<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\StatusBerkas;

class StatusBerkasController extends Controller
{
    public function index()
    {
        return view('status-berkas.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(StatusBerkas::query())
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('status-berkas.edit', $encodedId);

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
        return view('status-berkas.form', [
            'status_berkas' => null,  // add form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_terkini' => 'required|max:255',
            'next_step' => 'required|max:255',
        ]);

        StatusBerkas::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Status Berkas created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $status_berkas = StatusBerkas::findOrFail($id);
        return view('status-berkas.form', compact('status_berkas'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'status_terkini' => 'required|max:255',
            'next_step' => 'required|max:255',
        ]);

        $id = base64_decode($encoded);
        $status_berkas = StatusBerkas::findOrFail($id);

        $status_berkas->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Status Berkas updated successfully'
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
                StatusBerkas::whereIn('id', $ids)->delete();
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
