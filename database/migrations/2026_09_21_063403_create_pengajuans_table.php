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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('jenis_pengajuan_id')->constrained('jenis_pengajuans')->onDelete('cascade');
            $table->foreignId('pengajaran_id')->constrained('pengajarans')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('keterangan');
            $table->enum('status', ['diajukan', 'diperiksa', 'dikembalikan','disetujui','ditolak'])->default('diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
