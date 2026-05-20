<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk membuat tabel tasks.
 * Menyimpan semua tugas dengan atribut lengkap.
 */
return new class extends Migration
{
    /**
     * Jalankan migrasi - buat tabel tasks.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');                        
            $table->text('description')->nullable();       
            $table->boolean('is_completed')->default(false);
            $table->timestamps();                          
        });
    }

    /**
     * Batalkan migrasi - hapus tabel tasks.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};