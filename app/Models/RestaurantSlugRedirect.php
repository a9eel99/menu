<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSlugRedirect extends Model
{
    protected $fillable = [
        'restaurant_id',
        'slug',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
