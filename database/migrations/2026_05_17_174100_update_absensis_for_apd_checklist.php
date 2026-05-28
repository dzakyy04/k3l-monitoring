<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('absensis')) {
            return;
        }

        Schema::table('absensis', function (Blueprint $table) {
            if (! Schema::hasColumn('absensis', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('absensis', 'jam')) {
                $table->time('jam')->nullable()->after('tanggal');
            }

            if (! Schema::hasColumn('absensis', 'lokasi')) {
                $table->string('lokasi')->nullable()->after('status');
            }

            if (! Schema::hasColumn('absensis', 'uraian')) {
                $table->text('uraian')->nullable()->after('lokasi');
            }

            if (! Schema::hasColumn('absensis', 'checklist_apd')) {
                $table->json('checklist_apd')->nullable()->after('uraian');
            }
        });

        if (Schema::hasColumn('absensis', 'petugas_id') && Schema::hasColumn('absensis', 'user_id')) {
            DB::statement('UPDATE absensis SET user_id = petugas_id WHERE user_id IS NULL');
        }

        if (Schema::hasColumn('absensis', 'uraian_kegiatan') && Schema::hasColumn('absensis', 'uraian')) {
            DB::statement('UPDATE absensis SET uraian = uraian_kegiatan WHERE uraian IS NULL');
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('absensis')) {
            return;
        }

        Schema::table('absensis', function (Blueprint $table) {
            if (Schema::hasColumn('absensis', 'checklist_apd')) {
                $table->dropColumn('checklist_apd');
            }

            if (Schema::hasColumn('absensis', 'lokasi')) {
                $table->dropColumn('lokasi');
            }

            if (Schema::hasColumn('absensis', 'uraian')) {
                $table->dropColumn('uraian');
            }
        });
    }
};
