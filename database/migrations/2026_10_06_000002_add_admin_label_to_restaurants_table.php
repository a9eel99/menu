<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * اسم تمييزي يظهر بلوحة التحكم بس (مثلاً: إربد) للتفريق بين فروع بنفس الاسم
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('admin_label', 60)->nullable()->after('name_en');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('admin_label');
        });
    }
};
