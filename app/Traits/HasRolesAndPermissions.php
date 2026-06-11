<?php

namespace App\Traits;

use App\Models\Role;

trait HasRolesAndPermissions
{
    // ==================== Relationships ====================

    public function roles()
    {
        return $this->morphToMany(Role::class, 'model', 'model_has_roles');
    }

    // ==================== Role Methods ====================

    /**
     * إعطاء دور للمستخدم
     */
    public function assignRole($role)
    {
        $role = $this->getRole($role);
        if (!$role) return $this;

        if (!$this->roles()->where('role_id', $role->id)->exists()) {
            $this->roles()->attach($role->id);
        }
        
        return $this;
    }

    /**
     * إزالة دور من المستخدم
     */
    public function removeRole($role)
    {
        $role = $this->getRole($role);
        if (!$role) return $this;

        $this->roles()->detach($role->id);
        return $this;
    }

    /**
     * هل لديه دور معين؟
     */
    public function hasRole($role): bool
    {
        $role = $this->getRole($role);
        if (!$role) return false;

        return $this->roles()->where('role_id', $role->id)->exists();
    }

    // ==================== Helper Methods ====================

    private function getRole($role)
    {
        if (is_string($role)) {
            return Role::where('name', $role)->first();
        }
        if (is_numeric($role)) {
            return Role::find($role);
        }
        return $role;
    }

    // ==================== Quick Checks ====================

    /**
     * هل المستخدم Admin (مدير)؟
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * هل المستخدم موظف؟
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    /**
     * للتوافق - نفس isAdmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->isAdmin();
    }
}