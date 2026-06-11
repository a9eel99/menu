<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'restaurant_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'price',
        'old_price',
        'image',
        'is_available',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'menu_item_tag');
    }

    public function getName($locale = null)
    {
        $locale = $locale ?? session('locale', 'ar');
        return $locale === 'ar' ? $this->name_ar : ($this->name_en ?: $this->name_ar);
    }

    public function getDescription($locale = null)
    {
        $locale = $locale ?? session('locale', 'ar');
        return $locale === 'ar' ? $this->description_ar : ($this->description_en ?: $this->description_ar);
    }

    public function getImageUrl()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function hasImage()
    {
        return $this->image && Storage::disk('public')->exists($this->image);
    }

    public function hasDiscount()
    {
        return $this->old_price && $this->old_price > $this->price;
    }

    public function getDiscountPercentage()
    {
        if (!$this->hasDiscount()) return 0;
        return round((($this->old_price - $this->price) / $this->old_price) * 100);
    }

    public function getFormattedPrice()
    {
        $symbol = $this->restaurant ? $this->restaurant->currency_symbol : 'ر.س';
        return number_format($this->price, 2) . ' ' . $symbol;
    }
}
