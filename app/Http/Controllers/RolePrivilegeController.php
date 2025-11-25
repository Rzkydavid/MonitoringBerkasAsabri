<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePrivilege;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RolePrivilegeController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles-privileges.index', compact('roles'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $roleId = $request->role_id;

            // If no role selected, return empty.
            if (!$roleId) {
                return DataTables::of(collect([]))->make(true);
            }

            $query = RolePrivilege::with('privilege')
                ->where('role_id', $roleId);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('name', fn($row) => $row->privilege->name ?? '-')
                ->rawColumns(['checkbox'])
                ->make(true);
        }
    }

    public function assign(Request $request)
    {
        $request->validate([
            'role_id' => 'required',
            'privileges' => 'required|array',
        ]);

        foreach ($request->privileges as $privilegeName) {
            RolePrivilege::create([
                'role_id' => $request->role_id,
                'privilege_name' => $privilegeName,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Privileges added successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ], 400);
        }

        try {
            DB::transaction(function () use ($ids) {
                RolePrivilege::whereIn('id', $ids)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' item(s) deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }


    public function create()
    {
        return view('roles-privileges.form', [
            'role_privilege' => null,  // add form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:255',
            'route'     => 'required|max:255',
        ]);

        RolePrivilege::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role Privilege created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $role_privilege = RolePrivilege::findOrFail($id);
        return view('roles-privileges.form', compact('role_privilege'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
        ]);

        $id = base64_decode($encoded);
        $menu = RolePrivilege::findOrFail($id);

        $menu->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role Privilege updated successfully'
        ]);
    }
}
