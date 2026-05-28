<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PetugasController extends Controller
{
    /**
     * Tampilkan semua data petugas
     */
    public function index()
    {
        $petugas = User::latest()->get();

        return view('petugas.index', compact('petugas'));
    }

    /**
     * Tampilkan form tambah petugas
     */
    public function create()
    {
        return view('petugas.create');
    }

    /**
     * Simpan data petugas
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => ['required', Rule::in(['petugas', 'supervisor'])],
        ]);

        User::create([

            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,

        ]);

        return redirect('/petugas')
            ->with('success', 'Data petugas berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit
     */
    public function edit($id)
    {
        $petugas = User::findOrFail($id);

        return view('petugas.edit', compact('petugas'));
    }

    /**
     * Update data petugas
     */
    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($petugas->id)],
            'role' => ['required', Rule::in(['petugas', 'supervisor'])],
        ]);

        $petugas->update([

            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,

        ]);

        return redirect('/petugas')
            ->with('success', 'Data petugas berhasil diupdate');
    }

    /**
     * Hapus data petugas
     */
    public function destroy($id)
    {
        $petugas = User::findOrFail($id);

        $petugas->delete();

        return redirect('/petugas')
            ->with('success', 'Data petugas berhasil dihapus');
    }
}
