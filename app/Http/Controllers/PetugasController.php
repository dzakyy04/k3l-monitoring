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

        return redirect()
            ->route('petugas.index')
            ->with('success', 'Data petugas berhasil ditambahkan');
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
            'password' => ['nullable', 'min:6'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $petugas->update($data);

        return redirect()
            ->route('petugas.index')
            ->with('success', 'Data petugas berhasil diupdate');
    }

    /**
     * Hapus data petugas
     */
    public function destroy($id)
    {
        $petugas = User::findOrFail($id);

        $petugas->delete();

        return redirect()
            ->route('petugas.index')
            ->with('success', 'Data petugas berhasil dihapus');
    }
}
