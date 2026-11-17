<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifikasi = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        // Jumlah notifikasi yang belum dibaca
        $totalBelumDibaca = Notification::where('user_id', Auth::id())
            ->whereNull('dibaca_pada')
            ->count();

        // Jumlah notifikasi yang sudah dibaca
        $totalSudahDibaca = Notification::where('user_id', Auth::id())
            ->whereNotNull('dibaca_pada')
            ->count();

        return view('employee.notifikasi-employee', compact(
            'notifikasi',
            'totalBelumDibaca',
            'totalSudahDibaca'
        ));
    }

    public function tandaiDibaca($id)
    {
        $notifikasi = Notification::where('user_id', Auth::id())
            ->findOrFail($id);

        // Isi waktu baca jika belum dibaca
        if (is_null($notifikasi->dibaca_pada)) {
            $notifikasi->update([
                'dibaca_pada' => now('Asia/Jakarta'),
            ]);
        }

        return back()->with(
            'success',
            'Notifikasi ditandai sudah dibaca.'
        );
    }

    public function tandaiSemuaDibaca()
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('dibaca_pada')
            ->update([
                'dibaca_pada' => now('Asia/Jakarta'),
            ]);

        return back()->with(
            'success',
            'Semua notifikasi berhasil ditandai sudah dibaca.'
        );
    }
}
