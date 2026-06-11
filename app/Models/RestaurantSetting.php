<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'background_type',
        'background_color',
        'background_image',
        'primary_color',
        'secondary_color',
        'text_color',
        'font_family',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getBackgroundStyle()
    {
        if ($this->background_type === 'image' && $this->background_image) {
            return "background-image: url('" . asset('storage/' . $this->background_image) . "'); background-size: cover; background-position: center;";
        }
        return "background-color: {$this->background_color};";
    }

    public function getCssVariables()
    {
        return "
            --primary-color: {$this->primary_color};
            --secondary-color: {$this->secondary_color};
            --text-color: {$this->text_color};
            --font-family: '{$this->font_family}', sans-serif;
        ";
    }
}
