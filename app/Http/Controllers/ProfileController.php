<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'supervisor') {
            $stats = [
                ['label' => 'Petugas',  'value' => User::where('role', 'petugas')->count(), 'icon' => 'users'],
                ['label' => 'Lokasi',   'value' => Lokasi::count(),                          'icon' => 'map-pin'],
            ];
        } else {
            $stats = [
                ['label' => 'Absensi',   'value' => Absensi::where('user_id', $user->id)->count(),                                                                          'icon' => 'clipboard-check'],
                ['label' => 'Bulan Ini', 'value' => Absensi::where('user_id', $user->id)->whereYear('tanggal', now()->year)->whereMonth('tanggal', now()->month)->count(), 'icon' => 'calendar'],
            ];
        }

        return view('profile.edit', [
            'user' => $user,
            'stats' => $stats,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
