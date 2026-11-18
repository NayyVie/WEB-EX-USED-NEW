<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    /**
     * Menampilkan halaman verifikasi lokasi
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $authUser = Auth::user();

        $user = [
            'employee_id' => $authUser->employee_id,
            'name' => $authUser->name,
            'email' => $authUser->email,
            'unit' => $authUser->unit,
            'role' => $authUser->role,
        ];

        return view('auth.verify-location', compact('user'));
    }

    /**
     * Memproses lokasi pengguna
     */
    public function verify(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Koordinat kantor/unit
        // $officeLatitude = -7.376830912097001;
        // $officeLongitude = 112.738121641038;

        // Koordinat Kos 
        $officeLatitude = -7.315719533002872;
        $officeLongitude = 112.72977874470426;

        // Koordinat Kampus
        // $officeLatitude = -7.310953294882996;
        // $officeLongitude = 112.72886074461584;

        // Koordinat pengguna
        $userLatitude = (float) $request->latitude;
        $userLongitude = (float) $request->longitude;

        // Hitung jarak menggunakan rumus Haversine
        $earthRadius = 6371000;

        $lat1 = deg2rad($officeLatitude);
        $lat2 = deg2rad($userLatitude);

        $deltaLat = deg2rad(
            $userLatitude - $officeLatitude
        );

        $deltaLon = deg2rad(
            $userLongitude - $officeLongitude
        );

        $a =
            sin($deltaLat / 2) ** 2 +
            cos($lat1) *
            cos($lat2) *
            sin($deltaLon / 2) ** 2;

        $c = 2 * atan2(
            sqrt($a),
            sqrt(1 - $a)
        );

        $distance = $earthRadius * $c;

        // Radius yang diizinkan
        $allowedRadius = 500;

        // Lokasi di luar area
        if ($distance > $allowedRadius) {
            return back()->with(
                'location_error',
                'Akses lokasi ditolak. Anda berada di luar area unit. Jarak Anda sekitar ' .
                round($distance) .
                ' meter dari lokasi unit.'
            );
        }

        // Lokasi valid
        session([
            'location_verified' => true,
            'latitude' => $userLatitude,
            'longitude' => $userLongitude,
            'location_distance' => round($distance),
        ]);

        // Arahkan sesuai role pengguna dari database
        $role = strtolower(trim(Auth::user()->role ?? ''));

        if ($role === 'admin') {
            return redirect()->route('dashboardAdmin');
        }

        if ($role === 'worker') {
            return redirect()->route('dashboardEmployee');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Role akun tidak dikenali. Silakan hubungi administrator.',
        ]);
    }
}
