<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('restaurants')->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('slug')->unique();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('google_maps_url', 500)->nullable();
            $table->string('google_reviews_url', 500)->nullable();
            $table->string('working_hours_ar')->nullable();
            $table->string('working_hours_en')->nullable();
            $table->string('currency', 10)->default('SAR');
            $table->string('currency_symbol', 10)->default('ر.س');
            $table->string('primary_color', 20)->default('#c8a165');
            $table->string('secondary_color', 20)->default('#1a1a2e');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
