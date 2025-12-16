<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berkas_klaim', function (Blueprint $table) {
            $table->id();

            $table->string('nama_peserta');
            $table->string('nomor_identitas');

            $table->unsignedBigInteger('jenis_klaim_id');

            $table->date('tgl_berkas_diterima_cso');

            $table->enum('status_terima_klaim_masuk', [
                'Datang Langsung',
                'ASABRI Link',
                'Klaim Online',
                'Email',
                'Lainnya'
            ]);

            $table->string('status_terima_klaim_masuk_lainnya')->nullable();

            $table->enum('kelengkapan_persyaratan', ['Lengkap', 'Tidak Lengkap'])->default('Tidak Lengkap');
            $table->enum('copy_seluruh_dokumen_terbaca_jelas', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('keaslian_akta_kematian', ['Valid', 'Tidak Valid'])->default('Tidak Valid');
            $table->enum('flagging_kredit', ['Ya', 'Tidak'])->default('Tidak');
            $table->enum('nomor_wa_pengaju', ['Tercantum', 'Tidak Tercantum'])->default('Tidak Tercantum');
            $table->enum('nomor_rekening_pengaju', ['Aktif', 'Tidak Aktif'])->default('Tidak Aktif');
            $table->enum('perlu_konfirmasi_ulang', ['Ya', 'Tidak'])->default('Tidak');

            $table->text('catatan_konfirmasi')->nullable();
            $table->date('selesai_konfirmasi')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('status_berkas_id')->nullable();

            $table->timestamps();

            $table->foreign('jenis_klaim_id')->references('id')->on('jenis_klaim');
            $table->foreign('status_berkas_id')->references('id')->on('status_berkas');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_klaim');
    }
};
