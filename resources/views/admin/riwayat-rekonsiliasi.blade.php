@extends('layouts.app-admin')

@section('title', 'Riwayat Rekonsiliasi')

@section('page-title', 'Riwayat Rekonsiliasi')

@section('page-subtitle', 'Catatan tindak lanjut selisih persediaan')

@section('Profile', 'Admin')

@section('content')

    <style>
        .recon-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 15px;
            overflow: hidden;
        }

        .recon-header {
            padding: 20px;
            border-bottom: 1px solid #e8edf5;
        }

        .recon-header h3 {
            margin: 0 0 5px;
            color: #102a4c;
            font-size: 15px;
        }

        .recon-header p {
            margin: 0;
            color: #8a9ab1;
            font-size: 11px;
        }

        .recon-wrapper {
            overflow-x: auto;
        }

        .recon-table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }

        .recon-table th {
            padding: 14px 16px;
            background: #fafbfd;
            color: #8494aa;
            font-size: 10px;
            text-align: left;
            white-space: nowrap;
        }

        .recon-table td {
            padding: 15px 16px;
            border-top: 1px solid #f0f3f7;
            color: #526680;
            font-size: 11px;
            vertical-align: top;
        }

        .recon-code {
            color: #075dcc;
            font-weight: 700;
        }

        .recon-delta {
            color: #ef4444;
            font-weight: 700;
        }

        .recon-note {
            max-width: 230px;
            white-space: normal;
        }

        .recon-empty {
            padding: 35px !important;
            text-align: center;
            color: #94a3b8 !important;
        }

        .recon-pagination {
            padding: 16px 20px;
        }
    </style>

    <div class="recon-card">

        <div class="recon-header">
            <h3>Riwayat Pencatatan Rekonsiliasi</h3>
            <p>
                Catatan pemeriksaan dan tindak lanjut selisih stok.
                Data Maximo tidak diubah oleh halaman ini.
            </p>
        </div>

        <div class="recon-wrapper">

            <table class="recon-table">

                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Item Number</th>
                        <th>Deskripsi</th>
                        <th>Stok Fisik</th>
                        <th>Stok Maximo</th>
                        <th>Selisih</th>
                        <th>Nilai Deviasi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($riwayatRekonsiliasi as $item)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_rekonsiliasi)->format('d M Y H:i') }}
                            </td>

                            <td>
                                <span class="recon-code">
                                    {{ $item->kode_barang }}
                                </span>
                            </td>

                            <td>
                                {{ $item->deskripsi ?? 'Data barang tidak tersedia' }}
                            </td>

                            <td>
                                {{ $item->stok_fisik }} {{ $item->satuan }}
                            </td>

                            <td>
                                {{ $item->stok_maximo }} {{ $item->satuan }}
                            </td>

                            <td>
                                <span class="recon-delta">
                                    {{ $item->selisih > 0 ? '+' : '' }}{{ $item->selisih }}
                                    {{ $item->satuan }}
                                </span>
                            </td>

                            <td>
                                @if ($item->nilai_deviasi !== null)
                                    Rp {{ number_format($item->nilai_deviasi, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="recon-note">
                                {{ $item->catatan_rekonsiliasi ?: '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="recon-empty">
                                Belum ada catatan rekonsiliasi.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="recon-pagination">
            {{ $riwayatRekonsiliasi->links() }}
        </div>

    </div>

@endsection
