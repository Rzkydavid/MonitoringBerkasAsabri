<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\RoleMenu;
use App\Models\Menu;

class RoleMenuController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('roles-menus.index', compact('roles'));
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $roleId = $request->role_id;

            // If no role selected, return empty table
            if (!$roleId) {
                return DataTables::of(collect([]))->make(true);
            }

            $query = RoleMenu::with(['menu', 'parentMenu'])
                ->where('role_id', $roleId);

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })

                ->addColumn('menu_name', function ($row) {
                    return $row->menu->name ?? '-';
                })

                ->addColumn('parent_menu_name', function ($row) {
                    return $row->parentMenu->name ?? '-';
                })

                ->addColumn('order', function ($row) {
                    return $row->order ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('roles-menus.edit', $encodedId);

                    return '
                        <a href="' . $url . '" class="btn btn-sm btn-warning
                            data-bs-toggle="tooltip"
                            title="Edit"">
                            <i class="material-icons fs-6">edit</i> Edit
                        </a>
                    ';
                })

                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('roles-menus.form', [
            'roleMenu' => null,
            'roles'    => Role::all(),
            'menus'    => Menu::all(),
            'parents'  => Menu::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'  => 'required',
            'menu_id'  => 'required',
            'parent_id' => 'nullable',
        ]);

        RoleMenu::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role menu created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);

        $roleMenu = RoleMenu::findOrFail($id);
        $roles    = Role::all();
        $menus    = Menu::all();
        $parents  = Menu::all();

        return view('roles-menus.form', compact('roleMenu', 'roles', 'menus', 'parents'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'role_id'  => 'required',
            'menu_id'  => 'required',
            'parent_id' => 'nullable',
        ]);

        $id = base64_decode($encoded);

        $roleMenu = RoleMenu::findOrFail($id);
        $roleMenu->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role menu updated successfully'
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
                RoleMenu::whereIn('id', $ids)->delete();
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
}
