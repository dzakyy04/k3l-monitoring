<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'user_id',
        'lokasi_id',
        'tanggal',
        'jam',
        'status',
        'lokasi',
        'uraian',
        'checklist_apd',
        'latitude',
        'longitude',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'checklist_apd' => 'array',
    ];

    /**
     * Relasi ke user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke lokasi
     */
    public function lokasiData(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}