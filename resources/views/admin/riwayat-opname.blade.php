@extends('layouts.app-admin')

@section('title', 'Riwayat Opname')

@section('page-title', 'Riwayat Opname Fisik')

@section('page-subtitle', 'Catatan pemeriksaan stok barang')

@section('Profile', 'Admin')

@section('content')

    <style>
        .history-card {
            background: #fff;
            border: 1px solid #e8edf5;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(15, 39, 70, .04);
        }

        .history-header {
            padding: 20px;
            border-bottom: 1px solid #e8edf5;
        }

        .history-header h3 {
            margin: 0 0 5px;
            color: #102a4c;
            font-size: 15px;
            font-weight: 700;
        }

        .history-header p {
            margin: 0;
            color: #8a9ab1;
            font-size: 11px;
        }

        .history-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            min-width: 950px;
            border-collapse: collapse;
        }

        .history-table th {
            padding: 14px 16px;
            background: #fafbfd;
            color: #8494aa;
            text-align: left;
            font-size: 10px;
            white-space: nowrap;
        }

        .history-table td {
            padding: 15px 16px;
            border-top: 1px solid #f0f3f7;
            color: #526680;
            font-size: 11px;
            white-space: nowrap;
        }

        .history-item {
            color: #075dcc;
            font-weight: 700;
        }

        .history-stock {
            color: #1b304d;
            font-weight: 700;
        }

        .history-note {
            max-width: 220px;
            white-space: normal !important;
        }

        .history-empty {
            padding: 35px !important;
            text-align: center;
            color: #94a3b8 !important;
        }

        .history-pagination {
            padding: 16px 20px;
        }
    </style>

    <div class="history-card">

        <div class="history-header">
            <h3>Riwayat Pemeriksaan Stok Fisik</h3>
            <p>Seluruh catatan opname yang telah disimpan oleh Admin Gudang</p>
        </div>

        <div class="history-wrapper">

            <table class="history-table">

                <thead>
                    <tr>
                        <th>Tanggal Opname</th>
                        <th>Item Number</th>
                        <th>Deskripsi Material</th>
                        <th>Stok Sebelum</th>
                        <th>Stok Sesudah</th>
                        <th>Stok Maximo</th>
                        <th>Catatan Opname</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($riwayat as $item)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal_opname)->format('d M Y H:i') }}
                            </td>

                            <td>
                                <span class="history-item">
                                    {{ $item->kode_barang }}
                                </span>
                            </td>

                            <td>
                                {{ $item->deskripsi ?? 'Data barang tidak tersedia' }}
                            </td>

                            <td>
                                <span class="history-stock">
                                    {{ $item->stok_fisik_sebelum }}
                                </span>
                                {{ $item->satuan }}
                            </td>

                            <td>
                                <span class="history-stock">
                                    {{ $item->stok_fisik_sesudah }}
                                </span>
                                {{ $item->satuan }}
                            </td>

                            <td>
                                <span class="history-stock">
                                    {{ $item->stok_maximo_saat_opname }}
                                </span>
                                {{ $item->satuan }}
                            </td>

                            <td class="history-note">
                                {{ $item->catatan_opname ?: '-' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="history-empty">
                                Belum ada riwayat opname yang tersimpan.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="history-pagination">
            {{ $riwayat->links() }}
        </div>

    </div>

@endsection
