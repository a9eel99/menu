<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * صفحات منيو الـ PDF كصور (أسرع بكثير من عرض الـ PDF على التلفون)
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->json('menu_pages')->nullable()->after('menu_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('menu_pages');
        });
    }
};
