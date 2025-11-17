<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Role::query())
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('roles.edit', $encodedId);

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
                Role::whereIn('id', $ids)->delete();
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
        return view('roles.form', [
            'Role' => null,  // add form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:255',
        ]);

        Role::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $role = Role::findOrFail($id);
        return view('roles.form', compact('role'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $id = base64_decode($encoded);
        $role = Role::findOrFail($id);

        $role->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully'
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Role deleted');
    }
}
