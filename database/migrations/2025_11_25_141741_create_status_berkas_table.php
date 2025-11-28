<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_berkas', function (Blueprint $table) {
            $table->id();
            $table->string('status_terkini');
            $table->string('next_step');
            $table->boolean('status')->default(1); // or tinyint(1)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_berkas');
    }
};
