<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Riwayat Ex Barang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen p-6">
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('riwayatEmployee') }}" class="inline-block mb-5 text-blue-600 hover:underline">
            ← Kembali ke Riwayat
        </a>

        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <div class="flex flex-wrap justify-between items-start gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        Detail Laporan Ex Barang
                    </h1>
                    <p class="text-slate-500 mt-1">
                        RPT-{{ str_pad($laporan->id, 6, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <span class="px-3 py-2 rounded-full text-sm bg-blue-50 text-blue-700">
                    {{ $laporan->status_laporan }}
                </span>
            </div>

            <h2 class="font-semibold text-lg mb-4">Informasi Laporan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @php
                    $fields = [
                        'Tanggal Laporan' => 'tanggal_laporan',
                        'Status Lokasi' => 'status_lokasi',
                        'Bidang' => 'bidang',
                        'Nomor WO' => 'nomor_wo',
                        'Nama Pekerjaan' => 'nama_pekerjaan',
                        'Nomor Aset / Item' => 'item_number',
                        'Nama Barang' => 'nama_barang',
                        'Jenis Pengembalian' => 'jenis_pengembalian',
                        'Kondisi Barang' => 'kondisi_barang',
                        'Material Base' => 'material_base',
                        'Lokasi Gudang' => 'lokasi_gudang',
                        'Jumlah Barang' => 'jumlah_barang',
                        'Satuan' => 'satuan',
                        'Estimasi' => 'estimasi',
                        'Status Kondisi' => 'status_kondisi',
                    ];
                @endphp

                @foreach ($fields as $label => $field)
                    <div class="border-b border-slate-100 pb-3">
                        <p class="text-sm text-slate-500">{{ $label }}</p>
                        <p class="font-medium text-slate-800 mt-1">
                            @if ($field === 'tanggal_laporan' && $laporan->$field)
                                {{ $laporan->$field->format('d M Y, H:i') }}
                            @else
                                {{ $laporan->$field ?? '-' }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-7">
                <h2 class="font-semibold text-lg mb-2">Deskripsi Kondisi Barang</h2>
                <div class="bg-slate-50 rounded-xl p-4 text-slate-700 whitespace-pre-line">
                    {{ $laporan->deskripsi ?: 'Tidak ada deskripsi.' }}
                </div>
            </div>

            <div class="mt-7">
                <h2 class="font-semibold text-lg mb-3">Foto Bukti</h2>

                @if ($laporan->foto_bukti)
                    <a href="{{ asset('storage/' . $laporan->foto_bukti) }}" target="_blank"
                        class="block w-full max-w-lg aspect-3/1 rounded-xl border border-slate-200 overflow-hidden no-underline">

                        <img src="{{ asset('storage/' . $laporan->foto_bukti) }}" alt="Foto bukti kondisi barang"
                            class="w-full h-full object-contain">
                    </a>
                @else
                    <p class="text-slate-500">Foto bukti tidak tersedia.</p>
                @endif
            </div>

            <div class="mt-7">
                <h2 class="font-semibold text-lg mb-3">Tanda Tangan Pelapor</h2>

                @if ($laporan->tanda_tangan)
                    <div
                        class="w-full max-w-lg aspect-3/1 rounded-xl border border-slate-200 p-3 bg-white overflow-hidden">
                        <img src="{{ $laporan->tanda_tangan }}" alt="Tanda tangan pelapor"
                            class="w-full h-full object-contain">
                    </div>
                @else
                    <p class="text-slate-500">Tanda tangan tidak tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
