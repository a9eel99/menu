<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\SocialLink;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // أولاً: إنشاء الصلاحيات والأدوار
        $this->call(PermissionSeeder::class);
        
        // إنشاء الـ Tags
        $this->call(TagSeeder::class);
        
        // Create demo admin user
        $user = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'is_active' => true,
        ]);
        
        // إعطاء دور admin للمستخدم
        $user->assignRole('admin');

        // Create demo restaurant (الإعدادات مدمجة الآن)
        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name_ar' => 'مطعم البيت الشامي',
            'name_en' => 'Al Bait Al Shami Restaurant',
            'slug' => 'al-bait-al-shami',
            'description_ar' => 'أشهى المأكولات الشامية التقليدية',
            'description_en' => 'The finest traditional Levantine cuisine',
            'phone' => '+962791234567',
            'email' => 'info@albait.com',
            'google_maps_url' => 'https://maps.google.com',
            'primary_color' => '#c9a227',
            'secondary_color' => '#1a1a2e',
            'is_active' => true,
        ]);

        // Create categories
        $appetizers = Category::create([
            'restaurant_id' => $restaurant->id,
            'name_ar' => 'المقبلات',
            'name_en' => 'Appetizers',
            'icon' => 'fas fa-leaf',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $grills = Category::create([
            'restaurant_id' => $restaurant->id,
            'name_ar' => 'المشاوي',
            'name_en' => 'Grills',
            'icon' => 'fas fa-fire',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $drinks = Category::create([
            'restaurant_id' => $restaurant->id,
            'name_ar' => 'المشروبات',
            'name_en' => 'Beverages',
            'icon' => 'fas fa-glass-water',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Create menu items
        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $appetizers->id,
            'name_ar' => 'حمص',
            'name_en' => 'Hummus',
            'description_ar' => 'حمص مهروس بالطحينة وزيت الزيتون',
            'description_en' => 'Mashed chickpeas with tahini and olive oil',
            'price' => 3.50,
            'is_available' => true,
            'is_featured' => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $appetizers->id,
            'name_ar' => 'متبل',
            'name_en' => 'Mutabal',
            'description_ar' => 'باذنجان مشوي مع الطحينة',
            'description_en' => 'Grilled eggplant with tahini',
            'price' => 4.00,
            'is_available' => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $grills->id,
            'name_ar' => 'مشكل مشاوي',
            'name_en' => 'Mixed Grill',
            'description_ar' => 'تشكيلة من اللحوم المشوية',
            'description_en' => 'Assorted grilled meats',
            'price' => 18.00,
            'old_price' => 22.00,
            'is_available' => true,
            'is_featured' => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $grills->id,
            'name_ar' => 'شيش طاووق',
            'name_en' => 'Shish Tawook',
            'description_ar' => 'صدر دجاج متبل ومشوي',
            'description_en' => 'Marinated and grilled chicken breast',
            'price' => 12.00,
            'is_available' => true,
        ]);

        MenuItem::create([
            'restaurant_id' => $restaurant->id,
            'category_id' => $drinks->id,
            'name_ar' => 'عصير ليمون بالنعناع',
            'name_en' => 'Lemon Mint Juice',
            'description_ar' => 'عصير طازج منعش',
            'description_en' => 'Fresh refreshing juice',
            'price' => 2.50,
            'is_available' => true,
        ]);

        // Create social links
        SocialLink::create([
            'restaurant_id' => $restaurant->id,
            'platform' => 'instagram',
            'url' => 'https://instagram.com/albaitshami',
            'is_active' => true,
        ]);

        SocialLink::create([
            'restaurant_id' => $restaurant->id,
            'platform' => 'whatsapp',
            'url' => '962791234567',
            'is_active' => true,
        ]);
    }
}