<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function rolePrivileges()
    {
        return $this->hasMany(RolePrivilege::class);
    }

    public function privileges()
    {
        return $this->rolePrivileges->pluck('privilege_name')->toArray();
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'role_menu');
    }

}
