<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل/إطفاء بطاقة "قيّمنا على Google" بصفحة المنيو لكل مطعم
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('review_prompt_enabled')->default(false)->after('google_reviews_url');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('review_prompt_enabled');
        });
    }
};
