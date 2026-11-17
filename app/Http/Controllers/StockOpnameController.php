<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class StockOpnameController extends Controller
{
    public function index()
    {
        // Mengambil barang yang memiliki selisih stok
        $barang = DB::table('barang')
            ->whereColumn('stok_fisik', '!=', 'stok_maximo')
            ->orderBy('kode_barang', 'asc')
            ->get();

        // Menghitung seluruh barang
        $totalBarang = DB::table('barang')->count();

        // Menghitung barang yang stoknya sesuai
        $jumlahSesuai = DB::table('barang')
            ->whereColumn('stok_fisik', '=', 'stok_maximo')
            ->count();

        // Menghitung barang yang memiliki selisih
        $jumlahSelisih = $barang->count();

        // Menghitung total nilai perselisihan stok
$totalNilaiDeviasi = DB::table('barang')
    ->whereColumn('stok_fisik', '!=', 'stok_maximo')
    ->whereNotNull('harga_satuan')
    ->selectRaw('SUM(ABS(stok_fisik - stok_maximo) * harga_satuan) as total')
    ->value('total') ?? 0;

        // Menghitung akurasi persediaan
        $akurasi = $totalBarang > 0
            ? ($jumlahSesuai / $totalBarang) * 100
            : 0;

        // Mengambil kategori yang tersedia
        $kategoriList = DB::table('barang')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('admin.opname', compact(
            'barang',
            'totalBarang',
            'jumlahSesuai',
            'jumlahSelisih',
            'akurasi',
            'kategoriList',
            'totalNilaiDeviasi'
        ));
    }



public function saveOpname(Request $request, $itemNumber)
{
    // Validasi input
    $validated = $request->validate([
        'stok_fisik' => ['required', 'integer', 'min:0'],
        'catatan_opname' => ['nullable', 'string'],
    ]);

    // Proses penyimpanan menggunakan database transaction
    DB::transaction(function () use ($validated, $itemNumber) {

        // Ambil data barang dan kunci selama proses berlangsung
        $item = DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->lockForUpdate()
            ->first();

        if (!$item) {
            abort(404, 'Data barang tidak ditemukan.');
        }

        $tanggalOpname = now();

        // Simpan riwayat opname sebelum stok diperbarui
        DB::table('riwayat_opname')->insert([
            'kode_barang' => $item->kode_barang,

            'stok_fisik_sebelum' => $item->stok_fisik,

            'stok_fisik_sesudah' => $validated['stok_fisik'],

            'stok_maximo_saat_opname' => $item->stok_maximo,

            'catatan_opname' => $validated['catatan_opname'] ?? null,

            'tanggal_opname' => $tanggalOpname,

            'created_at' => $tanggalOpname,
            'updated_at' => $tanggalOpname,
        ]);

        // Perbarui stok fisik terbaru pada tabel barang
        DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->update([
                'stok_fisik' => $validated['stok_fisik'],

                'catatan_opname' => $validated['catatan_opname'] ?? null,

                'tanggal_opname' => $tanggalOpname,

                'updated_at' => $tanggalOpname,
            ]);
    });

    return redirect()
        ->route('StockAdmin')
        ->with('success', 'Hasil opname berhasil disimpan dan riwayat telah dicatat.');
}


public function riwayat()
{
    $riwayat = DB::table('riwayat_opname as r')
        ->leftJoin('barang as b', 'r.kode_barang', '=', 'b.kode_barang')
        ->select(
            'r.id',
            'r.kode_barang',
            'b.deskripsi',
            'r.stok_fisik_sebelum',
            'r.stok_fisik_sesudah',
            'r.stok_maximo_saat_opname',
            'r.catatan_opname',
            'r.tanggal_opname',
            'b.satuan'
        )
        ->orderByDesc('r.tanggal_opname')
        ->paginate(15);

    return view('admin.riwayat-opname', compact('riwayat'));
}


public function saveRekonsiliasi(Request $request, $itemNumber)
{
    $validated = $request->validate([
        'catatan_rekonsiliasi' => ['nullable', 'string'],
    ]);

    DB::transaction(function () use ($validated, $itemNumber) {

        // Ambil data barang terbaru
        $item = DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->lockForUpdate()
            ->first();

        if (!$item) {
            abort(404, 'Data barang tidak ditemukan.');
        }

        $selisih = (int) $item->stok_fisik - (int) $item->stok_maximo;

        // Pastikan barang masih memiliki selisih
        if ($selisih === 0) {
            abort(422, 'Barang sudah tidak memiliki selisih stok.');
        }

        // Hitung nilai deviasi jika harga tersedia
        $nilaiDeviasi = $item->harga_satuan !== null
            ? abs($selisih) * (float) $item->harga_satuan
            : null;

        $tanggal = now();

        // Simpan catatan rekonsiliasi
        DB::table('riwayat_rekonsiliasi')->insert([
            'kode_barang' => $item->kode_barang,
            'stok_fisik' => $item->stok_fisik,
            'stok_maximo' => $item->stok_maximo,
            'selisih' => $selisih,
            'harga_satuan' => $item->harga_satuan,
            'nilai_deviasi' => $nilaiDeviasi,
            'catatan_rekonsiliasi' =>
                $validated['catatan_rekonsiliasi'] ?? null,
            'tanggal_rekonsiliasi' => $tanggal,
            'created_at' => $tanggal,
            'updated_at' => $tanggal,
        ]);
    });

    return redirect()
        ->route('StockAdmin')
        ->with('success', 'Catatan rekonsiliasi berhasil disimpan. Stok Maximo tidak diubah.');
}


public function riwayatRekonsiliasi()
{
    $riwayatRekonsiliasi = DB::table('riwayat_rekonsiliasi as r')
        ->leftJoin('barang as b', 'r.kode_barang', '=', 'b.kode_barang')
        ->select(
            'r.*',
            'b.deskripsi',
            'b.satuan'
        )
        ->orderByDesc('r.tanggal_rekonsiliasi')
        ->paginate(15);

    return view(
        'admin.riwayat-rekonsiliasi',
        compact('riwayatRekonsiliasi')
    );
}


}
