<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingButton extends Model
{
    protected $fillable = [
        'restaurant_id',
        'type',
        'title_ar',
        'title_en',
        'subtitle_ar',
        'subtitle_en',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getTitle($locale = 'ar')
    {
        return $locale === 'ar' ? ($this->title_ar ?: $this->title_en) : ($this->title_en ?: $this->title_ar);
    }

    public function getSubtitle($locale = 'ar')
    {
        return $locale === 'ar' ? ($this->subtitle_ar ?: $this->subtitle_en) : ($this->subtitle_en ?: $this->subtitle_ar);
    }

    public static function getDefaultButtons()
    {
        return [
            [
                'type' => 'menu',
                'title_ar' => 'قائمة الطعام',
                'title_en' => 'Food Menu',
                'subtitle_ar' => 'افتح المنيو',
                'subtitle_en' => 'Open the menu',
                'icon' => 'utensils',
                'sort_order' => 1,
            ],
            [
                'type' => 'branches',
                'title_ar' => 'فروعنا',
                'title_en' => 'Our Branches',
                'subtitle_ar' => 'شاهد جميع الفروع',
                'subtitle_en' => 'View all branches',
                'icon' => 'map-marker-alt',
                'sort_order' => 2,
            ],
            [
                'type' => 'phone',
                'title_ar' => 'اتصل بنا',
                'title_en' => 'Call Us',
                'subtitle_ar' => '',
                'subtitle_en' => '',
                'icon' => 'phone-alt',
                'sort_order' => 3,
            ],
            [
                'type' => 'location',
                'title_ar' => 'موقعنا',
                'title_en' => 'Our Location',
                'subtitle_ar' => 'افتح في خرائط جوجل',
                'subtitle_en' => 'Open in Google Maps',
                'icon' => 'map-marker-alt',
                'sort_order' => 4,
            ],
            [
                'type' => 'reviews',
                'title_ar' => 'قيّمنا على جوجل',
                'title_en' => 'Rate us on Google',
                'subtitle_ar' => 'شاركنا رأيك',
                'subtitle_en' => 'Share your feedback',
                'icon' => 'star',
                'sort_order' => 5,
            ],
        ];
    }
}
