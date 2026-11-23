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
        Schema::create('peminjamans', function (Blueprint $table) {
        $table->id();
        $table->string('kode_transaksi', 25)->unique();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
        $table->integer('jumlah');
        $table->date('tanggal_pinjam');
        $table->date('tanggal_kembali_rencana');
        $table->date('tanggal_kembali_aktual')->nullable();
        $table->enum('status_transaksi', ['Pending', 'Disetujui', 'Ditolak', 'Dikembalikan'])->default('Pending');
        $table->text('catatan_laboran')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('peminjamans');
    }
};
