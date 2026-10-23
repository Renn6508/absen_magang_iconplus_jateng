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
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->date('tanggal_absen');
        $table->enum('jenis_absen', ['masuk', 'pulang']);
        $table->string('status_peserta');          // siswa/siswi/mahasiswa/mahasiswi
        $table->string('nama');
        $table->string('asal_instansi');
        $table->string('divisi');
        $table->string('foto');                    // path di storage/public
        $table->boolean('synced_sheet')->default(false);
        $table->timestamps();                      // created_at = jam pengiriman

        $table->index(['tanggal_absen', 'divisi']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
