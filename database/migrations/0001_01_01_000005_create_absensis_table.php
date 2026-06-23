<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->nullable()->constrained('lokasis')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('jam')->nullable();
            $table->enum('status', ['standby', 'progress']);
            $table->string('lokasi')->nullable();
            $table->text('uraian')->nullable();
            $table->json('checklist_apd')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
