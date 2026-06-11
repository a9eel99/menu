<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            [
                'name_ar' => 'جديد',
                'name_en' => 'New',
                'icon' => '🆕',
                'color' => '#28a745',
            ],
            [
                'name_ar' => 'حار',
                'name_en' => 'Spicy',
                'icon' => '🌶️',
                'color' => '#dc3545',
            ],
            [
                'name_ar' => 'نباتي',
                'name_en' => 'Vegetarian',
                'icon' => '🥬',
                'color' => '#28a745',
            ],
            [
                'name_ar' => 'الأكثر مبيعاً',
                'name_en' => 'Bestseller',
                'icon' => '⭐',
                'color' => '#fd7e14',
            ],
            [
                'name_ar' => 'اختيار الشيف',
                'name_en' => "Chef's Special",
                'icon' => '👨‍🍳',
                'color' => '#6f42c1',
            ],
            [
                'name_ar' => 'عرض خاص',
                'name_en' => 'Special Offer',
                'icon' => '🏷️',
                'color' => '#e63946',
            ],
        ];

        foreach ($tags as $index => $tag) {
            Tag::updateOrCreate(
                ['name_ar' => $tag['name_ar']],
                array_merge($tag, ['sort_order' => $index])
            );
        }
    }
}