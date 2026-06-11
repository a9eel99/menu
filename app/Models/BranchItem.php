<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'menu_item_id',
        'price',
        'old_price',
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Get name from menu item
    public function getName($locale = null)
    {
        return $this->menuItem->getName($locale);
    }

    public function getDescription($locale = null)
    {
        return $this->menuItem->getDescription($locale);
    }

    public function getFormattedPrice()
    {
        return number_format($this->price, 2);
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

    // Get image from menu item
    public function getImageUrlAttribute()
    {
        return $this->menuItem->image_url;
    }
}
