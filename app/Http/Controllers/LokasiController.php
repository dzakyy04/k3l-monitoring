<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokasi = Lokasi::latest()->get();

        return view('lokasi.index', compact('lokasi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lokasi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|integer|min:1',
            'polygon' => 'required|json',
        ]);

        $data = $request->only(['nama_lokasi', 'latitude', 'longitude']);
        $data['radius'] = $request->input('radius') ?: 100;
        $data['polygon'] = json_decode($request->polygon, true);

        Lokasi::create($data);

        return redirect('/lokasi')
            ->with('success', 'Lokasi berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lokasi $lokasi)
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama_lokasi' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|integer|min:1',
            'polygon' => 'required|json',
        ]);

        $data = $request->only(['nama_lokasi', 'latitude', 'longitude']);
        $data['radius'] = $request->input('radius') ?: 100;
        $data['polygon'] = json_decode($request->polygon, true);

        $lokasi->update($data);

        return redirect('/lokasi')
            ->with('success', 'Lokasi berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();

        return back()->with(
            'success',
            'Lokasi berhasil dihapus'
        );
    }
}
