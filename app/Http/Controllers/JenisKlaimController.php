<?php

namespace App\Http\Controllers;

use App\Models\JenisKlaim;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class JenisKlaimController extends Controller
{
    public function index()
    {
        return view('jenis-klaim.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(JenisKlaim::query())
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })
                ->addColumn('action', function ($row) {
                    $encodedId = base64_encode($row->id);
                    $url = route('jenis-klaim.edit', $encodedId);

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
        return view('jenis-klaim.form', [
            'jenis_klaim' => null,  // add form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|max:255',
        ]);

        JenisKlaim::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jenis Klaim berhasil dibuat'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $jenis_klaim = JenisKlaim::findOrFail($id);
        return view('jenis-klaim.form', compact('jenis_klaim'));
    }

    public function update(Request $request, $encoded)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $id = base64_decode($encoded);
        $jenis_klaim = JenisKlaim::findOrFail($id);

        $jenis_klaim->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Jenis Klaim berhasil diperbarui'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item yang dipilih.',
            ], 400);
        }

        try {
            DB::transaction(function () use ($ids) {
                JenisKlaim::whereIn('id', $ids)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' item berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal Hapus: ' . $e->getMessage(),
            ], 500);
        }
    }
}
