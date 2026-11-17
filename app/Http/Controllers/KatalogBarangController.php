<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KatalogBarangController extends Controller
{
    // Menampilkan seluruh data katalog
    public function index()
    {
        $barang = DB::table('barang')
            ->orderBy('kode_barang', 'asc')
            ->get();

        return view('admin.katalog-admin', compact('barang'));
    }

    // Menampilkan halaman edit berdasarkan kode barang
    public function edit($itemNumber)
    {
        $item = DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->first();

        if (!$item) {
            abort(404, 'Data barang tidak ditemukan.');
        }

        return view('admin.edit-katalog', compact('item'));
    }

    // Menyimpan perubahan data barang
    public function update(Request $request, $itemNumber)
    {
        $item = DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->first();

        if (!$item) {
            abort(404, 'Data barang tidak ditemukan.');
        }

        $validated = $request->validate([
            'deskripsi'   => ['required', 'string'],
            'kategori'    => ['nullable', 'string', 'max:255'],
            'lokasi_rak'  => ['nullable', 'string', 'max:255'],
            'satuan'      => ['nullable', 'string', 'max:50'],
            'stok_fisik'  => ['nullable', 'integer', 'min:0'],
            'stok_maximo' => ['nullable', 'integer', 'min:0'],
            'harga_satuan'=> ['nullable', 'numeric', 'min:0'],
        ]);

        DB::table('barang')
            ->where('kode_barang', $itemNumber)
            ->update([
                'deskripsi'    => $validated['deskripsi'],
                'kategori'     => $validated['kategori'] ?? null,
                'lokasi_rak'   => $validated['lokasi_rak'] ?? null,
                'satuan'       => $validated['satuan'] ?? null,
                'stok_fisik'   => $validated['stok_fisik'] ?? 0,
                'stok_maximo'  => $validated['stok_maximo'] ?? 0,
                'harga_satuan' => $validated['harga_satuan'] ?? null,
                'updated_at'   => now(),
            ]);

        return redirect()
            ->route('katalogAdmin')
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    // Mencari barang berdasarkan kode barang hasil pemindaian barcode
public function cariBarcode($kodeBarang)
{
    $item = DB::table('barang')
        ->where('kode_barang', $kodeBarang)
        ->first();

    if (!$item) {
        return response()->json([
            'success' => false,
            'message' => 'Barang dengan kode tersebut tidak ditemukan.'
        ], 404);
    }

    return response()->json([
        'success' => true,
        'data' => [
            'kode_barang' => $item->kode_barang,
            'deskripsi' => $item->deskripsi,
            'kategori' => $item->kategori,
            'lokasi_rak' => $item->lokasi_rak,
            'satuan' => $item->satuan,
            'stok_fisik' => $item->stok_fisik,
            'stok_maximo' => $item->stok_maximo,
            'harga_satuan' => $item->harga_satuan,
        ]
    ]);
}

// Menampilkan halaman pemindai barcode
public function pemindaiBarcode()
{
    $barangSimulasi = DB::table('barang')
        ->orderBy('kode_barang', 'asc')
        ->limit(4)
        ->get();

    return view('admin.scanBarcode-admin', compact('barangSimulasi'));
}

// Menyimpan barang baru ke database
public function store(Request $request)
{
    $validated = $request->validate([
        'item_number' => ['required', 'string', 'max:255', 'unique:barang,kode_barang'],
        'deskripsi_spare_part' => ['required', 'string'],
        'kategori_material' => ['nullable', 'string', 'max:255'],
        'lokasi_bin' => ['nullable', 'string', 'max:255'],
        'satuan_ukur' => ['nullable', 'string', 'max:255'],
        'stok_fisik' => ['nullable', 'integer', 'min:0'],
        'stok_maximo' => ['nullable', 'integer', 'min:0'],
        'harga_satuan' => ['nullable', 'numeric', 'min:0'],
    ], [
        'item_number.required' => 'Item Number wajib diisi.',
        'item_number.unique' => 'Item Number sudah terdaftar.',
        'deskripsi_spare_part.required' => 'Deskripsi barang wajib diisi.',
    ]);

    DB::table('barang')->insert([
        'kode_barang' => $validated['item_number'],
        'deskripsi' => $validated['deskripsi_spare_part'],
        'kategori' => $validated['kategori_material'] ?? null,
        'lokasi_rak' => $validated['lokasi_bin'] ?? null,
        'satuan' => $validated['satuan_ukur'] ?? null,
        'stok_fisik' => $validated['stok_fisik'] ?? 0,
        'stok_maximo' => $validated['stok_maximo'] ?? 0,
        'harga_satuan' => $validated['harga_satuan'] ?? null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()
        ->route('katalogAdmin')
        ->with('success', 'Barang berhasil ditambahkan.');
}


// Menghapus barang dari database
public function destroy($itemNumber)
{
    $barang = DB::table('barang')
        ->where('kode_barang', $itemNumber)
        ->first();

    if (!$barang) {
        return redirect()
            ->route('katalogAdmin')
            ->with('error', 'Data barang tidak ditemukan.');
    }

    DB::table('barang')
        ->where('kode_barang', $itemNumber)
        ->delete();

    return redirect()
        ->route('katalogAdmin')
        ->with('success', 'Barang berhasil dihapus.');
}

}
