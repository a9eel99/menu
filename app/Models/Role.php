<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'name_ar',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // ==================== Relationships ====================

    public function users()
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_roles');
    }
}