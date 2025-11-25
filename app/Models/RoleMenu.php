<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    protected $table = 'role_menu';

    protected $fillable = [
        'role_id',
        'menu_id',
        'parent_menu_id',
        'order',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function parentMenu()
    {
        return $this->belongsTo(Menu::class, 'parent_menu_id');
    }
}
