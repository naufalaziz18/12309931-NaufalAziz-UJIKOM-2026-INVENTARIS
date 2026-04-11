<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->string('borrower_name'); // Nama peminjam
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Barang yang dipinjam
            $table->foreignId('user_id')->constrained(); // Operator yang melayani
            $table->integer('quantity'); // Jumlah barang
            $table->date('return_date'); // Estimasi kembali
            $table->text('description')->nullable(); // Keterangan per barang (Note)
            $table->enum('status', ['dipinjam', 'dikembalikan'])->default('dipinjam');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrows');
    }
};
