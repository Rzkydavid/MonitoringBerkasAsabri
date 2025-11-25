<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePrivilege extends Model
{
    protected $fillable = ['role_id', 'privilege_name'];

    public function privilege()
    {
        return $this->belongsTo(Privilege::class, 'privilege_name', 'name');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
