<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Get recent notifications for the current user (JSON API).
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = Notifikasi::where('user_id', Auth::id())
            ->with('absensi:id')
            ->orderByDesc('created_at')
            ->take(20)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'judul'      => $n->judul,
                'pesan'      => $n->pesan,
                'read'       => !is_null($n->read_at),
                'absensi_id' => $n->absensi_id,
                'waktu'      => $n->created_at->diffForHumans(),
                'created_at' => $n->created_at->toISOString(),
            ]);

        $unreadCount = Notifikasi::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function read(Notifikasi $notifikasi): JsonResponse
    {
        if ($notifikasi->user_id !== Auth::id()) {
            abort(403);
        }

        $notifikasi->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read for the current user.
     */
    public function readAll(): JsonResponse
    {
        Notifikasi::where('user_id', Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
