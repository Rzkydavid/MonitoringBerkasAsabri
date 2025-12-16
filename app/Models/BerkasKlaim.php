<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasKlaim extends Model
{
    use HasFactory;

    protected $table = 'berkas_klaim';

    protected $fillable = [
        'nama_peserta',
        'nomor_identitas',
        'jenis_klaim_id',
        'tgl_berkas_diterima_cso',
        'status_terima_klaim_masuk',
        'status_terima_klaim_masuk_lainnya',

        'kelengkapan_persyaratan',
        'copy_seluruh_dokumen_terbaca_jelas',
        'keaslian_akta_kematian',
        'flagging_kredit',
        'nomor_wa_pengaju',
        'nomor_rekening_pengaju',
        'perlu_konfirmasi_ulang',

        'catatan_konfirmasi',
        'selesai_konfirmasi',

        'created_by',
        'status_berkas_id',
    ];

    // ====== RELATIONS ======

    public function jenisKlaim()
    {
        return $this->belongsTo(JenisKlaim::class, 'jenis_klaim_id');
    }

    public function statusBerkas()
    {
        return $this->belongsTo(StatusBerkas::class, 'status_berkas_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function histories()
    {
        return $this->hasMany(RiwayatBerkasKlaim::class, 'berkas_klaim_id');
    }

    // Optional: enum helper arrays (buat dropdown)
    public static $statusTerimaOptions = [
        'Datang Langsung',
        'ASABRI Link',
        'Klaim Online',
        'Email',
        'Lainnya',
    ];
    public static $kelengkapanOptions = ['Lengkap', 'Tidak Lengkap'];
    public static $yaTidakOptions = ['Ya', 'Tidak'];
    public static $validOptions = ['Valid', 'Tidak Valid'];
    public static $tampilOptions = ['Tercantum', 'Tidak Tercantum'];
    public static $aktifOptions = ['Aktif', 'Tidak Aktif'];
}
