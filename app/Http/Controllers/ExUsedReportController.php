<?php

namespace App\Http\Controllers;

use App\Models\ExUsedReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ExUsedReportController extends Controller
{
    public function step1()
    {
        $user = Auth::user();

        return view('employee.input-barang', [
            'user' => $user,
            'data' => session('ex_used_step1', []),
        ]);
    }

    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            'tanggal_laporan' => ['required', 'date'],
            'bidang' => ['required', 'in:mekanik,listrik,controlling_instrumen,gudang'],
        ]);

        $validated['status_lokasi'] = Auth::user()->unit;

        session(['ex_used_step1' => $validated]);

        return redirect()->route('inputExBarang.step2');
    }

    public function step2()
    {
        if (!session()->has('ex_used_step1')) {
            return redirect()->route('inputExBarang');
        }

        return view('employee.input-step2', [
            'data' => session('ex_used_step2', []),
        ]);
    }

    public function saveStep2(Request $request)
    {
        if (!session()->has('ex_used_step1')) {
            return redirect()->route('inputExBarang');
        }

        $validated = $request->validate([
            'nomor_wo' => ['required', 'string', 'max:100'],
            'nama_pekerjaan' => ['required', 'string', 'max:255'],
            'item_number' => ['required', 'string', 'max:100'],
            'nama_barang' => ['required', 'string', 'max:255'],
            'jenis_pengembalian' => [
                'required', 'in:barang_lebihan,barang_pasca_pakai',
            ],
            'kondisi_barang' => ['required', 'in:investasi,operasi'],
            'material_base' => [
                'required', 'in:besi/baja,aluminium,tembaga,plastik,campuran',
            ],
            'lokasi_gudang' => ['required', 'string', 'max:255'],
            'jumlah_barang' => ['required', 'integer', 'min:1'],
            'satuan' => ['required', 'in:pcs,unit,meter,set,buah'],
            'estimasi' => ['required', 'numeric', 'min:0'],
        ]);

        session(['ex_used_step2' => $validated]);

        return redirect()->route('inputExBarang.step3');
    }

    public function step3()
    {
        if (!session()->has('ex_used_step1')) {
            return redirect()->route('inputExBarang');
        }

        if (!session()->has('ex_used_step2')) {
            return redirect()->route('inputExBarang.step2');
        }

        // mencegah satu formulir yang sama diproses berulang kali.
        if (!session()->has('ex_used_submission_token')) {
    session([
        'ex_used_submission_token' => (string) \Illuminate\Support\Str::uuid(),
    ]);
}

        return view('employee.input-step3', [
            'data' => session('ex_used_step3', []),
            'submissionToken' => session('ex_used_submission_token'),
        ]);
    }

    public function saveStep3(Request $request)
    {
        if (!session()->has('ex_used_step1') ||
            !session()->has('ex_used_step2')) {
            return redirect()->route('inputExBarang');
        }

        $validated = $request->validate([
            'status_kondisi' => [
                'required', 'in:rusak,repairable,kehilangan_fungsi',
            ],
            'foto_bukti' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'tanda_tangan' => ['required', 'string', 'max:1000000'],
            'pernyataan' => ['accepted'],
            'submission_token' => ['required', 'uuid'],
        ]);

        $token = $validated['submission_token'];

if (
    !session()->has('ex_used_submission_token') ||
    !hash_equals(
        session('ex_used_submission_token'),
        $token
    )
) {
    return redirect()
        ->route('inputExBarang')
        ->with('error', 'Formulir ini sudah diproses atau kedaluwarsa. Silakan periksa riwayat laporan.');
}

        $step1 = session('ex_used_step1');
        $step2 = session('ex_used_step2');

        $fotoPath = $request->file('foto_bukti')
            ->store('ex-used/bukti', 'public');

        $tanggalLaporan = Carbon::parse($step1['tanggal_laporan'])
            ->setTimeFrom(now('Asia/Jakarta'));

       try {
    DB::transaction(function () use (
        $step1,
        $step2,
        $validated,
        $fotoPath,
        $tanggalLaporan,
        &$laporan
    ) {
        // Simpan laporan
        $laporan = ExUsedReport::create(array_merge(
            $step1,
            $step2,
            [
                'user_id' => Auth::id(),
                'created_by' => Auth::id(),
    'sumber_input' => 'worker',
                'status_kondisi' => $validated['status_kondisi'],
                'foto_bukti' => $fotoPath,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanda_tangan' => $validated['tanda_tangan'],
                'status_laporan' => 'Menunggu Verifikasi',
                'tanggal_laporan' => $tanggalLaporan,
            ]
        ));

        // Simpan notifikasi dalam transaksi yang sama
        Notification::create([
            'user_id' => Auth::id(),
            'ex_used_report_id' => $laporan->id,
            'judul' => 'Laporan berhasil dibuat',
            'pesan' => 'Laporan Ex Used dengan ID RPT-'
                . str_pad($laporan->id, 6, '0', STR_PAD_LEFT)
                . ' berhasil dibuat dan menunggu proses verifikasi.',
            'jenis' => 'dibuat',
            'dibaca_pada' => null,
        ]);
    });
    
} catch (\Throwable $e) {
    // Hapus foto jika penyimpanan laporan/notifikasi gagal
    Storage::disk('public')->delete($fotoPath);

    throw $e;
}
        // Hapus data sementara dari session
        session()->forget([
            'ex_used_step1',
            'ex_used_step2',
            'ex_used_step3',
            'ex_used_submission_token',
        ]);

        return redirect()
            ->route('inputExBarang')
            ->with('success', 'Laporan Ex Barang berhasil disimpan.');
    }

    public function riwayat()
{
    $laporan = ExUsedReport::where('user_id', Auth::id())
        ->orderByDesc('created_at')
        ->get();

    return view('employee.riwayat-employee', compact('laporan'));
}

public function detail($id)
{
    $laporan = ExUsedReport::where('id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('employee.detail-riwayat', compact('laporan'));
}

public function laporan()
{
    $laporan = ExUsedReport::where('user_id', Auth::id())
        ->where('status_laporan', 'Disetujui')
        ->orderByDesc('tanggal_laporan')
        ->get();

    $totalLaporan = $laporan->count();

    $totalInvestasi = $laporan
        ->where('kondisi_barang', 'investasi')
        ->count();

    $totalOperasi = $laporan
        ->where('kondisi_barang', 'operasi')
        ->count();

    $totalDisetujui = $totalLaporan;

    return view('employee.laporan-employee', compact(
        'laporan',
        'totalLaporan',
        'totalInvestasi',
        'totalOperasi',
        'totalDisetujui'
    ));
}

public function dashboard()
{
    $query = ExUsedReport::where('user_id', Auth::id());

    $user = Auth::user();

    // Sapaan berdasarkan waktu WIB
    $jam = now('Asia/Jakarta')->hour;

    if ($jam >= 4 && $jam < 11) {
        $sapaan = 'Selamat Pagi';
    } elseif ($jam >= 11 && $jam < 15) {
        $sapaan = 'Selamat Siang';
    } elseif ($jam >= 15 && $jam < 18) {
        $sapaan = 'Selamat Sore';
    } else {
        $sapaan = 'Selamat Malam';
    }

    // Data laporan milik user yang sedang login
    $laporanUser = ExUsedReport::where('user_id', $user->id);

    // Semua laporan yang pernah disubmit oleh Worker
    $totalLaporan = (clone $query)->count();

    // Total berdasarkan kategori barang
    $totalInvestasi = (clone $query)
        ->where('kondisi_barang', 'investasi')
        ->count();

    $totalOperasi = (clone $query)
        ->where('kondisi_barang', 'operasi')
        ->count();

    // Laporan yang masih menunggu verifikasi
    $totalTertunda = (clone $query)
        ->where('status_laporan', 'Menunggu Verifikasi')
        ->count();

    // Lima laporan terbaru, apa pun statusnya
    $aktivitasTerbaru = (clone $query)
        ->orderByDesc('created_at')
        ->take(5)
        ->get();
    
    $user = Auth::user();

    $namaUser = $user->name ?? 'Pengguna';
    $unitUser = $user->unit ?? 'Unit belum diatur';

    return view('employee.dashboard-employee', compact(
        'totalLaporan',
        'totalInvestasi',
        'totalOperasi',
        'totalTertunda',
        'aktivitasTerbaru',
        'user',
        'unitUser',
        'sapaan'
    ));
}

public function adminIndex()
{
    $laporan = ExUsedReport::with(['user', 'pembuat'])
        ->orderByRaw("
            CASE
                WHEN status_laporan = 'Menunggu Verifikasi' THEN 0
                ELSE 1
            END
        ")
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

    // Menghitung seluruh laporan, bukan hanya halaman yang sedang dibuka
    $totalLaporan = ExUsedReport::count();

    $totalMenunggu = ExUsedReport::where(
        'status_laporan',
        'Menunggu Verifikasi'
    )->count();

    $totalDisetujui = ExUsedReport::where(
        'status_laporan',
        'Disetujui'
    )->count();

    $totalDitolak = ExUsedReport::where(
        'status_laporan',
        'Ditolak'
    )->count();

    $daftarWorker = User::where('role', 'worker')
        ->orderBy('name')
        ->get();

    return view('admin.wo-admin', compact(
        'laporan',
        'totalLaporan',
        'totalMenunggu',
        'totalDisetujui',
        'totalDitolak',
        'daftarWorker'
    ));
}

public function setujuiLaporan(Request $request, $id)
{
    $validated = $request->validate([
        'tanda_tangan_admin' => [
            'required',
            'string',
            'max:1000000',
        ],
    ], [
        'tanda_tangan_admin.required' => 'Tanda tangan Admin wajib diisi.',
    ]);

    $signaturePath = null;

    try {
        // Pastikan format tanda tangan berupa Base64 PNG
        $signature = $validated['tanda_tangan_admin'];

        if (!preg_match(
            '/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/',
            $signature,
            $matches
        )) {
            return back()->withErrors([
                'tanda_tangan_admin' => 'Format tanda tangan tidak valid.',
            ]);
        }

        $imageData = base64_decode($matches[1], true);

        if ($imageData === false || strlen($imageData) > 750000) {
            return back()->withErrors([
                'tanda_tangan_admin' => 'Ukuran tanda tangan tidak valid.',
            ]);
        }

        // Pastikan file benar-benar gambar PNG
        $imageInfo = getimagesizefromstring($imageData);

        if (
            $imageInfo === false ||
            $imageInfo['mime'] !== 'image/png'
        ) {
            return back()->withErrors([
                'tanda_tangan_admin' => 'File tanda tangan harus berupa PNG.',
            ]);
        }

        // Simpan gambar tanda tangan ke storage
        $signaturePath = 'tanda-tangan-admin/' .
            'admin_' . Auth::id() .
            '_' . $id . '_' . uniqid() . '.png';

        Storage::disk('public')->put(
            $signaturePath,
            $imageData
        );

        DB::transaction(function () use (
            $id,
            $signaturePath
        ) {
            $laporan = ExUsedReport::where('id', $id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($laporan->status_laporan !== 'Menunggu Verifikasi') {
                abort(422, 'Laporan ini sudah diverifikasi.');
            }

            $laporan->update([
                'status_laporan' => 'Disetujui',
                'alasan_penolakan' => null,
                'tanda_tangan_admin' => $signaturePath,
                'admin_verifikator_id' => Auth::id(),
                'tanggal_verifikasi' => now(),
            ]);

            Notification::create([
                'user_id' => $laporan->user_id,
                'ex_used_report_id' => $laporan->id,
                'judul' => 'Laporan Ex Barang Disetujui',
                'pesan' => 'Laporan Ex Barang dengan ID RPT-' .
                    str_pad($laporan->id, 6, '0', STR_PAD_LEFT) .
                    ' telah disetujui oleh Admin Gudang.',
                'jenis' => 'disetujui',
                'dibaca_pada' => null,
            ]);
        });

    } catch (\Throwable $e) {
        // Hapus file jika proses persetujuan gagal
        if ($signaturePath) {
            Storage::disk('public')->delete($signaturePath);
        }

        throw $e;
    }

    return redirect()
        ->route('pengeluaranBarang')
        ->with('success', 'Laporan Ex Barang berhasil disetujui.');
}

public function tolakLaporan(Request $request, $id)
{
    $validated = $request->validate([
        'alasan_penolakan' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use ($id, $validated) {
        $laporan = ExUsedReport::where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($laporan->status_laporan !== 'Menunggu Verifikasi') {
            abort(422, 'Laporan ini sudah diverifikasi.');
        }

        $laporan->update([
            'status_laporan' => 'Ditolak',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        Notification::create([
            'user_id' => $laporan->user_id,
            'ex_used_report_id' => $laporan->id,
            'judul' => 'Laporan Ex Barang Ditolak',
            'pesan' => 'Laporan Ex Barang dengan ID RPT-'
                . str_pad($laporan->id, 6, '0', STR_PAD_LEFT)
                . ' ditolak oleh Admin Gudang. Alasan: '
                . $validated['alasan_penolakan'],
            'jenis' => 'ditolak',
            'dibaca_pada' => null,
        ]);
    });

    return redirect()
        ->route('pengeluaranBarang')
        ->with('success', 'Laporan Ex Barang berhasil ditolak.');
}

public function storeAdmin(Request $request)
{
    $validated = $request->validate([
        'user_id' => ['required', 'exists:users,id'],
        'tanggal_laporan' => ['required', 'date'],
        'bidang' => [
            'required',
            'in:mekanik,listrik,controlling_instrumen,gudang'
        ],

        'nomor_wo' => ['required', 'string', 'max:100'],
        'nama_pekerjaan' => ['required', 'string', 'max:255'],
        'item_number' => ['required', 'string', 'max:100'],
        'nama_barang' => ['required', 'string', 'max:255'],
        'jenis_pengembalian' => [
            'required',
            'in:barang_lebihan,barang_pasca_pakai'
        ],
        'kondisi_barang' => ['required', 'in:investasi,operasi'],
        'material_base' => [
            'required',
            'in:besi/baja,aluminium,tembaga,plastik,campuran'
        ],
        'lokasi_gudang' => ['required', 'string', 'max:255'],
        'jumlah_barang' => ['required', 'integer', 'min:1'],
        'satuan' => ['required', 'in:pcs,unit,meter,set,buah'],
        'estimasi' => ['required', 'numeric', 'min:0'],

        'status_kondisi' => [
            'required',
            'in:rusak,repairable,kehilangan_fungsi'
        ],
        'foto_bukti' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png',
            'max:5120'
        ],
        'deskripsi' => ['nullable', 'string', 'max:5000'],
        'tanda_tangan' => ['required', 'string', 'max:1000000'],
    ]);

    // Ambil akun Worker yang dipilih Admin
    $worker = User::findOrFail($validated['user_id']);

    // Simpan foto bukti
    $fotoPath = $request->file('foto_bukti')
        ->store('ex-used/bukti', 'public');

    try {
        DB::transaction(function () use (
            $validated,
            $worker,
            $fotoPath
        ) {
            $laporan = ExUsedReport::create([
                'user_id' => $worker->id,
                'created_by' => Auth::id(),
                'sumber_input' => 'admin',

                'tanggal_laporan' => $validated['tanggal_laporan'],
                'status_lokasi' => $worker->unit,
                'bidang' => $validated['bidang'],

                'nomor_wo' => $validated['nomor_wo'],
                'nama_pekerjaan' => $validated['nama_pekerjaan'],
                'item_number' => $validated['item_number'],
                'nama_barang' => $validated['nama_barang'],
                'jenis_pengembalian' => $validated['jenis_pengembalian'],
                'kondisi_barang' => $validated['kondisi_barang'],
                'material_base' => $validated['material_base'],
                'lokasi_gudang' => $validated['lokasi_gudang'],
                'jumlah_barang' => $validated['jumlah_barang'],
                'satuan' => $validated['satuan'],
                'estimasi' => $validated['estimasi'],

                'status_kondisi' => $validated['status_kondisi'],
                'foto_bukti' => $fotoPath,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanda_tangan' => $validated['tanda_tangan'],

                // Input Admin langsung disetujui
                'status_laporan' => 'Disetujui',
            ]);

            Notification::create([
                'user_id' => $worker->id,
                'ex_used_report_id' => $laporan->id,
                'judul' => 'Laporan Ex Used berhasil dicatat',
                'pesan' => 'Laporan Ex Used dengan ID RPT-'
                    . str_pad($laporan->id, 6, '0', STR_PAD_LEFT)
                    . ' telah diinput oleh Admin Gudang dan disetujui.',
                'jenis' => 'disetujui',
                'dibaca_pada' => null,
            ]);
        });
    } catch (\Throwable $e) {
        Storage::disk('public')->delete($fotoPath);
        throw $e;
    }

    return redirect()
        ->route('pengeluaranBarang')
        ->with('success', 'Laporan Ex Used berhasil diinput dan disetujui.');
}

public function detailAdmin($id)
{
    // Pastikan ID laporan memang tersedia
    ExUsedReport::findOrFail($id);

    // Ambil kumpulan laporan untuk tabel
    $laporan = ExUsedReport::with(['user', 'pembuat'])
        ->orderByRaw("
            CASE
                WHEN status_laporan = 'Menunggu Verifikasi' THEN 0
                ELSE 1
            END
        ")
        ->orderByDesc('created_at')
        ->paginate(10)
        ->withQueryString();

    // Data ringkasan
    $totalLaporan = ExUsedReport::count();

    $totalMenunggu = ExUsedReport::where(
        'status_laporan',
        'Menunggu Verifikasi'
    )->count();

    $totalDisetujui = ExUsedReport::where(
        'status_laporan',
        'Disetujui'
    )->count();

    $totalDitolak = ExUsedReport::where(
        'status_laporan',
        'Ditolak'
    )->count();

    $daftarWorker = User::where('role', 'worker')
        ->orderBy('name')
        ->get();

    return view('admin.wo-admin', compact(
        'laporan',
        'totalLaporan',
        'totalMenunggu',
        'totalDisetujui',
        'totalDitolak',
        'daftarWorker'
    ));
}

public function revisiLaporan(Request $request, $id)
{
    $validated = $request->validate([
        'alasan_penolakan' => [
            'required',
            'string',
            'max:2000',
        ],
    ]);

    DB::transaction(function () use ($id, $validated) {
        $laporan = ExUsedReport::where('id', $id)
            ->lockForUpdate()
            ->firstOrFail();

        if (
            $laporan->status_laporan !== 'Menunggu Verifikasi' ||
            $laporan->sumber_input !== 'worker'
        ) {
            abort(422, 'Laporan ini tidak dapat direvisi.');
        }

        $laporan->update([
            'status_laporan' => 'Perlu Revisi',
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        Notification::create([
            'user_id' => $laporan->user_id,
            'ex_used_report_id' => $laporan->id,
            'judul' => 'Laporan Ex Barang Memerlukan Revisi',
            'pesan' => 'Laporan Ex Barang dengan ID RPT-'
                . str_pad($laporan->id, 6, '0', STR_PAD_LEFT)
                . ' memerlukan perbaikan. Alasan: '
                . $validated['alasan_penolakan'],
            'jenis' => 'revisi',
            'dibaca_pada' => null,
        ]);
    });

    return redirect()
        ->route('pengeluaranBarang')
        ->with('success', 'Laporan berhasil dikembalikan untuk direvisi.');
}


public function editRevisi($id)
{
    $laporan = ExUsedReport::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('sumber_input', 'worker')
        ->where('status_laporan', 'Perlu Revisi')
        ->firstOrFail();

    return view('employee.edit-revisi', compact('laporan'));
}



public function updateRevisi(Request $request, $id)
{
    // Cari laporan milik Worker yang sedang login
    $laporan = ExUsedReport::where('id', $id)
        ->where('user_id', Auth::id())
        ->where('sumber_input', 'worker')
        ->where('status_laporan', 'Perlu Revisi')
        ->firstOrFail();

    // Validasi data
    $validated = $request->validate([
        'tanggal_laporan' => ['required', 'date'],
        'bidang' => [
            'required',
            'in:mekanik,listrik,controlling_instrumen,gudang'
        ],

        'nomor_wo' => ['required', 'string', 'max:100'],
        'nama_pekerjaan' => ['required', 'string', 'max:255'],
        'item_number' => ['required', 'string', 'max:100'],
        'nama_barang' => ['required', 'string', 'max:255'],

        'jenis_pengembalian' => [
            'required',
            'in:barang_lebihan,barang_pasca_pakai'
        ],
        'kondisi_barang' => ['required', 'in:investasi,operasi'],
        'material_base' => [
            'required',
            'in:besi/baja,aluminium,tembaga,plastik,campuran'
        ],
        'lokasi_gudang' => ['required', 'string', 'max:255'],
        'jumlah_barang' => ['required', 'integer', 'min:1'],
        'satuan' => ['required', 'in:pcs,unit,meter,set,buah'],
        'estimasi' => ['required', 'numeric', 'min:0'],

        'status_kondisi' => [
            'required',
            'in:rusak,repairable,kehilangan_fungsi'
        ],
        'foto_bukti' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png',
            'max:5120'
        ],
        'deskripsi' => ['nullable', 'string', 'max:5000'],
        'tanda_tangan' => ['required', 'string', 'max:1000000'],
        'pernyataan' => ['accepted'],
    ]);

    // Simpan lokasi foto lama sebelum data diperbarui
    $fotoLama = $laporan->foto_bukti;

    // Gunakan foto lama jika Worker tidak mengunggah foto baru
    $fotoBaru = $fotoLama;

    if ($request->hasFile('foto_bukti')) {
        $fotoBaru = $request->file('foto_bukti')
            ->store('ex-used/bukti', 'public');
    }

    try {
        DB::transaction(function () use (
            $laporan,
            $validated,
            $fotoBaru
        ) {
            $laporan->update([
                'tanggal_laporan' => $validated['tanggal_laporan'],
                'bidang' => $validated['bidang'],

                'nomor_wo' => $validated['nomor_wo'],
                'nama_pekerjaan' => $validated['nama_pekerjaan'],
                'item_number' => $validated['item_number'],
                'nama_barang' => $validated['nama_barang'],
                'jenis_pengembalian' => $validated['jenis_pengembalian'],
                'kondisi_barang' => $validated['kondisi_barang'],
                'material_base' => $validated['material_base'],
                'lokasi_gudang' => $validated['lokasi_gudang'],
                'jumlah_barang' => $validated['jumlah_barang'],
                'satuan' => $validated['satuan'],
                'estimasi' => $validated['estimasi'],

                'status_kondisi' => $validated['status_kondisi'],
                'foto_bukti' => $fotoBaru,
                'deskripsi' => $validated['deskripsi'] ?? null,
                'tanda_tangan' => $validated['tanda_tangan'],

                // Kembalikan laporan ke antrean verifikasi
                'status_laporan' => 'Menunggu Verifikasi',
                'alasan_penolakan' => null,
            ]);

            // Buat notifikasi untuk Worker
            Notification::create([
                'user_id' => Auth::id(),
                'ex_used_report_id' => $laporan->id,
                'judul' => 'Laporan Berhasil Dikirim Ulang',
                'pesan' => 'Laporan Ex Barang dengan ID RPT-'
                    . str_pad($laporan->id, 6, '0', STR_PAD_LEFT)
                    . ' berhasil diperbaiki dan dikirim ulang. '
                    . 'Laporan menunggu proses verifikasi kembali.',
                'jenis' => 'dikirim_ulang',
                'dibaca_pada' => null,
            ]);
        });

    } catch (\Throwable $e) {
        // Jika proses gagal, hapus foto baru agar tidak menjadi file sampah
        if ($fotoBaru !== $fotoLama) {
            Storage::disk('public')->delete($fotoBaru);
        }

        throw $e;
    }

    // Hapus foto lama hanya setelah transaksi berhasil
    if ($fotoBaru !== $fotoLama && $fotoLama) {
        Storage::disk('public')->delete($fotoLama);
    }

    return redirect()
        ->route('riwayatEmployee')
        ->with('success', 'Laporan berhasil diperbaiki dan dikirim ulang.');
}


}