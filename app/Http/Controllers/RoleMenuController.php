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

                ->editColumn('order', function ($row) {
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
            'role_id'   => 'required',
            'menu_id'   => 'required',
            'parent_menu_id' => 'nullable',
            'order'     => 'required|numeric|min:1',
        ]);

        // Shift existing orders for same role
        RoleMenu::where('role_id', $request->role_id)
            ->where('order', '>=', $request->order)
            ->increment('order');

        RoleMenu::create([
            'role_id'   => $request->role_id,
            'menu_id'   => $request->menu_id,
            'parent_menu_id' => $request->parent_menu_id,
            'order'     => $request->order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role Menu created successfully'
        ]);
    }


    public function edit($encoded)
    {
        $id = base64_decode($encoded);

        $role_menu = RoleMenu::findOrFail($id);
        $roles    = Role::all();
        $menus    = Menu::all();
        $parents  = Menu::all();

        return view('roles-menus.form', compact('role_menu', 'roles', 'menus', 'parents'));
    }

    public function update(Request $request, $encoded)
    {
        $id = base64_decode($encoded);
        $roleMenu = RoleMenu::findOrFail($id);

        $request->validate([
            'role_id'   => 'required',
            'menu_id'   => 'required',
            'parent_menu_id' => 'nullable',
            'order'     => 'required|numeric|min:1',
        ]);

        $oldOrder = $roleMenu->order;
        $newOrder = $request->order;

        // Handle reorder only if the order changes
        if ($newOrder != $oldOrder) {

            if ($newOrder < $oldOrder) {
                // Move UP
                RoleMenu::where('role_id', $roleMenu->role_id)
                    ->whereBetween('order', [$newOrder, $oldOrder - 1])
                    ->increment('order');
            } else {
                // Move DOWN
                RoleMenu::where('role_id', $roleMenu->role_id)
                    ->whereBetween('order', [$oldOrder + 1, $newOrder])
                    ->decrement('order');
            }
        }

        $roleMenu->update([
            'menu_id'   => $request->menu_id,
            'parent_menu_id' => $request->parent_menu_id,
            'order'     => $newOrder,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Role Menu updated successfully'
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
