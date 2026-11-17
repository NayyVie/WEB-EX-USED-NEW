@extends('layouts.app')

@section('title', 'Notifikasi')

@section('page-title', 'Notifikasi')

@section('page-subtitle', 'PLN Nusantara Power Services')

@section('content')

    <div class="notifikasi-container">

        {{-- ================================
             HEADER NOTIFIKASI
        ================================= --}}
        <div class="notification-header">

            <div class="notification-header-left">

                <div class="notification-icon-main">
                    <span>🔔</span>
                </div>

                <div>
                    <h2>{{ __('Notifikasi') }}</h2>

                    <p>
                        Informasi dan pemberitahuan terkait laporan ex used.
                    </p>
                </div>

            </div>



            <form action="{{ route('notifikasiEmployee.bacaSemua') }}" method="POST">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn-read-all">
                    Tandai semua dibaca
                </button>
            </form>


        </div>


        {{-- ================================
             FILTER NOTIFIKASI
        ================================= --}}

        {{-- FILTER NOTIFIKASI --}}
        <div class="notification-filter">

            <button type="button" class="notification-filter-btn active" onclick="filterNotifikasi('semua', this)">
                Semua
                <span class="filter-count">{{ $notifikasi->count() }}</span>
            </button>

            <button type="button" class="notification-filter-btn" onclick="filterNotifikasi('belum', this)">
                Belum Dibaca
                <span class="filter-count unread">
                    {{ $notifikasi->whereNull('dibaca_pada')->count() }}
                </span>
            </button>

            <button type="button" class="notification-filter-btn" onclick="filterNotifikasi('dibaca', this)">
                Sudah Dibaca
                <span class="filter-count">
                    {{ $notifikasi->whereNotNull('dibaca_pada')->count() }}
                </span>
            </button>

        </div>


        {{-- ================================
             DAFTAR NOTIFIKASI
        ================================= --}}

        {{-- DAFTAR NOTIFIKASI --}}
        <div class="notification-card">

            @forelse ($notifikasi as $item)
                @php
                    $jenis = strtolower($item->jenis ?? '');

                    $warna = match ($jenis) {
                        'dibuat' => 'blue',
                        'disetujui' => 'green',
                        'ditolak' => 'orange',
                        'revisi' => 'purple',
                        default => 'blue',
                    };

                    $ikon = match ($jenis) {
                        'dibuat' => '✓',
                        'disetujui' => '✓',
                        'ditolak' => '!',
                        'revisi' => '↻',
                        default => '•',
                    };

                    $sudahDibaca = !is_null($item->dibaca_pada);
                    $statusBaca = $sudahDibaca ? 'dibaca' : 'belum';
                @endphp

                <div class="notification-item {{ $sudahDibaca ? 'read-item' : 'unread-item' }}"
                    data-status="{{ $statusBaca }}">

                    <div class="notification-item-icon {{ $warna }}">
                        {{ $ikon }}
                    </div>

                    <div class="notification-content">

                        <div class="notification-title-row">
                            <h3>{{ $item->judul }}</h3>

                            @unless ($sudahDibaca)
                                <span class="unread-dot"></span>
                            @endunless
                        </div>

                        <p>{{ $item->pesan }}</p>

                        <span class="notification-time">
                            {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                        </span>

                        @unless ($sudahDibaca)
                            <form action="{{ route('notifikasiEmployee.baca', $item->id) }}" method="POST"
                                style="margin-top: 10px;">

                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    style="border: 0; background: transparent; color: #0758b5; cursor: pointer; font: inherit; font-size: 10px;">
                                    Tandai dibaca
                                </button>
                            </form>
                        @endunless

                    </div>
                </div>

            @empty

                <div style="padding: 35px 20px; text-align: center; color: #8a9ab1;">
                    Belum ada notifikasi.
                </div>
            @endforelse

        </div>


        {{-- ================================
             EMPTY STATE
        ================================= --}}
        <div class="notification-empty" id="notificationEmpty" style="display: none;">

            <div class="empty-icon">
                🔔
            </div>

            <h3>Tidak ada notifikasi</h3>

            <p>
                Belum ada notifikasi pada kategori ini.
            </p>

        </div>


    </div>


    {{-- ================================
         CSS
    ================================= --}}
    <style>
        /* ================================
                                   CONTAINER
                                ================================= */

        .notifikasi-container {
            padding: 22px 38px 50px;
            max-width: 1100px;
            margin: 0 auto;
        }


        /* ================================
                                   HEADER
                                ================================= */

        .notification-header {
            background: #ffffff;

            border: 1px solid #e8edf5;
            border-radius: 18px;

            padding: 22px 25px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            box-shadow:
                0 3px 12px rgba(15, 39, 70, 0.04);

            margin-bottom: 18px;
        }


        .notification-header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }


        .notification-icon-main {
            width: 50px;
            height: 50px;

            border-radius: 13px;

            background: #edf5ff;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 22px;
        }


        .notification-header h2 {
            margin: 0;

            color: #102a4c;

            font-size: 22px;
            font-weight: 700;
        }


        .notification-header p {
            margin: 4px 0 0;

            color: #8a9ab1;

            font-size: 12px;
            line-height: 1.5;
        }


        /* ================================
                                   BUTTON TANDAI SEMUA
                                ================================= */

        .btn-read-all {
            height: 39px;

            padding: 0 16px;

            border-radius: 9px;

            border: 1px solid #cfe1f7;

            background: #f4f8fd;

            color: #0758b5;

            font-family: 'Poppins', sans-serif;

            font-size: 11px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;

            white-space: nowrap;
        }


        .btn-read-all:hover {
            background: #e8f2ff;

            transform: translateY(-1px);
        }


        /* ================================
                                   FILTER
                                ================================= */

        .notification-filter {
            background: #ffffff;

            border: 1px solid #e8edf5;
            border-radius: 14px;

            padding: 5px;

            display: inline-flex;

            gap: 3px;

            margin-bottom: 15px;

            box-shadow:
                0 3px 12px rgba(15, 39, 70, 0.03);
        }


        .notification-filter-btn {
            border: none;

            background: transparent;

            color: #74859d;

            padding: 9px 15px;

            border-radius: 9px;

            font-family: 'Poppins', sans-serif;

            font-size: 11px;
            font-weight: 500;

            cursor: pointer;

            transition: 0.2s;

            display: flex;
            align-items: center;

            gap: 7px;
        }


        .notification-filter-btn:hover {
            color: #0758b5;
        }


        .notification-filter-btn.active {
            background: #0758b5;

            color: #ffffff;

            font-weight: 600;
        }


        .filter-count {
            min-width: 20px;
            height: 20px;

            padding: 0 5px;

            border-radius: 20px;

            background: #eaf3ff;

            color: #0758b5;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            font-size: 9px;
            font-weight: 700;
        }


        .notification-filter-btn.active .filter-count {
            background: rgba(255, 255, 255, 0.2);

            color: #ffffff;
        }


        .filter-count.unread {
            background: #fff0f0;
            color: #e53935;
        }


        /* ================================
                                   NOTIFICATION CARD
                                ================================= */

        .notification-card {
            background: #ffffff;

            border: 1px solid #e8edf5;

            border-radius: 18px;

            overflow: hidden;

            box-shadow:
                0 3px 12px rgba(15, 39, 70, 0.04);
        }


        /* ================================
                                   ITEM
                                ================================= */

        .notification-item {
            display: flex;

            align-items: flex-start;

            gap: 16px;

            padding: 20px 23px;

            border-bottom: 1px solid #edf1f6;

            transition: 0.2s;
        }


        .notification-item:last-child {
            border-bottom: none;
        }


        .notification-item:hover {
            background: #fafcff;
        }


        .unread-item {
            background: #fbfdff;
        }


        /* ================================
                                   ICON
                                ================================= */

        .notification-item-icon {
            width: 43px;
            height: 43px;

            flex-shrink: 0;

            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 18px;
            font-weight: 700;
        }


        .notification-item-icon.blue {
            background: #edf5ff;
            color: #1769e0;
        }


        .notification-item-icon.green {
            background: #ecfbf2;
            color: #09a854;
        }


        .notification-item-icon.orange {
            background: #fff8e8;
            color: #ef9d00;
        }


        .notification-item-icon.purple {
            background: #f7efff;
            color: #9146e6;
        }


        /* ================================
                                   CONTENT
                                ================================= */

        .notification-content {
            flex: 1;

            min-width: 0;
        }


        .notification-title-row {
            display: flex;

            align-items: center;

            gap: 8px;
        }


        .notification-content h3 {
            margin: 0;

            color: #1b304d;

            font-size: 14px;
            font-weight: 600;
        }


        .notification-content p {
            margin: 5px 0 7px;

            color: #71839b;

            font-size: 11px;

            line-height: 1.7;
        }


        .notification-content p strong {
            color: #0758b5;

            font-weight: 600;
        }


        .notification-time {
            color: #9aa8ba;

            font-size: 10px;
        }


        /* ================================
                                   UNREAD DOT
                                ================================= */

        .unread-dot {
            width: 7px;
            height: 7px;

            border-radius: 50%;

            background: #e53935;

            flex-shrink: 0;
        }


        /* ================================
                                   EMPTY STATE
                                ================================= */

        .notification-empty {
            background: #ffffff;

            border: 1px solid #e8edf5;

            border-radius: 18px;

            padding: 55px 20px;

            text-align: center;

            box-shadow:
                0 3px 12px rgba(15, 39, 70, 0.04);
        }


        .empty-icon {
            width: 55px;
            height: 55px;

            margin: 0 auto 13px;

            border-radius: 50%;

            background: #f2f6fb;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 23px;
        }


        .notification-empty h3 {
            margin: 0;

            color: #263d5b;

            font-size: 15px;
        }


        .notification-empty p {
            margin: 5px 0 0;

            color: #8a9ab1;

            font-size: 11px;
        }


        /* ================================
                                   RESPONSIVE
                                ================================= */

        @media (max-width: 768px) {

            .notifikasi-container {
                padding: 20px 16px 40px;
            }


            .notification-header {
                padding: 20px;

                align-items: flex-start;
            }


            .notification-header-left {
                align-items: flex-start;
            }


            .notification-icon-main {
                width: 44px;
                height: 44px;

                font-size: 19px;
            }


            .notification-header h2 {
                font-size: 20px;
            }


            .notification-header p {
                font-size: 11px;
            }


            .btn-read-all {
                font-size: 10px;

                padding: 0 12px;
            }


            .notification-item {
                padding: 17px;
            }

        }


        @media (max-width: 520px) {

            .notification-header {
                flex-direction: column;

                align-items: stretch;
            }


            .notification-header-left {
                width: 100%;
            }


            .btn-read-all {
                width: 100%;
            }


            .notification-filter {
                width: 100%;
            }


            .notification-filter-btn {
                flex: 1;

                justify-content: center;

                padding: 9px 7px;

                font-size: 10px;
            }


            .notification-item {
                gap: 11px;

                padding: 16px;
            }


            .notification-item-icon {
                width: 38px;
                height: 38px;

                border-radius: 10px;

                font-size: 15px;
            }


            .notification-content h3 {
                font-size: 12px;
            }


            .notification-content p {
                font-size: 10px;
            }

        }
    </style>


    {{-- ================================
         JAVASCRIPT
    ================================= --}}
    <script>
        function filterNotifikasi(filter, button) {
            document.querySelectorAll('.notification-filter-btn')
                .forEach(btn => btn.classList.remove('active'));

            button.classList.add('active');

            const items = document.querySelectorAll('.notification-item');
            const emptyState = document.getElementById('notificationEmpty');
            let jumlahTampil = 0;

            items.forEach(item => {
                const status = item.dataset.status;

                const tampil =
                    filter === 'semua' ||
                    status === filter;

                item.style.display = tampil ? 'flex' : 'none';

                if (tampil) {
                    jumlahTampil++;
                }
            });

            if (emptyState) {
                emptyState.style.display =
                    jumlahTampil === 0 ? 'block' : 'none';
            }
        }
    </script>

@endsection
