<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ===================== الأدوار فقط (بدون صلاحيات معقدة) =====================
        
        // 1. Admin - يقدر يسوي كل شي
        Role::updateOrCreate(
            ['name' => 'admin'],
            ['name_ar' => 'مدير', 'is_system' => true]
        );

        // 2. Staff - يقدر يسوي كل شي ما عدا إضافة مطعم جديد
        Role::updateOrCreate(
            ['name' => 'staff'],
            ['name_ar' => 'موظف', 'is_system' => true]
        );
    }
}