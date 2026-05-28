<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $fillable = [
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
        'polygon',
    ];

    protected $casts = [
        'polygon' => 'array',
    ];

    /**
     * Cek apakah sebuah titik berada di dalam polygon (Ray Casting algorithm).
     */
    public function containsPoint(float $lat, float $lng): bool
    {
        $polygon = $this->polygon;

        if (empty($polygon) || count($polygon) < 3) {
            // Fallback ke radius jika polygon belum diset
            return $this->containsPointByRadius($lat, $lng);
        }

        $n = count($polygon);
        $inside = false;

        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $polygon[$i][0]; // lat
            $yi = $polygon[$i][1]; // lng
            $xj = $polygon[$j][0];
            $yj = $polygon[$j][1];

            $intersect = (($yi > $lng) !== ($yj > $lng))
                && ($lat < ($xj - $xi) * ($lng - $yi) / ($yj - $yi) + $xi);

            if ($intersect) {
                $inside = !$inside;
            }
        }

        return $inside;
    }

    /**
     * Fallback: cek berdasarkan radius (Haversine).
     */
    public function containsPointByRadius(float $lat, float $lng): bool
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($this->latitude - $lat);
        $dLon = deg2rad($this->longitude - $lng);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat)) * cos(deg2rad($this->latitude)) * sin($dLon / 2) ** 2;

        $distance = $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));

        return $distance <= (float) $this->radius;
    }
}
