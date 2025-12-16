<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBerkasKlaim extends Model
{
    use HasFactory;

    protected $table = 'riwayat_berkas_klaim';

    protected $fillable = [
        'berkas_klaim_id',
        'status_berkas_id',
        'user_id',
    ];

    // ====== RELATIONS ======

    public function berkasKlaim()
    {
        return $this->belongsTo(BerkasKlaim::class, 'berkas_klaim_id');
    }

    public function statusBerkas()
    {
        return $this->belongsTo(StatusBerkas::class, 'status_berkas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
