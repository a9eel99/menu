<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * الروابط القديمة للمطاعم - عشان أي QR مطبوع يضل شغال بعد تغيير الرابط
     */
    public function up(): void
    {
        Schema::create('restaurant_slug_redirects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_slug_redirects');
    }
};
