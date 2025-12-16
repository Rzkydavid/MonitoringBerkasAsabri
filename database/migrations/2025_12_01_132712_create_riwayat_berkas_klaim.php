<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_berkas_klaim', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('berkas_klaim_id');
            $table->unsignedBigInteger('status_berkas_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();

            $table->foreign('berkas_klaim_id')->references('id')->on('berkas_klaim')->onDelete('cascade');
            $table->foreign('status_berkas_id')->references('id')->on('status_berkas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_berkas_klaim');
    }
};
