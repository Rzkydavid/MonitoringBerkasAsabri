<?php

namespace App\Http\Controllers;

use App\Models\RiwayatBerkasKlaim;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class RiwayatBerkasKlaimController extends Controller
{
    public function index()
    {
        return view('riwayat-berkas-klaim.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $period = $request->period;

            // If no period selected, return empty table
            if (!$period || !preg_match('/^\d{4}-\d{2}$/', $period)) {
                return DataTables::of(collect([]))->make(true);
            }

            [$year, $month] = explode('-', $period);

            $query = RiwayatBerkasKlaim::query()
                ->select('riwayat_berkas_klaim.*')
                ->join(
                    'berkas_klaim',
                    'berkas_klaim.id',
                    '=',
                    'riwayat_berkas_klaim.berkas_klaim_id'
                )
                ->with(['berkasKlaim', 'statusBerkas', 'user'])
                ->whereYear('riwayat_berkas_klaim.created_at', (int) $year)
                ->whereMonth('riwayat_berkas_klaim.created_at', (int) $month)

                // ordering
                ->orderBy('riwayat_berkas_klaim.created_at', 'desc')
                ->orderBy('berkas_klaim.nama_peserta', 'asc');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn(
                    'nama_peserta',
                    fn($row) =>
                    optional($row->berkasKlaim)->nama_peserta
                )
                ->addColumn(
                    'status_terkini',
                    fn($row) =>
                    optional($row->statusBerkas)->status_terkini
                )
                ->addColumn(
                    'next_step',
                    fn($row) =>
                    optional($row->statusBerkas)->next_step
                )
                ->addColumn(
                    'user_name',
                    fn($row) =>
                    optional($row->user)->name
                )
                ->addColumn(
                    'created_at',
                    fn($row) =>
                    $row->created_at
                        ? $row->created_at->format('d M Y H:i')
                        : '-'
                )
                ->make(true);
        }
    }


    // public function data(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $query = RiwayatBerkasKlaim::with(['berkasKlaim', 'statusBerkas', 'user']);

    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->addColumn('nama_peserta', fn($row) => optional($row->berkasKlaim)->nama_peserta)
    //             ->addColumn('status_terkini', fn($row) => optional($row->statusBerkas)->status_terkini)
    //             ->addColumn('next_step', fn($row) => optional($row->statusBerkas)->next_step)
    //             ->addColumn('user_name', fn($row) => optional($row->user)->name)
    //             ->addColumn('created_at', fn($row) => $row->created_at->format('d M Y H:i'))
    //             ->make(true);
    //     }
    // }

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
                RiwayatBerkasKlaim::whereIn('id', $ids)->delete();
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
