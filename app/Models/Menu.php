<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'route',
        'icon'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_menu')
            ->withPivot(['parent_menu_id', 'order'])
            ->withTimestamps();
    }
}
