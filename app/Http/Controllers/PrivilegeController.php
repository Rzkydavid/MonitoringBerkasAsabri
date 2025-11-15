<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilege;
use Illuminate\Support\Facades\Route;

class PrivilegeController extends Controller
{
    public function index()
    {
        // Load all privileges
        $privileges = Privilege::all();

        // Return a view and pass the privileges
        return view('privileges.index', compact('privileges'));
    }

    public function populate()
    {
        $routes = Route::getRoutes();
        $privileges = [];

        foreach ($routes as $route) {
            $priv = $route->defaults['privilege'] ?? null;
            if ($priv)
                $privileges[] = $priv;
        }

        Privilege::truncate();
        // print_r($privileges);
        foreach (array_unique($privileges) as $p) {
            Privilege::create(['name' => $p]);
        }

        return 'Privileges repopulated successfully!';
    }
}
