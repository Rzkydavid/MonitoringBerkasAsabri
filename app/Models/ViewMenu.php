<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewMenu extends Model
{
    protected $table = 'view_menus'; // name of your view

    public $timestamps = false; // views do not have timestamps

    protected $primaryKey = 'id'; // required for DataTables
}
