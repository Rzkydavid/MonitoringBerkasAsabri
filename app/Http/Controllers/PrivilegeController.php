<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Privilege;
use App\Models\RolePrivilege;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class PrivilegeController extends Controller
{
    public function index()
    {
        return view('privileges.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Privilege::query())
                ->addIndexColumn()
                ->make(true);
        }
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

    public function available(Request $request)
    {
        if ($request->ajax()) {

            $roleId = $request->role_id;

            if (!$roleId) {
                return DataTables::of(collect([]))->make(true);
            }

            // Get privileges already assigned
            $assigned = RolePrivilege::where('role_id', $roleId)
                ->pluck('privilege_name')
                ->toArray();

            // Get unassigned privileges
            $query = Privilege::whereNotIn('name', $assigned);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="avail-checkbox table-checkbox" value="' . $row->name . '">';
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }
}
