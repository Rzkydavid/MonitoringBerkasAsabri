<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        return view('menus.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Menu::query())
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('menus.edit', $encodedId);

                    return '
                        <a href="' . $url . '" class="btn btn-sm btn-warning
                            data-bs-toggle="tooltip"
                            title="Edit"">
                            <i class="material-icons">edit</i> Edit
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
                Menu::whereIn('id', $ids)->delete();
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
        return view('menus.form', [
            'menu' => null,  // add form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:255',
            'route'     => 'required|max:255',
        ]);

        Menu::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $menu = Menu::findOrFail($id);
        return view('menus.form', compact('menu'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'name' => 'required',
            'route' => 'required',
        ]);

        $id = base64_decode($encoded);
        $menu = Menu::findOrFail($id);

        $menu->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Menu updated successfully'
        ]);
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu deleted');
    }
}
