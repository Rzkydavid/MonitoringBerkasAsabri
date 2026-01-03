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
        Schema::table('berkas_klaim', function (Blueprint $table) {
            $table->smallInteger('no_loket')
                ->nullable()
                ->after('selesai_konfirmasi');

            $table->string('nama_pengaju')
                ->nullable()
                ->after('nomor_identitas');
        });
    }

    public function down(): void
    {
        Schema::table('berkas_klaim', function (Blueprint $table) {
            $table->dropColumn([
                'no_loket',
                'nama_pengaju',
            ]);
        });
    }
};
