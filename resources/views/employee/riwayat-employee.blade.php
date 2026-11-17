@extends('layouts.app')

@section('title', 'Riwayat')

@section('page-title', 'Riwayat')

@section('page-subtitle', 'PLN Nusantara Power Services')

@section('content')

    <div class="history-container">

        {{-- ================================
         HISTORY CARD
    ================================= --}}
        <div class="history-card">

            {{-- ================================
             FILTER & SEARCH
        ================================= --}}
            <div class="history-toolbar">

                {{-- FILTER --}}
                <div class="history-filter">

                    <button type="button" class="filter-btn active" data-filter="semua">
                        Semua
                    </button>

                    <button type="button" class="filter-btn" data-filter="investasi">
                        Investasi
                    </button>

                    <button type="button" class="filter-btn" data-filter="operasi">
                        Operasi
                    </button>

                </div>


                {{-- SEARCH --}}
                <div class="history-search">

                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.7" />

                        <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                    </svg>

                    <input type="text" id="historySearch" placeholder="Cari...">

                </div>

            </div>


            {{-- ================================
             TABLE
        ================================= --}}
            <div class="history-table-wrapper">

                <table class="history-table">

                    <thead>
                        <tr>

                            <th>ID Laporan</th>

                            <th>Nama Barang</th>

                            <th>No. Asset</th>

                            <th>Kondisi</th>

                            <th>Bidang</th>

                            <th>Tanggal</th>

                            <th>Status Pengajuan</th>

                            <th>Detail</th>

                        </tr>
                    </thead>


                    <tbody id="historyTableBody">
                        @forelse ($laporan as $item)
                            <tr data-category="{{ $item->kondisi_barang }}">
                                <td>
                                    <a href="javascript:void(0)" class="report-id">
                                        RPT-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>

                                <td class="item-name">
                                    {{ $item->nama_barang }}
                                </td>

                                <td>
                                    <span class="asset-number">
                                        {{ $item->item_number }}
                                    </span>
                                </td>

                                <td>
                                    <span class="condition-badge {{ $item->kondisi_barang }}">
                                        {{ ucfirst($item->kondisi_barang) }}
                                    </span>
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $item->bidang)) }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y H:i') }}
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $status = trim($item->status_laporan ?? 'Menunggu Verifikasi');

                                        $statusClass = match ($status) {
                                            'Menunggu Verifikasi' => 'status-menunggu',
                                            'Disetujui' => 'status-disetujui',
                                            'Ditolak' => 'status-ditolak',
                                            'Perlu Revisi' => 'status-revisi',
                                            default => 'status-default',
                                        };
                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">

                                        <a href="{{ route('riwayatEmployee.detail', $item->id) }}" class="btn-detail">
                                            Lihat Detail
                                        </a>

                                        @if ($item->status_laporan === 'Perlu Revisi' && $item->sumber_input === 'worker')
                                            <a href="{{ route('riwayatEmployee.revisi', $item->id) }}" class="btn-revisi">
                                                Edit Revisi
                                            </a>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">
                                    Belum ada laporan Ex Barang.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>


            {{-- EMPTY STATE --}}
            <div class="history-empty" id="historyEmpty" style="display: none;">

                <div class="empty-icon">

                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.7" />

                        <path d="M16.5 16.5L21 21" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                    </svg>

                </div>

                <h4>Data tidak ditemukan</h4>

                <p>
                    Tidak ada riwayat yang sesuai dengan pencarian.
                </p>

            </div>

        </div>

    </div>


    {{-- ========================================
     CSS
======================================== --}}
    <style>
        .history-container {
            width: 100%;
            max-width: 100%;
            margin: 0 auto;
        }


        /* ========================================
                                                                                                                   HISTORY CARD
                                                                                                                ======================================== */

        .history-card {
            background: #ffffff;

            border: 1px solid #e8edf3;

            border-radius: 16px;

            box-shadow:
                0 2px 5px rgba(15, 23, 42, 0.06);

            overflow: hidden;
        }


        /* ========================================
                                                                                                                   TOOLBAR
                                                                                                                ======================================== */

        .history-toolbar {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 18px 18px 16px;

            border-bottom: 1px solid #edf0f4;
        }


        /* ========================================
                                                                                                                   FILTER
                                                                                                                ======================================== */

        .history-filter {
            display: flex;

            align-items: center;

            background: #f1f3f6;

            border-radius: 12px;

            padding: 3px;

            flex-shrink: 0;
        }

        .filter-btn {
            border: none;

            background: transparent;

            padding: 9px 15px;

            border-radius: 9px;

            font-family: inherit;

            font-size: 12px;

            font-weight: 500;

            color: #617089;

            cursor: pointer;

            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            color: #075eae;
        }

        .filter-btn.active {
            background: #ffffff;

            color: #075eae;

            font-weight: 600;

            box-shadow:
                0 1px 4px rgba(15, 23, 42, 0.10);
        }


        /* ========================================
                                                                                                                   SEARCH
                                                                                                                ======================================== */

        .history-search {
            flex: 1;

            height: 40px;

            display: flex;

            align-items: center;

            gap: 9px;

            padding: 0 13px;

            background: #f8fafc;

            border: 1px solid #dfe5ec;

            border-radius: 11px;

            color: #95a1b2;
        }

        .history-search:focus-within {
            border-color: #2d73d5;

            background: #ffffff;

            box-shadow:
                0 0 0 3px rgba(45, 115, 213, 0.08);
        }

        .history-search input {
            width: 100%;

            border: none;

            outline: none;

            background: transparent;

            font-family: inherit;

            font-size: 13px;

            color: #263b5b;
        }

        .history-search input::placeholder {
            color: #98a4b5;
        }


        /* ========================================
                                                                                                                   TABLE
                                                                                                                ======================================== */

        .history-table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        .history-table {
            width: 100%;

            border-collapse: collapse;

            min-width: 760px;
        }


        /* HEADER */

        .history-table thead th {
            padding: 13px 24px;

            text-align: left;

            font-size: 11px;

            font-weight: 600;

            color: #8a98ad;

            background: #ffffff;

            border-bottom: 1px solid #edf0f4;

            white-space: nowrap;
        }


        /* BODY */

        .history-table tbody td {
            padding: 17px 24px;

            font-size: 12px;

            color: #5e7089;

            border-bottom: 1px solid #f0f2f5;

            white-space: nowrap;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }

        .history-table tbody tr {
            transition: background 0.2s ease;
        }

        .history-table tbody tr:hover {
            background: #fafcff;
        }


        /* ========================================
                                                                                                                   REPORT ID
                                                                                                                ======================================== */

        .report-id {
            color: #005eff;

            font-size: 11px;

            font-weight: 500;

            text-decoration: none;
        }

        .report-id:hover {
            text-decoration: underline;
        }


        /* ========================================
                                                                                                                   ITEM NAME
                                                                                                                ======================================== */

        .item-name {
            color: #172b4d !important;

            font-size: 13px !important;

            font-weight: 500;
        }


        /* ========================================
                                                                                                                   ASSET NUMBER
                                                                                                                ======================================== */

        .asset-number {
            color: #617089;

            font-size: 11px;
        }


        /* ========================================
                                                                                                                   CONDITION BADGE
                                                                                                                ======================================== */

        .condition-badge {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 4px 11px;

            border-radius: 20px;

            font-size: 10px;

            font-weight: 500;
        }


        /* INVESTASI */

        .condition-badge.investasi {
            background: #e2efff;

            border: 1px solid #b9d7ff;

            color: #1263cf;
        }


        /* OPERASI */

        .condition-badge.operasi {
            background: #fff2cf;

            border: 1px solid #ffd66b;

            color: #e79a00;
        }


        /* ========================================
                                                                                                                   EMPTY STATE
                                                                                                                ======================================== */

        .history-empty {
            padding: 55px 20px;

            text-align: center;
        }

        .empty-icon {
            width: 58px;
            height: 58px;

            margin: 0 auto 13px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 14px;

            background: #eef5ff;

            color: #1769d2;
        }

        .history-empty h4 {
            margin: 0;

            font-size: 15px;

            color: #263b5b;
        }

        .history-empty p {
            margin: 7px 0 0;

            font-size: 12px;

            color: #8c99ab;
        }

        /* ========================================
                                                                                                           DETAIL BUTTON
                                                                                                        ======================================== */

        .btn-detail {
            border: none;

            background: #eef5ff;

            color: #1263cf;

            padding: 7px 12px;

            border-radius: 8px;

            font-family: inherit;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;

            transition: all 0.2s ease;

            white-space: nowrap;
            text-decoration: none !important;
        }

        .btn-detail:hover {
            background: #dcecff;

            color: #0758bd;
        }

        /* ========================================
                                                                               DETAIL SECTION TITLE
                                                                            ======================================== */

        .detail-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .detail-section-title h4 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            color: #172b4d;
        }

        .detail-section-title p {
            margin: 3px 0 0;
            font-size: 10px;
            color: #8a98ad;
        }

        .detail-step-number {
            width: 30px;
            height: 30px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #edf5ff;
            color: #1263cf;

            font-size: 12px;
            font-weight: 700;
        }


        /* ========================================
                                                                               DETAIL GRID
                                                                            ======================================== */

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .detail-item.full {
            grid-column: 1 / -1;
        }

        .detail-item {
            padding: 12px 14px;

            background: #f8fafc;

            border: 1px solid #e8edf3;

            border-radius: 9px;
        }

        .detail-item span:first-child {
            display: block;

            margin-bottom: 5px;

            font-size: 10px;

            color: #8a98ad;
        }

        .detail-item strong {
            display: block;

            font-size: 12px;

            font-weight: 600;

            color: #263b5b;
        }


        /* ========================================
                                                                               VERIFIED
                                                                            ======================================== */

        .verified-text {
            color: #179447 !important;
        }


        /* ========================================
                                                                               CONDITION
                                                                            ======================================== */

        .condition-result {
            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 13px 15px;

            margin-bottom: 16px;

            background: #fff8e6;

            border: 1px solid #f8df9a;

            border-radius: 9px;
        }

        .condition-label {
            font-size: 11px;

            color: #68778c;
        }

        .condition-status {
            padding: 6px 12px;

            border-radius: 20px;

            background: #fff0b8;

            color: #9a6b00;

            font-size: 11px;

            font-weight: 600;
        }


        /* ========================================
                                                                               EVIDENCE
                                                                            ======================================== */

        .evidence-section,
        .description-section,
        .signature-section {
            margin-top: 18px;
        }

        .evidence-label {
            display: block;

            margin-bottom: 8px;

            font-size: 11px;

            font-weight: 600;

            color: #263b5b;
        }


        /* FOTO */

        .evidence-image {
            width: 100%;
            height: 220px;

            overflow: hidden;

            border: 1px solid #e5eaf0;

            border-radius: 10px;

            background: #f6f8fa;
        }

        .evidence-image img {
            width: 100%;
            height: 100%;

            object-fit: contain;

            display: block;
        }

        .image-placeholder {
            width: 100%;
            height: 100%;

            display: none;

            align-items: center;
            justify-content: center;

            flex-direction: column;

            color: #8a98ad;
        }

        .image-placeholder span {
            font-size: 30px;
        }

        .image-placeholder p {
            margin: 5px 0 0;

            font-size: 10px;
        }


        /* ========================================
                                                                               DESCRIPTION
                                                                            ======================================== */

        .description-box {
            padding: 13px 15px;

            border: 1px solid #e5eaf0;

            border-radius: 9px;

            background: #f8fafc;

            color: #5e7089;

            font-size: 11px;

            line-height: 1.7;
        }


        /* ========================================
                                                                               SIGNATURE
                                                                            ======================================== */

        .signature-box {
            width: 100%;
            height: 140px;

            overflow: hidden;

            border: 1px solid #e5eaf0;

            border-radius: 10px;

            background: #fff;
        }

        .signature-box img {
            width: 100%;
            height: 100%;

            object-fit: contain;
        }

        .signature-placeholder {
            width: 100%;
            height: 100%;

            display: none;

            align-items: center;
            justify-content: center;

            flex-direction: column;

            color: #8a98ad;
        }

        .signature-placeholder span {
            font-size: 28px;
        }

        .signature-placeholder p {
            margin: 5px 0 0;

            font-size: 10px;
        }

        .signature-name {
            margin: 6px 0 0;

            font-size: 10px;

            color: #68778c;
        }


        /* ========================================
                                                                               STATEMENT
                                                                            ======================================== */

        .statement-box {
            display: flex;

            align-items: flex-start;

            gap: 10px;

            margin-top: 18px;

            padding: 13px 15px;

            border: 1px solid #d9eee2;

            border-radius: 9px;

            background: #f2fbf5;
        }

        .statement-check {
            width: 20px;
            height: 20px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 5px;

            background: #18a957;

            color: white;

            font-size: 12px;
            font-weight: 700;
        }

        .statement-box p {
            margin: 1px 0 0;

            font-size: 10px;

            line-height: 1.6;

            color: #41624f;
        }


        /* ========================================
                                                                               STATUS
                                                                            ======================================== */

        .report-status-section {
            padding-top: 4px;
        }

        .status-submitted {
            display: inline-flex;

            align-items: center;

            padding: 7px 12px;

            border-radius: 20px;

            background: #e9f9ef;

            color: #179447;

            font-size: 11px;

            font-weight: 600;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid transparent;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-menunggu {
            background-color: #fef3c7 !important;
            color: #92400e !important;
            border-color: #fcd34d !important;
        }

        .status-disetujui {
            background-color: #dcfce7 !important;
            color: #166534 !important;
            border-color: #86efac !important;
        }

        .status-direvisi {
            background-color: #dbeafe !important;
            color: #1d4ed8 !important;
            border-color: #93c5fd !important;
        }

        .status-ditolak {
            background-color: #fee2e2 !important;
            color: #b91c1c !important;
            border-color: #fca5a5 !important;
        }

        .status-default {
            background-color: #f3f4f6 !important;
            color: #374151 !important;
            border-color: #d1d5db !important;
        }

        .btn-revisi {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 7px 12px;

            border-radius: 8px;

            background: #fff4df;
            border: 1px solid #f4d18b;

            color: #b7791f;

            font-family: inherit;
            font-size: 11px;
            font-weight: 600;

            text-decoration: none;
            white-space: nowrap;

            transition: all 0.2s ease;
        }

        .btn-revisi:hover {
            background: #ffebc2;
            color: #925f12;
        }
    </style>

    {{-- ========================================
     JAVASCRIPT
======================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const filterButtons =
                document.querySelectorAll('.filter-btn');

            const rows =
                document.querySelectorAll('#historyTableBody tr');

            const searchInput =
                document.getElementById('historySearch');

            const emptyState =
                document.getElementById('historyEmpty');


            let currentFilter = 'semua';


            /* ========================================
               FILTER
            ======================================== */

            function filterHistory() {

                const searchValue =
                    searchInput.value.toLowerCase().trim();

                let visibleCount = 0;


                rows.forEach(function(row) {

                    const category =
                        row.dataset.category;

                    const text =
                        row.textContent.toLowerCase();


                    const matchesFilter =
                        currentFilter === 'semua' ||
                        category === currentFilter;

                    const matchesSearch =
                        text.includes(searchValue);


                    if (matchesFilter && matchesSearch) {

                        row.style.display = '';

                        visibleCount++;

                    } else {

                        row.style.display = 'none';

                    }

                });


                /* EMPTY STATE */

                if (visibleCount === 0) {

                    emptyState.style.display = 'block';

                } else {

                    emptyState.style.display = 'none';

                }

            }


            /* ========================================
               FILTER BUTTON
            ======================================== */

            filterButtons.forEach(function(button) {

                button.addEventListener('click', function() {

                    filterButtons.forEach(function(btn) {

                        btn.classList.remove('active');

                    });


                    this.classList.add('active');


                    currentFilter =
                        this.dataset.filter;


                    filterHistory();

                });

            });


            /* ========================================
               SEARCH
            ======================================== */

            searchInput.addEventListener(
                'input',
                filterHistory
            );

        });

        /* ========================================
           DETAIL MODAL
        ======================================== */

        function openDetail(reportId) {

            const modal = document.getElementById('detailModal');

            const reportIdElement =
                document.getElementById('detailReportId');


            // Ubah ID laporan pada header modal
            if (reportIdElement) {
                reportIdElement.textContent = reportId;
            }


            // Tampilkan modal
            modal.classList.add('show');


            // Kunci scroll halaman belakang
            document.body.style.overflow = 'hidden';

        }


        function closeDetail() {

            const modal =
                document.getElementById('detailModal');

            modal.classList.remove('show');

            document.body.style.overflow = '';
        }


        /* CLOSE DENGAN ESC */

        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeDetail();

            }

        });
    </script>

@endsection
