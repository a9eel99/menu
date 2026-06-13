<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'linked_group_id',
        'show_linked_selector',
        'name_ar',
        'name_en',
        'slug',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'logo',
        'cover_image',
        'phone',
        'whatsapp',
        'email',
        'google_maps_url',
        'google_reviews_url',
        'working_hours_ar',
        'working_hours_en',
        'currency',
        'currency_symbol',
        'primary_color',
        'secondary_color',
        'is_active',
        'menu_type',
        'menu_pdf',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'show_linked_selector' => 'boolean',
    ];

    protected $attributes = [
        'currency' => 'SAR',
        'currency_symbol' => 'ر.س',
        'primary_color' => '#FF6B35',
        'secondary_color' => '#2E2E2E',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = static::generateUniqueSlug($restaurant->name_en ?: $restaurant->name_ar);
            }
        });
    }

    public static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        if (empty($slug)) {
            $slug = 'restaurant-' . Str::random(6);
        }

        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ==================== Relationships ====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(Restaurant::class, 'parent_id');
    }

    public function branches()
    {
        return $this->hasMany(Restaurant::class, 'parent_id')->orderBy('name_ar');
    }

    public function activeBranches()
    {
        return $this->branches()->where('is_active', true);
    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('sort_order');
    }

    public function activeCategories()
    {
        return $this->categories()->where('is_active', true);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function socialLinks()
    {
        return $this->hasMany(SocialLink::class);
    }

    public function landingButtons()
    {
        return $this->hasMany(LandingButton::class)->orderBy('sort_order');
    }

    public function activeLandingButtons()
    {
        return $this->landingButtons()->where('is_active', true);
    }

    public function getOrCreateLandingButtons()
    {
        if ($this->landingButtons()->count() === 0) {
            foreach (LandingButton::getDefaultButtons() as $button) {
                $this->landingButtons()->create($button);
            }
        }
        return $this->landingButtons()->orderBy('sort_order')->get();
    }

    public function linkedRestaurants()
    {
        if (!$this->linked_group_id) {
            return collect();
        }
        return static::where('linked_group_id', $this->linked_group_id)
            ->where('is_active', true)
            ->where('id', '!=', $this->id)
            ->get();
    }

    public function allLinkedRestaurants()
    {
        if (!$this->linked_group_id) {
            return collect([$this]);
        }
        return static::where('linked_group_id', $this->linked_group_id)
            ->where('is_active', true)
            ->orderBy('name_ar')
            ->get();
    }

    public function hasLinkedRestaurants()
    {
        return $this->linked_group_id && $this->linkedRestaurants()->count() > 0;
    }

    // ==================== Helpers ====================

    public function isMain()
    {
        return is_null($this->parent_id);
    }

    public function isBranch()
    {
        return !is_null($this->parent_id);
    }

    public function getMainRestaurant()
    {
        return $this->isMain() ? $this : $this->parent;
    }

    public function getAllBranches()
    {
        if ($this->isBranch()) {
            return $this->parent->branches;
        }
        return $this->branches;
    }

    public function getName($locale = null)
    {
        $locale = $locale ?? session('current_menu_locale', session('locale', 'ar'));
        
        if ($locale === 'ar') {
            return $this->name_ar;
        }
        
        return $this->name_en ?: $this->name_ar;
    }

    public function getAddress($locale = null)
    {
        $locale = $locale ?? session('locale', 'ar');
        return $locale === 'ar' ? $this->address_ar : ($this->address_en ?: $this->address_ar);
    }

    public function getWorkingHours($locale = null)
    {
        $locale = $locale ?? session('locale', 'ar');
        return $locale === 'ar' ? $this->working_hours_ar : ($this->working_hours_en ?: $this->working_hours_ar);
    }

    public function getLogoUrl()
    {
        // إذا عنده لوقو خاص
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        // إذا فرع وما عنده لوقو، يستخدم لوقو الأب
        if ($this->isBranch() && $this->parent && $this->parent->logo) {
            return asset('storage/' . $this->parent->logo);
        }
        return null;
    }

    public function getCoverUrl()
    {
        // إذا عنده غلاف خاص
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }
        // إذا فرع وما عنده غلاف، يستخدم غلاف الأب
        if ($this->isBranch() && $this->parent && $this->parent->cover_image) {
            return asset('storage/' . $this->parent->cover_image);
        }
        return null;
    }

    /**
     * جلب الوصف
     */
    public function getDescription($locale = null)
    {
        $locale = $locale ?? session('current_menu_locale', session('locale', 'ar'));
        
        if ($locale === 'ar') {
            return $this->description_ar;
        }
        
        return $this->description_en ?: $this->description_ar;
    }

    /**
     * جلب اللون الأساسي
     */
    public function getEffectivePrimaryColor()
    {
        return $this->primary_color ?? '#FF6B35';
    }

    /**
     * جلب اللون الثانوي
     */
    public function getEffectiveSecondaryColor()
    {
        return $this->secondary_color ?? '#2E2E2E';
    }

    public function getWhatsappUrl()
    {
        if ($this->whatsapp) {
            $number = preg_replace('/[^0-9]/', '', $this->whatsapp);
            return "https://wa.me/{$number}";
        }
        return null;
    }

    public function getMenuUrl()
    {
        return route('menu.landing', $this->slug);
    }

    public function getFormattedPrice($price)
    {
        return number_format($price, 2) . ' ' . $this->currency_symbol;
    }

    public function getMenuPdfUrl()
    {
        if ($this->menu_pdf) {
            return asset('storage/' . $this->menu_pdf);
        }
        return null;
    }

    public function isPdfMenu()
    {
        return $this->menu_type === 'pdf' && $this->menu_pdf;
    }
}