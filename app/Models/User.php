<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'is_active',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'restaurant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    protected $attributes = [
        'is_active' => true,
    ];

    // ==================== Relationships ====================

    /**
     * المطعم المعيّن عليه (للموظفين)
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    /**
     * المطاعم التي يملكها
     */
    public function ownedRestaurants()
    {
        return $this->hasMany(Restaurant::class, 'user_id');
    }

    /**
     * من أنشأ هذا المستخدم
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * المستخدمين الذين أنشأهم
     */
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // ==================== Accessors ====================

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=random';
    }

    // ==================== Methods ====================

    /**
     * تسجيل آخر دخول
     */
    public function recordLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * الحصول على المطاعم المتاحة للمستخدم
     */
    public function getAccessibleRestaurants()
    {
        // Super Admin: كل المطاعم
        if ($this->isSuperAdmin()) {
            return Restaurant::all();
        }

        // جمع المطاعم من الأدوار
        $restaurantIds = $this->roles()
            ->whereNotNull('model_has_roles.restaurant_id')
            ->pluck('model_has_roles.restaurant_id')
            ->unique();

        // إضافة المطاعم المملوكة
        $ownedIds = $this->ownedRestaurants()->pluck('id');

        return Restaurant::whereIn('id', $restaurantIds->merge($ownedIds))->get();
    }

    /**
     * هل يملك مطعم معين؟
     */
    public function ownsRestaurant($restaurantId): bool
    {
        return $this->ownedRestaurants()->where('id', $restaurantId)->exists();
    }

    /**
     * هل لديه وصول لمطعم معين؟
     */
    public function hasAccessToRestaurant($restaurantId): bool
    {
        if ($this->isSuperAdmin()) return true;
        if ($this->ownsRestaurant($restaurantId)) return true;
        
        return $this->roles()
            ->wherePivot('restaurant_id', $restaurantId)
            ->exists();
    }
}