<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name_ar',
        'name_en',
        'icon',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function availableItems()
    {
        return $this->menuItems()->where('is_available', true);
    }

    public function getName($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar);
    }

    public function getImageUrl()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
