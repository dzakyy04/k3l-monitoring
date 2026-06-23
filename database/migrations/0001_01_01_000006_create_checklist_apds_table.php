<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_apds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensis')->cascadeOnDelete();
            $table->boolean('helm')->default(false);
            $table->boolean('sepatu')->default(false);
            $table->boolean('sarung_tangan')->default(false);
            $table->boolean('kacamata')->default(false);
            $table->boolean('face_shield')->default(false);
            $table->boolean('tali_pengaman')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_apds');
    }
};
