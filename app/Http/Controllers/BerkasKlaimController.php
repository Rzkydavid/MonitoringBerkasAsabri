<?php

namespace App\Http\Controllers;

use App\Models\BerkasKlaim;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\JenisKlaim;
use App\Models\RiwayatBerkasKlaim;

class BerkasKlaimController extends Controller
{
    public function index()
    {
        return view('berkas-klaim.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {

            $redBadge   = fn($v) => '<span class="badge bg-gradient-danger">' . $v . '</span>';
            $greenBadge = fn($v) => '<span class="badge bg-gradient-success">' . $v . '</span>';

            $badge = function ($value, $field) use ($redBadge, $greenBadge) {

                switch ($field) {

                    case 'kelengkapan':
                        return $value === 'Lengkap'
                            ? $greenBadge('Lengkap')
                            : $redBadge('Tidak Lengkap');

                    case 'copy_dokumen':
                        return $value === 'Ya'
                            ? $greenBadge('Ya')
                            : $redBadge('Tidak');

                    case 'akta_kematian':
                        return $value === 'Valid'
                            ? $greenBadge('Valid')
                            : $redBadge('Tidak Valid');

                    case 'flagging':
                        return $value === 'Ya'
                            ? $redBadge('Ya')
                            : $greenBadge('Tidak');

                    case 'wa':
                        return $value === 'Tercantum'
                            ? $greenBadge('Tercantum')
                            : $redBadge('Tidak Tercantum');

                    case 'rekening':
                        return $value === 'Aktif'
                            ? $greenBadge('Aktif')
                            : $redBadge('Tidak Aktif');

                    case 'konfirmasi_ulang':
                        return $value === 'Ya'
                            ? $redBadge('Ya')
                            : $greenBadge('Tidak');

                    default:
                        return $value;
                }
            };

            $query = BerkasKlaim::with(['jenisKlaim', 'statusBerkas', 'creator']);

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">';
                })

                ->addColumn('jenis_klaim', fn($row) => optional($row->jenisKlaim)->name)
                ->addColumn('created_by_name', fn($row) => optional($row->creator)->name)

                ->addColumn('status_terkini', fn($row) => optional($row->statusBerkas)->status_terkini)
                ->addColumn('next_step', fn($row) => optional($row->statusBerkas)->next_step)

                ->addColumn('tgl_berkas_diterima_cso', function ($row) {
                    return \Carbon\Carbon::parse($row->tgl_berkas_diterima_cso)->format('d M Y');
                })

                ->addColumn('status_terima_lainnya', fn($row) => $row->status_terima_klaim_masuk_lainnya)

                // === ENUM BADGE FIELDS ===
                // KELENGKAPAN PERSYARATAN
                ->addColumn(
                    'kelengkapan_persyaratan',
                    fn($row) => $badge($row->kelengkapan_persyaratan, 'kelengkapan')
                )

                // COPY DOKUMEN
                ->addColumn(
                    'copy_seluruh_dokumen_terbaca_jelas',
                    fn($row) => $badge($row->copy_seluruh_dokumen_terbaca_jelas, 'copy_dokumen')
                )

                // AKTA KEMATIAN
                ->addColumn(
                    'keaslian_akta_kematian',
                    fn($row) => $badge($row->keaslian_akta_kematian, 'akta_kematian')
                )

                // FLAGGING KREDIT
                ->addColumn(
                    'flagging_kredit',
                    fn($row) => $badge($row->flagging_kredit, 'flagging')
                )

                // NOMOR WA
                ->addColumn(
                    'nomor_wa_pengaju',
                    fn($row) => $badge($row->nomor_wa_pengaju, 'wa')
                )

                // NOMOR REKENING
                ->addColumn(
                    'nomor_rekening_pengaju',
                    fn($row) => $badge($row->nomor_rekening_pengaju, 'rekening')
                )

                // PERLU KONFIRMASI ULANG
                ->addColumn(
                    'perlu_konfirmasi_ulang',
                    fn($row) => $badge($row->perlu_konfirmasi_ulang, 'konfirmasi_ulang')
                )


                // plain text columns still shown normally
                ->addColumn('catatan_konfirmasi', fn($row) => $row->catatan_konfirmasi)
                ->addColumn('selesai_konfirmasi', function ($row) {
                    return \Carbon\Carbon::parse($row->selesai_konfirmasi)->format('d M Y');
                })

                // Action button
                ->addColumn('action', function ($row) {
                    $user = Auth::user();

                    $canCSO = $user->role->name === 'Customer Service Officer';
                    $canEdit = $canCSO && optional($row->statusBerkas)->status_terkini === "Diterima CSO";

                    if ($canEdit) {
                        return '<a href="' . route('berkas-klaim.edit', $row->id) . '" class="btn btn-warning btn-sm">EDIT</a>';
                    }

                    return '';
                })
                // ->addColumn('action', function ($row) {
                //     $encodedId = base64_encode($row->id);
                //     $url = route('berkas-klaim.edit', $encodedId);

                //     return '
                //     <a href="' . $url . '" class="btn btn-sm btn-warning"
                //         data-bs-toggle="tooltip"
                //         title="Edit">
                //         <i class="material-icons fs-6">edit</i> Edit
                //     </a>
                // ';
                // })

                ->rawColumns([
                    'checkbox',
                    'action',

                    // badge-based fields
                    'kelengkapan_persyaratan',
                    'copy_seluruh_dokumen_terbaca_jelas',
                    'keaslian_akta_kematian',
                    'flagging_kredit',
                    'nomor_wa_pengaju',
                    'nomor_rekening_pengaju',
                    'perlu_konfirmasi_ulang',
                ])

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
                BerkasKlaim::whereIn('id', $ids)->delete();
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
        return view('berkas-klaim.form', [
            'berkasKlaim' => null,
            'jenisKlaim'  => JenisKlaim::all()
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_peserta'                      => 'required|string|max:255',
            'nomor_identitas'          => 'required|string|max:255',
            'jenis_klaim_id'           => 'required|integer',
            'tgl_berkas_diterima_cso'  => 'required|date',
            'status_terima_klaim_masuk' => 'required|in:Datang Langsung,ASABRI Link,Klaim Online,Email,Lainnya',
            'status_terima_klaim_masuk_lainnya' => 'required_if:status_terima_klaim_masuk,Lainnya',

            'kelengkapan_persyaratan' => 'required|in:Lengkap,Tidak Lengkap',
            'copy_seluruh_dokumen_terbaca_jelas' => 'required|in:Ya,Tidak',
            'keaslian_akta_kematian' => 'required|in:Valid,Tidak Valid',
            'flagging_kredit' => 'required|in:Ya,Tidak',
            'nomor_wa_pengaju' => 'required|in:Tercantum,Tidak Tercantum',
            'nomor_rekening_pengaju' => 'required|in:Aktif,Tidak Aktif',
            'perlu_konfirmasi_ulang' => 'required|in:Ya,Tidak',
        ]);

        // take only validated data
        $data = $request->all();

        // override default values
        $data['status_berkas_id'] = 1;
        $data['created_by'] = Auth::user()->id;

        $klaim = BerkasKlaim::create($data);

        RiwayatBerkasKlaim::create([
            'berkas_klaim_id'  => $klaim->id,
            'status_berkas_id' => 1, // status awal
            'user_id'          => Auth::user()->id,
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Berkas klaim created successfully'
        ]);
    }

    public function edit($encoded)
    {
        $id = base64_decode($encoded);
        $berkasKlaim = BerkasKlaim::findOrFail($id);
        $jenisKlaim = JenisKlaim::all();
        return view('berkas-klaim.form', compact('berkasKlaim', 'jenisKlaim'));
    }

    public function update(Request $request, $encoded)
    {
        $id = base64_decode($encoded);
        $berkasKlaim = BerkasKlaim::findOrFail($id);

        $request->validate([
            'nama_peserta'                      => 'required|string|max:255',
            'nomor_identitas'          => 'required|string|max:255',
            'jenis_klaim_id'           => 'required|integer',
            'tgl_berkas_diterima_cso'  => 'required|date',
            'status_terima_klaim_masuk' => 'required|in:Datang Langsung,ASABRI Link,Klaim Online,Email,Lainnya',
            'status_terima_klaim_masuk_lainnya' => 'required_if:status_terima_klaim_masuk,Lainnya',

            'kelengkapan_persyaratan' => 'required|in:Lengkap,Tidak Lengkap',
            'copy_seluruh_dokumen_terbaca_jelas' => 'required|in:Ya,Tidak',
            'keaslian_akta_kematian' => 'required|in:Valid,Tidak Valid',
            'flagging_kredit' => 'required|in:Ya,Tidak',
            'nomor_wa_pengaju' => 'required|in:Tercantum,Tidak Tercantum',
            'nomor_rekening_pengaju' => 'required|in:Aktif,Tidak Aktif',
            'perlu_konfirmasi_ulang' => 'required|in:Ya,Tidak',
        ]);

        $berkasKlaim->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berkas klaim updated successfully'
        ]);
    }

    public function destroy(BerkasKlaim $berkasKlaim)
    {
        $berkasKlaim->delete();
        return back()->with('success', 'Berkas klaim deleted');
    }

    public function pendingTaskIndex()
    {
        return view('berkas-klaim.pending-task');
    }


    public function pendingTaskData(Request $request)
    {
        if ($request->ajax()) {

            $redBadge   = fn($v) => '<span class="badge bg-gradient-danger">' . $v . '</span>';
            $greenBadge = fn($v) => '<span class="badge bg-gradient-success">' . $v . '</span>';

            $badge = function ($value, $field) use ($redBadge, $greenBadge) {
                switch ($field) {
                    case 'kelengkapan':
                        return $value === 'Lengkap'
                            ? $greenBadge('Lengkap')
                            : $redBadge('Tidak Lengkap');

                    case 'copy_dokumen':
                        return $value === 'Ya'
                            ? $greenBadge('Ya')
                            : $redBadge('Tidak');

                    case 'akta_kematian':
                        return $value === 'Valid'
                            ? $greenBadge('Valid')
                            : $redBadge('Tidak Valid');

                    case 'flagging':
                        return $value === 'Ya'
                            ? $redBadge('Ya')
                            : $greenBadge('Tidak');

                    case 'wa':
                        return $value === 'Tercantum'
                            ? $greenBadge('Tercantum')
                            : $redBadge('Tidak Tercantum');

                    case 'rekening':
                        return $value === 'Aktif'
                            ? $greenBadge('Aktif')
                            : $redBadge('Tidak Aktif');

                    case 'konfirmasi_ulang':
                        return $value === 'Ya'
                            ? $redBadge('Ya')
                            : $greenBadge('Tidak');

                    default:
                        return $value;
                }
            };

            $user = Auth::user();
            $userRole = $user->role->name;

            /** =========================
             *  BASE QUERY (SELECT ALL)
             *  ========================= */
            $query = BerkasKlaim::with([
                'jenisKlaim',
                'statusBerkas',
                'creator'
            ]);

            /** =========================
             *  ROLE → NEXT_STEP FILTER
             *  ========================= */
            switch ($userRole) {
                case 'Customer Service Officer':
                    $query->whereHas(
                        'statusBerkas',
                        fn($q) =>
                        $q->where('next_step', 'Menunggu Diterima CSO')
                    );
                    break;

                case 'Proses':
                    $query->whereHas(
                        'statusBerkas',
                        fn($q) =>
                        $q->whereIn('next_step', [
                            'Menunggu diterima Tim Proses',
                            'Menunggu Input dan Verif',
                        ])
                    );
                    break;

                case 'Kepala Bidang':
                    $query->whereHas(
                        'statusBerkas',
                        fn($q) =>
                        $q->whereIn('next_step', [
                            'Menunggu diterima Kepala Bidang',
                            'Menunggu Approval Kepala Bidang',
                        ])
                    );
                    break;

                case 'Dosir':
                    $query->whereHas(
                        'statusBerkas',
                        fn($q) =>
                        $q->whereIn('next_step', [
                            'Menunggu Diterima Dosir',
                            'Menunggu Scan dan Arsip',
                            'Menunggu Penyimpanan Berkas Fisik',
                        ])
                    );
                    break;

                case 'Korespondensi':
                    $query->whereHas(
                        'statusBerkas',
                        fn($q) =>
                        $q->where('next_step', 'Menunggu Penyimpanan Berkas Fisik')
                    );
                    break;

                default:
                    // no access → empty table
                    $query->whereRaw('1 = 0');
                    break;
            }

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn(
                    'checkbox',
                    fn($row) =>
                    '<input type="checkbox" class="row-checkbox table-checkbox" value="' . $row->id . '">'
                )

                ->addColumn('jenis_klaim', fn($row) => optional($row->jenisKlaim)->name)
                ->addColumn('created_by_name', fn($row) => optional($row->creator)->name)

                ->addColumn('status_terkini', fn($row) => optional($row->statusBerkas)->status_terkini)
                ->addColumn('next_step', fn($row) => optional($row->statusBerkas)->next_step)

                ->addColumn(
                    'tgl_berkas_diterima_cso',
                    fn($row) =>
                    \Carbon\Carbon::parse($row->tgl_berkas_diterima_cso)->format('d M Y')
                )

                ->addColumn('status_terima_lainnya', fn($row) => $row->status_terima_klaim_masuk_lainnya)

                // === BADGE FIELDS ===
                ->addColumn(
                    'kelengkapan_persyaratan',
                    fn($row) => $badge($row->kelengkapan_persyaratan, 'kelengkapan')
                )
                ->addColumn(
                    'copy_seluruh_dokumen_terbaca_jelas',
                    fn($row) => $badge($row->copy_seluruh_dokumen_terbaca_jelas, 'copy_dokumen')
                )
                ->addColumn(
                    'keaslian_akta_kematian',
                    fn($row) => $badge($row->keaslian_akta_kematian, 'akta_kematian')
                )
                ->addColumn(
                    'flagging_kredit',
                    fn($row) => $badge($row->flagging_kredit, 'flagging')
                )
                ->addColumn(
                    'nomor_wa_pengaju',
                    fn($row) => $badge($row->nomor_wa_pengaju, 'wa')
                )
                ->addColumn(
                    'nomor_rekening_pengaju',
                    fn($row) => $badge($row->nomor_rekening_pengaju, 'rekening')
                )
                ->addColumn(
                    'perlu_konfirmasi_ulang',
                    fn($row) => $badge($row->perlu_konfirmasi_ulang, 'konfirmasi_ulang')
                )

                ->addColumn('catatan_konfirmasi', fn($row) => $row->catatan_konfirmasi)
                ->addColumn(
                    'selesai_konfirmasi',
                    fn($row) =>
                    \Carbon\Carbon::parse($row->selesai_konfirmasi)->format('d M Y')
                )

                ->rawColumns([
                    'checkbox',
                    'kelengkapan_persyaratan',
                    'copy_seluruh_dokumen_terbaca_jelas',
                    'keaslian_akta_kematian',
                    'flagging_kredit',
                    'nomor_wa_pengaju',
                    'nomor_rekening_pengaju',
                    'perlu_konfirmasi_ulang',
                ])
                ->make(true);
        }
    }

    public function acceptBerkas(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ], 400);
        }

        $user     = Auth::user();
        $roleName = $user->role->name;

        DB::beginTransaction();

        try {
            $berkasList = BerkasKlaim::with('statusBerkas')
                ->whereIn('id', $ids)
                ->get();

            foreach ($berkasList as $klaim) {

                $nextStep     = optional($klaim->statusBerkas)->next_step;
                $newStatusId  = null;

                switch ($roleName) {

                    case 'Customer Service Officer':
                        if ($nextStep === 'Menunggu Diterima CSO') {
                            $newStatusId = 1;
                        }
                        break;

                    case 'Proses':
                        if ($nextStep === 'Menunggu diterima Tim Proses') {
                            $newStatusId = 2;
                        } elseif ($nextStep === 'Menunggu Input dan Verif') {
                            $newStatusId = 4;
                        }
                        break;

                    case 'Kepala Bidang':
                        if ($nextStep === 'Menunggu diterima Kepala Bidang') {
                            $newStatusId = 5;
                        } elseif ($nextStep === 'Menunggu Approval Kepala Bidang') {
                            $newStatusId = 7;
                        }
                        break;

                    case 'Dosir':
                        if ($nextStep === 'Menunggu Diterima Dosir') {
                            $newStatusId = 8;
                        } elseif ($nextStep === 'Menunggu Scan dan Arsip') {
                            $newStatusId = 9;
                        } elseif ($nextStep === 'Menunggu Penyimpanan Berkas Fisik') {
                            $newStatusId = 10;
                        }
                        break;

                    case 'Korespondensi':
                        if ($nextStep === 'Menunggu Penyimpanan Berkas Fisik') {
                            $newStatusId = 10;
                        }
                        break;
                }

                // Skip if no valid transition
                if (!$newStatusId) {
                    continue;
                }

                // Update main berkas status
                $klaim->update([
                    'status_berkas_id' => $newStatusId
                ]);

                // Create history
                RiwayatBerkasKlaim::create([
                    'berkas_klaim_id'  => $klaim->id,
                    'status_berkas_id' => $newStatusId,
                    'user_id'          => $user->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Selected berkas successfully accepted.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to accept berkas.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function rejectBerkas(Request $request)
    {
        $ids = $request->ids;

        if (!is_array($ids) || count($ids) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No items selected.',
            ], 400);
        }

        $user     = Auth::user();
        $roleName = $user->role->name;

        DB::beginTransaction();

        try {
            $berkasList = BerkasKlaim::with('statusBerkas')
                ->whereIn('id', $ids)
                ->get();

            foreach ($berkasList as $klaim) {
                $newStatusId  = null;

                switch ($roleName) {

                    case 'Proses':
                        $newStatusId = 3;
                        break;

                    case 'Kepala Bidang':
                        $newStatusId = 6;
                        break;
                }

                // Skip if no valid transition
                if (!$newStatusId) {
                    continue;
                }

                // Update main berkas status
                $klaim->update([
                    'status_berkas_id' => $newStatusId
                ]);

                // Create history
                RiwayatBerkasKlaim::create([
                    'berkas_klaim_id'  => $klaim->id,
                    'status_berkas_id' => $newStatusId,
                    'user_id'          => $user->id,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Selected berkas successfully rejected.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject berkas.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
