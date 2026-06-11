<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, boolean, image, json
            $table->string('group')->default('general'); // general, appearance, contact
            $table->timestamps();
        });

        // إدخال الإعدادات الافتراضية
        $settings = [
            // General
            ['key' => 'site_name', 'value' => 'QR Menu', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_name_ar', 'value' => 'المنيو الرقمي', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Digital Menu System', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description_ar', 'value' => 'نظام القوائم الرقمية', 'type' => 'text', 'group' => 'general'],
            ['key' => 'default_language', 'value' => 'ar', 'type' => 'text', 'group' => 'general'],
            ['key' => 'allow_registration', 'value' => '1', 'type' => 'boolean', 'group' => 'general'],
            
            // Appearance
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'appearance'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'appearance'],
            ['key' => 'primary_color', 'value' => '#c9a227', 'type' => 'text', 'group' => 'appearance'],
            ['key' => 'secondary_color', 'value' => '#1a1a2e', 'type' => 'text', 'group' => 'appearance'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'info@example.com', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => '', 'type' => 'text', 'group' => 'contact'],
            ['key' => 'contact_address', 'value' => '', 'type' => 'text', 'group' => 'contact'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};