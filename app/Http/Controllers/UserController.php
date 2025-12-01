<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(User::with('role'))
                ->addIndexColumn()
                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">'
                )
                ->addColumn(
                    'role',
                    fn($row) =>
                    $row->role ? $row->role->name : '-'
                )
                ->addColumn('status', function ($row) {
                    return $row->status
                        ? '<i class="fa fa-check text-success"></i>'
                        : '<i class="fa fa-times text-danger"></i>';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('users.edit', $encodedId);

                    return '<a href="' . $url . '" class="btn btn-sm btn-warning">
                                <i class="material-icons fs-6">edit</i> Edit
                            </a>';
                })
                ->rawColumns(['checkbox', 'status', 'action'])
                ->make(true);
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ], 400);
        }

        try {
            DB::transaction(
                fn() =>
                User::whereIn('id', $ids)->delete()
            );

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' user(s) deleted successfully.',
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
        return view('users.form', [
            'user' => null,
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->has('status') ? 1 : 0,
        ]);

        $request->validate([
            'name'      => 'required|max:255',
            'nip'       => 'required|max:25|unique:users,nip',
            'email'     => 'nullable|email|unique:users,email',
            'role_id'   => 'required',
            'password'  => 'required|min:6',
            'status'    => 'nullable|boolean'
        ]);

        User::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'User created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);

        return view('users.form', [
            'user' => User::findOrFail($id),
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, $encoded)
    {
        $id = base64_decode($encoded);
        $user = User::findOrFail($id);

        $request->merge([
            'status' => $request->has('status') ? 1 : 0,
        ]);

        $request->validate([
            'name'      => 'required',
            'nip'       => 'required|max:25|unique:users,nip,' . $user->id,
            'email'     => 'nullable|email|unique:users,email,' . $user->id,
            'role_id'   => 'required',
            'password'  => 'nullable|min:6',
            'status'    => 'nullable|boolean'
        ]);

        $data = $request->all();
        if (empty($data['password'])) unset($data['password']);

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully'
        ]);
    }
}
