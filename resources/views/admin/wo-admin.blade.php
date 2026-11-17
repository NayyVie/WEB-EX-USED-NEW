@extends('layouts.app-admin')

@section('title', 'Pengeluaran Barang')

@section('page-title', 'Pengeluaran Barang')
@section('page-subtitle', 'Pemeriksaan dan verifikasi laporan Ex Barang dari unit kerja')

@section('content')

    <style>
        /* =========================================================
                                                                                                                                                                                                                                                                                                               PENGELUARAN BARANG - ADMIN GUDANG
                                                                                                                                                                                                                                                                                                               ========================================================= */

        .pengeluaran-page {
            width: 100%;
            max-width: 1500px;
            margin: 0 auto;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               HEADER
                                                                                                                                                                                                                                                                                                               ========================= */

        .pengeluaran-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 24px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn-primary {
            border: none;
            border-radius: 9px;
            height: 34px;
            padding: 0 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: #0758bd;
            color: #ffff;
            border: 1px solid #155eef;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            filter: brightness(.96);
        }

        .btn-primary svg {
            width: 17px;
            height: 17px;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               SUMMARY CARDS
                                                                                                                                                                                                                                                                                                               ========================= */

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid #e1e9f2;
            border-radius: 14px;
            padding: 19px 20px;
            min-height: 112px;
            box-shadow: 0 3px 12px rgba(24, 67, 110, .05);
            position: relative;
            overflow: hidden;
        }

        .summary-card::after {
            content: "";
            position: absolute;
            width: 70px;
            height: 70px;
            right: -27px;
            bottom: -30px;
            border-radius: 50%;
            background: #f1f8ff;
        }

        .summary-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .summary-label {
            color: #7890ae;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .2px;
        }

        .summary-small-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: #e8f4ff;
            color: #0786d1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .summary-small-icon svg {
            width: 17px;
            height: 17px;
        }

        .summary-value {
            margin-top: 10px;
            color: #172b4d;
            font-size: 25px;
            font-weight: 700;
            line-height: 1.1;
        }

        .summary-description {
            margin-top: 6px;
            color: #7890ae;
            font-size: 11px;
        }

        .summary-card.blue .summary-small-icon {
            background: #e4f3ff;
            color: #0786d1;
        }

        .summary-card.orange .summary-small-icon {
            background: #fff3dc;
            color: #e89b00;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               FILTER
                                                                                                                                                                                                                                                                                                               ========================= */

        .filter-card {
            background: #fff;
            border: 1px solid #e1e9f2;
            border-radius: 14px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(24, 67, 110, .04);
        }

        .filter-row {
            display: grid;
            grid-template-columns: minmax(250px, 1fr) 190px auto;
            gap: 10px;
            align-items: center;
        }

        .search-box {
            height: 42px;
            border: 1px solid #d7e2ee;
            border-radius: 9px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            padding: 0 13px;
        }

        .search-box svg {
            width: 17px;
            height: 17px;
            color: #7890ae;
            flex-shrink: 0;
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            padding-left: 9px;
            font-family: inherit;
            font-size: 12px;
            color: #172b4d;
        }

        .search-box input::placeholder {
            color: #9aabc0;
        }

        .filter-select {
            height: 42px;
            border: 1px solid #d7e2ee;
            border-radius: 9px;
            background: #f8fafc;
            color: #526b87;
            padding: 0 12px;
            font-family: inherit;
            font-size: 12px;
            outline: none;
        }

        .btn-print {
            height: 35px;
            border: none;

            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 0 11px;

            border-radius: 9px;

            background: #1653eb;
            color: #ffffff;

            font-family: 'Poppins', sans-serif;
            font-size: 10px;
            font-weight: 600;

            cursor: pointer;
        }

        .btn-print:hover {
            background: #0785c0;
            color: #ffffff;
        }

        .btn-print svg {
            width: 16px;
            height: 16px;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               TABLE
                                                                                                                                                                                                                                                                                                               ========================= */

        .table-card {
            background: #fff;
            border: 1px solid #e1e9f2;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(24, 67, 110, .05);
        }

        .table-header {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #edf1f6;
        }

        .table-header h3 {
            margin: 0;
            font-size: 15px;
            color: #172b4d;
            font-weight: 700;
        }

        .table-header span {
            font-size: 11px;
            color: #7890ae;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            scrollbar-width: thin;
        }

        .pengeluaran-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
        }

        .pengeluaran-table th {
            background: #f5f8fc;
            color: #667d98;
            font-size: 11px;
            font-weight: 700;
            text-align: left;
            padding: 14px 13px;
            border-bottom: 1px solid #e4ebf3;
            white-space: nowrap;
        }

        .pengeluaran-table td {
            padding: 15px 13px;
            border-bottom: 1px solid #edf1f5;
            color: #334f70;
            font-size: 12px;
            vertical-align: middle;
        }

        .pengeluaran-table tbody tr:hover {
            background: #f9fbfd;
        }

        .pengeluaran-table tbody tr:last-child td {
            border-bottom: none;
        }

        .wo-number {
            color: #0874c9;
            font-weight: 700;
            line-height: 1.4;
        }

        .transaction-number {
            color: #172b4d;
            font-weight: 600;
        }

        .material-name {
            color: #172b4d;
            font-weight: 600;
            line-height: 1.4;
        }

        .secondary-text {
            display: block;
            margin-top: 3px;
            color: #8ba0b8;
            font-size: 10px;
        }

        .quantity {
            font-weight: 700;
            color: #172b4d;
        }

        .rack-badge {
            color: #0874c9;
            font-size: 10px;
            font-weight: 600;
        }

        .sync-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 20px;
            background: #e5faf2;
            color: #09986f;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        .sync-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #0cb98a;
        }

        .action-link {
            border: none;
            background: #eef5ff;
            color: #1263cf;
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s ease;
            padding: 7px 12px;
            border-radius: 8px;
        }

        .action-link:hover {
            background: #dcecff;

            color: #0758bd;
        }

        /* =========================
                                                                                                                                                                                                                                                                                                               PAGINATION
                                                                                                                                                                                                                                                                                                               ========================= */

        .table-footer {
            padding: 15px 20px;
            border-top: 1px solid #edf1f6;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .table-info {
            color: #879ab0;
            font-size: 11px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-btn {
            width: 31px;
            height: 31px;
            border-radius: 7px;
            border: 1px solid #dbe5ef;
            background: #fff;
            color: #526b87;
            font-family: inherit;
            font-size: 11px;
            cursor: pointer;
        }

        .page-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .page-btn.active {
            background: #0874c9;
            border-color: #0874c9;
            color: #fff;
        }

        .page-btn:hover:not(.active) {
            background: #f3f8fd;
        }


        /* =========================================================
                                                                                                                                                                                                                                                                                                               RESPONSIVE
                                                                                                                                                                                                                                                                                                               ========================================================= */

        @media (max-width: 1200px) {

            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-row {
                grid-template-columns: 1fr 180px auto;
            }
        }

        @media (max-width: 768px) {

            .pengeluaran-header {
                flex-direction: column;
                gap: 15px;
            }

            .header-actions {
                width: 100%;
            }

            .header-actions button {
                flex: 1;
            }

            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .summary-card {
                padding: 15px;
                min-height: 105px;
            }

            .summary-value {
                font-size: 21px;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }

            .filter-select,
            .btn-print {
                width: 100%;
            }

            .table-header {
                padding: 15px;
            }

            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 520px) {

            .header-actions {
                flex-direction: column;
            }

            .header-actions button {
                width: 100%;
            }

            .summary-grid {
                grid-template-columns: 1fr 1fr;
                gap: 9px;
            }

            .summary-card {
                padding: 13px;
            }

            .summary-label {
                font-size: 10px;
            }

            .summary-value {
                font-size: 19px;
            }

            .summary-description {
                font-size: 9px;
            }

            .table-header h3 {
                font-size: 13px;
            }

            .table-header span {
                display: none;
            }
        }

        /* =========================================================
                                                                                                                                                                                                                                                       MODAL PENGELUARAN BARANG BARU
                                                                                                                                                                                                                                                       ========================================================= */

        .pengeluaran-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(23, 43, 77, 0.55);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 9999;
        }

        .pengeluaran-modal-overlay.show {
            display: flex;
        }

        .pengeluaran-modal {
            width: 100%;
            max-width: 900px;
            max-height: 92vh;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #dce6f0;
            box-shadow: 0 20px 60px rgba(23, 43, 77, 0.22);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: pengeluaranModalIn .2s ease;
        }

        @keyframes pengeluaranModalIn {
            from {
                opacity: 0;
                transform: translateY(10px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* HEADER MODAL */

        .pengeluaran-modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e5edf5;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            background: #ffffff;
        }

        .pengeluaran-modal-header h2 {
            margin: 0;
            color: #172b4d;
            font-size: 18px;
            font-weight: 700;
        }

        .pengeluaran-modal-header p {
            margin: 5px 0 0;
            color: #7890ae;
            font-size: 11px;
        }

        .pengeluaran-modal-close {
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 8px;
            background: #f3f7fb;
            color: #7890ae;
            font-size: 21px;
            line-height: 1;
            cursor: pointer;
            transition: .2s ease;
        }

        .pengeluaran-modal-close:hover {
            background: #e8f1fa;
            color: #172b4d;
        }

        /* BODY */

        .pengeluaran-modal-body {
            padding: 22px 24px;
            overflow-y: auto;
        }

        /* INFO MAXIMO */

        .maximo-info-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 12px 14px;
            border-radius: 10px;
            background: #f0f7ff;
            border: 1px solid #cfe3fa;
            margin-bottom: 20px;
        }

        .maximo-info-left {
            display: flex;
            align-items: center;
            gap: 9px;
            color: #0758bd;
            font-size: 11px;
            font-weight: 700;
        }

        .maximo-info-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #dcecff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0874c9;
            font-size: 14px;
        }

        .maximo-status {
            padding: 5px 10px;
            border-radius: 20px;
            background: #e5faf2;
            color: #09986f;
            border: 1px solid #bcefdc;
            font-size: 9px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* FORM GRID */

        .pengeluaran-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px 16px;
        }

        .pengeluaran-form-group {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .pengeluaran-form-group.full {
            grid-column: 1 / -1;
        }

        .pengeluaran-form-group label {
            color: #526b87;
            font-size: 11px;
            font-weight: 700;
        }

        .pengeluaran-required {
            color: #e5484d;
        }

        .pengeluaran-input,
        .pengeluaran-select,
        .pengeluaran-textarea {
            width: 100%;
            border: 1px solid #d7e2ee;
            border-radius: 9px;
            background: #f8fafc;
            color: #334f70;
            font-family: inherit;
            font-size: 11px;
            outline: none;
            transition: .2s ease;
            box-sizing: border-box;
        }

        .pengeluaran-input,
        .pengeluaran-select {
            height: 40px;
            padding: 0 12px;
        }

        .pengeluaran-input:focus,
        .pengeluaran-select:focus,
        .pengeluaran-textarea:focus {
            border-color: #0874c9;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(8, 116, 201, .08);
        }

        .pengeluaran-input::placeholder,
        .pengeluaran-textarea::placeholder {
            color: #9aabc0;
        }

        .signature-box {
            width: 100%;
            height: 145px;

            border: 1px solid #d9e1ec;
            border-radius: 9px;

            background:
                repeating-linear-gradient(0deg,
                    transparent,
                    transparent 28px,
                    #f1f4f8 29px);

            position: relative;

            cursor: crosshair;
        }

        .signature-placeholder {
            position: absolute;

            inset: 0;

            display: flex;
            flex-direction: column;

            align-items: center;
            justify-content: center;

            color: #a1adbd;
        }

        .signature-placeholder span {
            font-size: 26px;
            margin-bottom: 4px;
        }

        .signature-placeholder p {
            margin: 0;

            font-size: 12px;
        }

        /* MATERIAL */

        .material-info {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 10px;
            margin-top: 8px;
        }

        .material-stock-info {
            padding: 9px 11px;
            border-radius: 8px;
            background: #eef5fb;
            color: #526b87;
            font-size: 10px;
        }

        .material-stock-info strong {
            color: #172b4d;
        }

        .material-price-info {
            padding: 9px 11px;
            border-radius: 8px;
            background: #eef7ff;
            color: #0874c9;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }

        /* FOOTER */

        .pengeluaran-modal-footer {
            padding: 15px 24px;
            border-top: 1px solid #e5edf5;
            background: #fbfcfe;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 9px;
        }

        .btn-modal-cancel {
            height: 36px;
            padding: 0 15px;
            border: 1px solid #d7e2ee;
            border-radius: 8px;
            background: #ffffff;
            color: #526b87;
            font-family: inherit;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-modal-cancel:hover {
            background: #f3f7fb;
        }

        .btn-modal-save {
            height: 36px;
            padding: 0 16px;
            border: 1px solid #155eef;
            border-radius: 8px;
            background: #0758bd;
            color: #ffffff;
            font-family: inherit;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: .2s ease;
        }

        .btn-modal-save:hover {
            background: #064da8;
            transform: translateY(-1px);
        }

        .btn-modal-save svg {
            width: 15px;
            height: 15px;
        }

        /* RESPONSIVE MODAL */

        @media (max-width: 700px) {
            .pengeluaran-modal {
                max-height: 95vh;
            }

            .pengeluaran-modal-header {
                padding: 17px;
            }

            .pengeluaran-modal-body {
                padding: 17px;
            }

            .pengeluaran-form-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .pengeluaran-form-group.full {
                grid-column: auto;
            }

            .material-info {
                grid-template-columns: 1fr;
            }

            .pengeluaran-modal-footer {
                padding: 13px 17px;
            }

            .btn-modal-cancel,
            .btn-modal-save {
                flex: 1;
                justify-content: center;
            }
        }

        /* =========================
                                                                                                                                                                                                                                           UKURAN GAMBAR DI TABEL
                                                                                                                                                                                                                                           ========================= */

        .pengeluaran-table td img {
            width: 130px;
            height: 100px;
            object-fit: contain;
            object-position: center;
            display: block;
            margin: 0 auto;
            border-radius: 8px;
        }

        /* Supaya kolom gambar tidak terlalu lebar */
        .pengeluaran-table td:has(img) {
            width: 150px;
            min-width: 150px;
        }

        /* TABEL LAPORAN EX USED */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
        }

        .pengeluaran-table {
            width: 100%;
            min-width: 1250px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* Ukuran masing-masing kolom */
        .pengeluaran-table th:nth-child(1),
        .pengeluaran-table td:nth-child(1) {
            width: 95px;
        }

        .pengeluaran-table th:nth-child(2),
        .pengeluaran-table td:nth-child(2) {
            width: 105px;
        }

        .pengeluaran-table th:nth-child(3),
        .pengeluaran-table td:nth-child(3) {
            width: 125px;
        }

        .pengeluaran-table th:nth-child(4),
        .pengeluaran-table td:nth-child(4) {
            width: 145px;
        }

        .pengeluaran-table th:nth-child(5),
        .pengeluaran-table td:nth-child(5) {
            width: 150px;
        }

        .pengeluaran-table th:nth-child(6),
        .pengeluaran-table td:nth-child(6) {
            width: 150px;
        }

        .pengeluaran-table th:nth-child(7),
        .pengeluaran-table td:nth-child(7) {
            width: 100px;
        }

        .pengeluaran-table th:nth-child(8),
        .pengeluaran-table td:nth-child(8) {
            width: 120px;
        }

        .pengeluaran-table th:nth-child(9),
        .pengeluaran-table td:nth-child(9) {
            width: 140px;
        }

        .pengeluaran-table th:nth-child(10),
        .pengeluaran-table td:nth-child(10) {
            width: 100px;
        }

        /* Header tabel */
        .pengeluaran-table th {
            white-space: nowrap;
            padding: 14px 12px;
            text-align: left;
        }

        /* Isi tabel */
        .pengeluaran-table td {
            padding: 16px 12px;
            vertical-align: middle;
            overflow-wrap: break-word;
        }

        /* Informasi yang tidak perlu terpotong */
        .pengeluaran-table .transaction-number,
        .pengeluaran-table .wo-number {
            overflow-wrap: anywhere;
        }

        .status-menunggu {
            color: #b7791f;
            background: #fff4dc;
        }

        .status-disetujui {
            color: #16834a;
            background: #e8f7ee;
        }

        .status-ditolak {
            color: #dc3545;
            background: #fdeaea;
        }

        .status-revisi {
            color: #2563a6;
            background: #e8f1ff;
        }

        #detailStatus {
            display: inline-block;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
    </style>


    <div class="pengeluaran-page">

        {{-- =========================
         HEADER
    ========================== --}}
        <div class="pengeluaran-header">

            <div class="header-actions">

                <button type="button" class="btn-primary" id="btnPengeluaranBaru">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M12 5v14"></path>
                        <path d="M5 12h14"></path>
                    </svg>
                    Pengeluaran Barang Baru
                </button>

            </div>

        </div>


        {{-- =========================
         SUMMARY
    ========================== --}}
        <div class="summary-grid">

            <div class="summary-card blue">

                <div class="summary-top">
                    <span class="summary-label">Total Pengeluaran</span>

                    <div class="summary-small-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17 17 7"></path>
                            <path d="M7 7h10v10"></path>
                        </svg>
                    </div>
                </div>

                <div class="summary-value">{{ $totalLaporan }}</div>

                <div class="summary-description">
                    transaksi WO
                </div>

            </div>


            <div class="summary-card orange">

                <div class="summary-top">
                    <span class="summary-label">WO Terlayani</span>

                    <div class="summary-small-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a4 4 0 0 0 5 5L13 18l-3-3 6.7-6.7a4 4 0 0 0-2-2z"></path>
                            <path d="M5 19l-2 2"></path>
                        </svg>
                    </div>
                </div>

                <div class="summary-value">{{ $totalMenunggu }}</div>

                <div class="summary-description">
                    paket WO aktif
                </div>

            </div>


            <div class="summary-card blue">

                <div class="summary-top">
                    <span class="summary-label">Sinkronisasi Maximo</span>

                    <div class="summary-small-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 11a8.1 8.1 0 0 0-14.9-4"></path>
                            <path d="M4 4v4h4"></path>
                            <path d="M4 13a8.1 8.1 0 0 0 14.9 4"></path>
                            <path d="M20 20v-4h-4"></path>
                        </svg>
                    </div>
                </div>

                <div class="summary-value">0</div>

                <div class="summary-description">
                    MIF connected
                </div>

            </div>

        </div>


        {{-- =========================
         FILTER
    ========================== --}}
        <div class="filter-card">

            <div class="filter-row">

                <div class="search-box">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>

                    <input type="text" placeholder="Cari No TRX, No WO Maximo, Teknisi, Item Part, Lokasi...">

                </div>


                <select class="filter-select">
                    <option>Semua Unit Pembangkit</option>
                    <option>Unit Surabaya</option>
                    <option>Unit Gresik</option>
                    <option>Unit Paiton</option>
                </select>


                <button type="button" class="btn-print">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 9V3h12v6"></path>
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                        <path d="M6 14h12v7H6z"></path>
                    </svg>

                    Cetak Rekap Pengeluaran

                </button>

            </div>

        </div>


        {{-- =========================
         TABLE
    ========================== --}}
        <div class="table-card">

            <div class="table-header">
                <h3>Data Laporan Ex Used</h3>
                <span>Daftar laporan Ex Used dari Worker dan Admin Gudang</span>
            </div>

            <div class="table-wrapper">
                <table class="pengeluaran-table">

                    <thead>
                        <tr>
                            <th>No. Laporan</th>
                            <th>Tanggal Laporan</th>
                            <th>Nomor WO</th>
                            <th>Unit / Bidang</th>
                            <th>Teknisi / Pelapor</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Kondisi Barang</th>
                            <th>Status Verifikasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($laporan as $item)
                            <tr>
                                {{-- NO. LAPORAN --}}
                                <td>
                                    <span class="transaction-number">
                                        RPT-{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>

                                {{-- TANGGAL LAPORAN --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d M Y') }}

                                    <span class="secondary-text">
                                        {{ $item->created_at?->format('H:i') ?? '-' }}
                                    </span>
                                </td>

                                {{-- NOMOR WO --}}
                                <td>
                                    <span class="wo-number">
                                        {{ $item->nomor_wo }}
                                    </span>
                                </td>

                                {{-- UNIT / BIDANG --}}
                                <td>
                                    <span class="material-name">
                                        {{ $item->status_lokasi ?? '-' }}
                                    </span>

                                    <span class="secondary-text">
                                        {{ ucfirst(str_replace('_', ' ', $item->bidang ?? '-')) }}
                                    </span>
                                </td>

                                {{-- TEKNISI / PELAPOR --}}
                                <td>
                                    <span class="material-name">
                                        {{ $item->user->name ?? '-' }}
                                    </span>

                                    <span class="secondary-text">
                                        Diinput oleh:
                                        {{ $item->pembuat->name ?? '-' }}
                                    </span>
                                </td>

                                {{-- NAMA BARANG --}}
                                <td>
                                    <span class="material-name">
                                        {{ $item->nama_barang }}
                                    </span>

                                    <span class="secondary-text">
                                        {{ $item->item_number }}
                                    </span>
                                </td>

                                {{-- JUMLAH --}}
                                <td>
                                    <span class="quantity">
                                        {{ $item->jumlah_barang }}
                                        {{ strtoupper($item->satuan) }}
                                    </span>
                                </td>

                                {{-- KONDISI BARANG --}}
                                <td>
                                    <span class="rack-badge">
                                        @switch($item->status_kondisi)
                                            @case('rusak')
                                                Rusak
                                            @break

                                            @case('repairable')
                                                Repairable
                                            @break

                                            @case('kehilangan_fungsi')
                                                Kehilangan Fungsi
                                            @break

                                            @default
                                                -
                                        @endswitch
                                    </span>
                                </td>

                                {{-- STATUS VERIFIKASI --}}
                                <td>
                                    @if ($item->status_laporan === 'Menunggu Verifikasi')
                                        <span class="sync-badge" style="background:#fff7e6;color:#b7791f;">
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif ($item->status_laporan === 'Disetujui')
                                        <span class="sync-badge" style="background:#e6f7ed;color:#16834a;">
                                            Disetujui
                                        </span>
                                    @else
                                        <span class="sync-badge" style="background:#fdeaea;color:#c53030;">
                                            Ditolak
                                        </span>
                                    @endif

                                    <span class="secondary-text">
                                        {{ $item->sumber_input === 'admin' ? 'Input Admin' : 'Input Worker' }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td>
                                    <button type="button" class="action-link"
                                        data-report="{{ json_encode([
                                            'id' => $item->id,
                                            'tanggal_laporan' => $item->tanggal_laporan,
                                            'nomor_wo' => $item->nomor_wo,
                                            'status_lokasi' => $item->status_lokasi,
                                            'bidang' => $item->bidang,
                                            'nama_worker' => $item->user->name ?? '-',
                                            'nama_pembuat' => $item->pembuat->name ?? '-',
                                            'nama_pekerjaan' => $item->nama_pekerjaan,
                                            'item_number' => $item->item_number,
                                            'nama_barang' => $item->nama_barang,
                                            'jenis_pengembalian' => $item->jenis_pengembalian,
                                            'kondisi_barang' => $item->kondisi_barang,
                                            'material_base' => $item->material_base,
                                            'lokasi_gudang' => $item->lokasi_gudang,
                                            'jumlah_barang' => $item->jumlah_barang,
                                            'satuan' => $item->satuan,
                                            'estimasi' => $item->estimasi,
                                            'status_kondisi' => $item->status_kondisi,
                                            'foto_bukti' => $item->foto_bukti ? asset('storage/' . $item->foto_bukti) : '',
                                            'deskripsi' => $item->deskripsi,
                                            'tanda_tangan' => $item->tanda_tangan,
                                            'status_laporan' => $item->status_laporan,
                                            'sumber_input' => $item->sumber_input,
                                            'alasan_penolakan' => $item->alasan_penolakan,
                                        ]) }}"
                                        onclick="openPengeluaranDetail(this)">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align:center;padding:30px;">
                                        Belum ada laporan Ex Used.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="table-footer">

                    <div class="table-info">
                        @if ($laporan->total() > 0)
                            Menampilkan {{ $laporan->firstItem() }}
                            - {{ $laporan->lastItem() }}
                            dari {{ $laporan->total() }} laporan Ex Used
                        @else
                            Belum ada laporan Ex Used
                        @endif
                    </div>

                    @if ($laporan->hasPages())
                        <div class="pagination">

                            {{-- PREVIOUS --}}
                            @if ($laporan->onFirstPage())
                                <button class="page-btn" disabled>&laquo;</button>
                            @else
                                <a href="{{ $laporan->previousPageUrl() }}" class="page-btn"
                                    style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    &laquo;
                                </a>
                            @endif

                            {{-- NOMOR HALAMAN --}}
                            @foreach ($laporan->getUrlRange(1, $laporan->lastPage()) as $page => $url)
                                <a href="{{ $url }}"
                                    class="page-btn {{ $laporan->currentPage() == $page ? 'active' : '' }}"
                                    style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    {{ $page }}
                                </a>
                            @endforeach

                            {{-- NEXT --}}
                            @if ($laporan->hasMorePages())
                                <a href="{{ $laporan->nextPageUrl() }}" class="page-btn"
                                    style="display:flex;align-items:center;justify-content:center;text-decoration:none;">
                                    &raquo;
                                </a>
                            @else
                                <button class="page-btn" disabled>&raquo;</button>
                            @endif

                        </div>
                    @endif

                </div>

            </div>

        </div>

        </div>

        {{-- =====================================================
         MODAL PENGELUARAN BARANG BARU
         ===================================================== --}}

        <div class="pengeluaran-modal-overlay" id="pengeluaranModal">

            <div class="pengeluaran-modal">

                {{-- HEADER MODAL --}}
                <div class="pengeluaran-modal-header">

                    <div>
                        <h2>Formulir Pengeluaran Barang</h2>

                        <p>
                            Input Ex Used
                        </p>
                    </div>

                    <button type="button" class="pengeluaran-modal-close" id="closePengeluaranModal">
                        &times;
                    </button>

                </div>


                {{-- BODY --}}
                <div class="pengeluaran-modal-body">

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-600">
                            <strong>Periksa kembali data yang dimasukkan:</strong>
                            <ul class="mt-2 list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="formPengeluaranBarang" action="{{ route('pengeluaranBarang.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="pengeluaran-form-grid">

                            {{-- WORKER / PEMOHON --}}
                            <div class="pengeluaran-form-group full">
                                <label>
                                    Teknisi / Worker Pemohon
                                    <span class="pengeluaran-required">*</span>
                                </label>

                                <input type="text" name="user_id" class="pengeluaran-select"
                                    placeholder="Masukkan Nama Worker" required>
                                {{-- <option value="">Pilih Worker</option>

                                    @foreach ($daftarWorker as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ old('user_id') == $worker->id ? 'selected' : '' }}>
                                            {{ $worker->name }} - {{ $worker->unit }}
                                        </option>
                                    @endforeach
                                </select> --}}
                            </div>

                            {{-- TANGGAL --}}
                            <div class="pengeluaran-form-group">
                                <label>Tanggal Laporan
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="date" name="tanggal_laporan" class="pengeluaran-input"
                                    value="{{ old('tanggal_laporan', date('Y-m-d')) }}" required>
                            </div>

                            {{-- BIDANG --}}
                            <div class="pengeluaran-form-group">
                                <label>Bidang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="bidang" class="pengeluaran-select" required>
                                    <option value="">Pilih Bidang</option>
                                    <option value="mekanik">Mekanik</option>
                                    <option value="listrik">Listrik</option>
                                    <option value="controlling_instrumen">Controlling Instrumen</option>
                                    <option value="gudang">Gudang</option>
                                </select>
                            </div>

                            {{-- NOMOR WO --}}
                            <div class="pengeluaran-form-group">
                                <label>Nomor Work Order (WO)
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="text" name="nomor_wo" class="pengeluaran-input"
                                    value="{{ old('nomor_wo') }}" placeholder="Masukkan nomor WO" required>
                            </div>

                            {{-- NAMA PEKERJAAN --}}
                            <div class="pengeluaran-form-group">
                                <label>Nama Pekerjaan
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="text" name="nama_pekerjaan" class="pengeluaran-input"
                                    value="{{ old('nama_pekerjaan') }}" placeholder="Masukkan nama pekerjaan" required>
                            </div>

                            {{-- ITEM NUMBER --}}
                            <div class="pengeluaran-form-group">
                                <label>Item Number
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="text" name="item_number" class="pengeluaran-input"
                                    value="{{ old('item_number') }}" placeholder="Masukkan item number" required>
                            </div>

                            {{-- NAMA BARANG --}}
                            <div class="pengeluaran-form-group">
                                <label>Nama Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="text" name="nama_barang" class="pengeluaran-input"
                                    value="{{ old('nama_barang') }}" placeholder="Masukkan nama barang" required>
                            </div>

                            {{-- JENIS PENGEMBALIAN --}}
                            <div class="pengeluaran-form-group">
                                <label>Jenis Pengembalian
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="jenis_pengembalian" class="pengeluaran-select" required>
                                    <option value="">Pilih Jenis</option>
                                    <option value="barang_lebihan">Barang Kelebihan</option>
                                    <option value="barang_pasca_pakai">Barang Pasca Pakai</option>
                                </select>
                            </div>

                            {{-- KONDISI BARANG --}}
                            <div class="pengeluaran-form-group">
                                <label>Kondisi Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="kondisi_barang" class="pengeluaran-select" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="investasi">Inventaris</option>
                                    <option value="operasi">Operasi</option>
                                </select>
                            </div>

                            {{-- MATERIAL BASE --}}
                            <div class="pengeluaran-form-group">
                                <label>Material Base
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="material_base" class="pengeluaran-select" required>
                                    <option value="">Pilih Material</option>
                                    <option value="besi/baja">Besi/Baja</option>
                                    <option value="aluminium">Aluminium</option>
                                    <option value="tembaga">Tembaga</option>
                                    <option value="plastik">Plastik</option>
                                    <option value="campuran">Campuran</option>
                                </select>
                            </div>

                            {{-- LOKASI GUDANG --}}
                            <div class="pengeluaran-form-group">
                                <label>Lokasi Gudang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="text" name="lokasi_gudang" class="pengeluaran-input"
                                    value="{{ old('lokasi_gudang') }}" placeholder="Masukkan lokasi gudang" required>
                            </div>

                            {{-- JUMLAH --}}
                            <div class="pengeluaran-form-group">
                                <label>Jumlah Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="number" name="jumlah_barang" class="pengeluaran-input" min="1"
                                    value="{{ old('jumlah_barang', 1) }}" required>
                            </div>

                            {{-- SATUAN --}}
                            <div class="pengeluaran-form-group">
                                <label>Satuan
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="satuan" class="pengeluaran-select" required>
                                    <option value="">Pilih Satuan</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="unit">Unit</option>
                                    <option value="meter">Meter</option>
                                    <option value="set">Set</option>
                                    <option value="buah">Buah</option>
                                </select>
                            </div>

                            {{-- ESTIMASI --}}
                            <div class="pengeluaran-form-group">
                                <label>Estimasi Nilai Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="number" name="estimasi" class="pengeluaran-input" min="0"
                                    step="0.01" value="{{ old('estimasi') }}" placeholder="Masukkan estimasi nilai"
                                    required>
                            </div>

                            {{-- STATUS KONDISI --}}
                            <div class="pengeluaran-form-group">
                                <label>Status Kondisi Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <select name="status_kondisi" class="pengeluaran-select" required>
                                    <option value="">Pilih Kondisi</option>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="repairable">Repairable</option>
                                    <option value="kehilangan_fungsi">Kehilangan Fungsi</option>
                                </select>
                            </div>

                            {{-- FOTO BUKTI --}}
                            <div class="pengeluaran-form-group full">
                                <label>Foto Bukti Barang
                                    <span class="pengeluaran-required">*</span>
                                </label>
                                <input type="file" name="foto_bukti" class="pengeluaran-input" accept=".jpg,.jpeg,.png"
                                    required>
                                <small>Format JPG, JPEG, atau PNG. Maksimal 5 MB.</small>
                            </div>

                            {{-- DESKRIPSI --}}
                            <div class="pengeluaran-form-group full">
                                <label>Deskripsi / Keterangan</label>
                                <textarea name="deskripsi" class="pengeluaran-textarea"
                                    placeholder="Jelaskan kondisi barang dan keterangan lainnya...">{{ old('deskripsi') }}</textarea>
                            </div>

                            {{-- TANDA TANGAN WORKER --}}
                            <div class="pengeluaran-form-group full">
                                <label>Tanda Tangan Worker *</label>

                                <div class="signature-input-wrapper">
                                    <canvas id="signatureCanvas"></canvas>
                                </div>

                                <input type="hidden" name="tanda_tangan" id="tandaTanganInput">

                                <div class="signature-input-footer">
                                    <small>Silakan bubuhkan tanda tangan pada area di atas.</small>

                                    <button type="button" id="clearSignature">
                                        <i class="fas fa-eraser"></i>
                                        Hapus TTD
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                {{-- FOOTER FORM INPUT ADMIN --}}
                <div class="detail-report-footer">

                    <button type="button" class="report-btn-close"
                        onclick="document.getElementById('pengeluaranModal').classList.remove('show'); document.body.style.overflow = '';">
                        Tutup
                    </button>

                    <button type="submit" form="formPengeluaranBarang" class="report-btn-approve"
                        id="savePengeluaranBarang">
                        Simpan Laporan
                    </button>

                </div>

            </div>

        </div>


        {{-- JAVASCRIPT MODAL --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const modal = document.getElementById('pengeluaranModal');
                const openButton = document.getElementById('btnPengeluaranBaru');
                const closeButton = document.getElementById('closePengeluaranModal');
                const cancelButton = document.getElementById('cancelPengeluaranModal');
                const saveButton = document.getElementById('savePengeluaranBarang');


                /* OPEN MODAL */

                if (openButton) {

                    openButton.addEventListener('click', function() {
                        modal.classList.add('show');
                        document.body.style.overflow = 'hidden';

                        // Atur ukuran canvas setelah modal tampil
                        requestAnimationFrame(function() {
                            initSignatureCanvas();
                        });
                    });

                }


                /* CLOSE MODAL */

                function closeModal() {

                    modal.classList.remove('show');

                    document.body.style.overflow = '';

                }


                if (closeButton) {

                    closeButton.addEventListener('click', closeModal);

                }


                if (cancelButton) {

                    cancelButton.addEventListener('click', closeModal);

                }


                /* CLICK OUTSIDE */

                if (modal) {

                    modal.addEventListener('click', function(event) {

                        if (event.target === modal) {

                            closeModal();

                        }

                    });

                }


                /* ESCAPE */

                document.addEventListener('keydown', function(event) {

                    if (event.key === 'Escape' && modal.classList.contains('show')) {

                        closeModal();

                    }

                });


                /* SIMPAN */

                if (saveButton) {

                    saveButton.addEventListener('click', function() {

                        alert(
                            'Data pengeluaran barang berhasil disimpan dan siap disinkronkan ke Maximo.'
                        );

                        closeModal();

                    });

                }

            });
        </script>

        {{-- =====================================================
     MODAL DETAIL & CETAK LAPORAN PENGELUARAN
===================================================== --}}
        <div class="pengeluaran-detail-overlay" id="pengeluaranDetailModal">

            <div class="pengeluaran-detail-modal">

                {{-- HEADER --}}
                <div class="detail-report-header">

                    <div class="detail-report-brand">
                        <div>
                            <strong>Laporan Pengeluaran Barang</strong>
                            <span>Goods Issue Work Order</span>
                        </div>
                    </div>

                    <button type="button" class="detail-report-close" onclick="closePengeluaranDetail()">
                        &times;
                    </button>

                </div>

                {{-- BODY --}}
                <div class="detail-report-body">

                    {{-- IDENTITAS LAPORAN --}}
                    <div class="report-info-top">

                        <div class="report-number-box">
                            <span>No. Laporan</span>
                            <strong id="detailTransaction">
                                -
                            </strong>
                        </div>

                        <div class="report-date-box">
                            <span>Tanggal Laporan</span>
                            <strong id="detailDate">
                                -
                            </strong>
                        </div>

                        <div class="report-status-box">
                            <span>Status Laporan</span>

                            <strong id="detailStatus" class="status-badge">
                                -
                            </strong>
                        </div>

                    </div>


                    {{-- SECTION 1 --}}
                    <div class="report-section">

                        <div class="report-section-title">
                            <span class="report-step">
                                1
                            </span>

                            <div>
                                <h3>Informasi Work Order</h3>
                                <p>Informasi pekerjaan dan pemohon pengeluaran</p>
                            </div>
                        </div>


                        <div class="report-grid">

                            <div class="report-field">
                                <span>Nomor Work Order (WO)</span>
                                <strong id="detailWO">
                                    -
                                </strong>
                            </div>

                            <div class="report-field">
                                <span>Jenis Pengembalian</span>
                                <strong id="detailMaintenance">
                                    -
                                </strong>
                            </div>

                            <div class="report-field">
                                <span>Unit</span>
                                <strong id="detailUnit">
                                    -
                                </strong>
                            </div>

                            <div class="report-field">
                                <span>Lokasi Gudang</span>
                                <strong id="detailLocation">
                                    -
                                </strong>
                            </div>

                            <div class="report-field">
                                <span>Teknisi / Worker</span>
                                <strong id="detailTechnician">
                                    -
                                </strong>
                            </div>

                            <div class="report-field">
                                <span>Admin yang menginput</span>
                                <strong id="detailWarehouseOfficer">
                                    -
                                </strong>
                            </div>

                        </div>

                    </div>


                    {{-- SECTION 2 --}}
                    <div class="report-section">

                        <div class="report-section-title">
                            <span class="report-step">
                                2
                            </span>

                            <div>
                                <h3>Detail Barang Ex Used</h3>
                                <p>Material yang dikeluarkan dari gudang</p>
                            </div>
                        </div>


                        <div class="material-report-card">

                            <div class="material-report-main">

                                <span class="material-code" id="detailMaterialCode">
                                    -
                                </span>

                                <h4 id="detailMaterial">
                                    -
                                </h4>

                                <p id="detailSpecification">
                                    -
                                </p>

                            </div>


                            <div class="material-report-grid">

                                <div>
                                    <span>Jumlah Barang</span>
                                    <strong id="detailQuantity">
                                        -
                                    </strong>
                                </div>

                                <div>
                                    <span>Kondisi Barang</span>
                                    <strong id="detailRack">
                                        -
                                    </strong>
                                </div>

                                <div>
                                    <span>Estimasi</span>
                                    <strong id="detailPrice">
                                        -
                                    </strong>
                                </div>

                                <div>
                                    <span>Status Kondisi</span>
                                    <strong id="detailTotal">
                                        -
                                    </strong>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SECTION 3 --}}
                    <div class="report-section">

                        <div class="report-section-title">
                            <span class="report-step">
                                3
                            </span>

                            <div>
                                <h3>Bukti & Catatan Pengeluaran</h3>
                                <p>Bukti material dan informasi tambahan</p>
                            </div>
                        </div>


                        <div class="evidence-report-grid">

                            <div>

                                <span class="report-label">
                                    Foto / Bukti Material
                                </span>

                                <div class="report-photo-box">

                                    <img id="detailEvidencePhoto" alt="Bukti material" style="display: none;">

                                    <div class="report-photo-placeholder">
                                        📷
                                        <span>Foto bukti tidak tersedia</span>
                                    </div>

                                </div>

                            </div>


                            <div>

                                <span class="report-label">
                                    Deskripsi Laporan
                                </span>

                                <div class="report-description" id="detailNote">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- SECTION 4 --}}
                    <div class="report-section">

                        <div class="report-section-title">
                            <span class="report-step">
                                4
                            </span>

                            <div>
                                <h3>Konfirmasi & Tanda Tangan</h3>
                                <p>Konfirmasi digital dari pemohon pengeluaran</p>
                            </div>
                        </div>


                        <div class="signature-report-wrapper">

                            <div class="signature-report-box">

                                <img id="detailSignature" alt="Tanda tangan digital" style="display: none;">

                                <div class="signature-report-placeholder">
                                    ✍
                                    <span>Tanda tangan belum tersedia</span>
                                </div>

                            </div>


                            <div class="signature-report-info">

                                <span>Tanda tangan digital</span>

                                <strong id="detailSigner">
                                    -
                                </strong>

                                <small id="detailSignerRole">
                                    -
                                </small>

                                <small id="detailSignatureDate">
                                    -
                                </small>

                            </div>

                        </div>


                        <div class="report-agreement">

                            <div class="agreement-icon">
                                ✓
                            </div>

                            <p>
                                Saya menyatakan bahwa data pengeluaran barang
                                yang tercantum pada laporan ini telah diperiksa
                                dan dapat dipertanggungjawabkan.
                            </p>

                        </div>

                    </div>

                </div>

                {{-- FOOTER MODAL DETAIL --}}
                <div class="detail-report-footer">

                    <div class="detail-report-actions" id="detailVerificationActions">

                        <button type="button" class="report-btn-approve" onclick="openVerificationForm('approve')">
                            Setujui
                        </button>

                        <button type="button" class="report-btn-revision" onclick="openVerificationForm('revision')">
                            Revisi
                        </button>

                        <button type="button" class="report-btn-reject" onclick="openVerificationForm('reject')">
                            Tolak
                        </button>

                    </div>
                    <button type="button" class="report-btn-close" onclick="closePengeluaranDetail()">
                        Tutup
                    </button>

                    <button type="button" class="report-btn-print" onclick="printPengeluaranReport()">
                        Cetak Laporan
                    </button>

                </div>

            </div>

        </div>

        <div class="verification-overlay" id="verificationModal">
            <div class="verification-modal">

                <div class="verification-header">
                    <div>
                        <h3 id="verificationTitle">Verifikasi Laporan</h3>
                        <p id="verificationSubtitle">Periksa kembali laporan sebelum melanjutkan.</p>
                    </div>

                    <button type="button" class="verification-close" onclick="closeVerificationModal()">&times;</button>
                </div>

                <div class="verification-body">

                    <div id="approvalSignatureSection" style="display:none;">
                        <label class="verification-label">
                            Tanda Tangan Digital Admin Gudang
                        </label>

                        <div class="admin-signature-wrapper">
                            <canvas id="adminSignatureCanvas"></canvas>
                        </div>

                        <div class="signature-hint">
                            <span>✍</span>
                            Bubuhkan tanda tangan pada area di atas.
                        </div>

                        <button type="button" class="signature-clear" onclick="clearAdminSignature()">
                            Hapus Tanda Tangan
                        </button>
                    </div>

                    <div id="verificationReasonSection" style="display:none;">
                        <label class="verification-label" for="verificationReason">
                            Alasan
                        </label>

                        <textarea id="verificationReason" class="verification-textarea"
                            placeholder="Tuliskan alasan revisi atau penolakan laporan..." rows="5"></textarea>

                        <small class="verification-hint">
                            Alasan wajib diisi sebelum laporan diproses.
                        </small>
                    </div>

                </div>

                <div class="verification-footer">
                    <button type="button" class="verification-cancel" onclick="closeVerificationModal()">
                        Batal
                    </button>

                    <button type="button" class="verification-submit" id="verificationSubmitButton"
                        onclick="submitVerification()">
                        Konfirmasi
                    </button>
                </div>

            </div>
        </div>

        <style>
            /* MODAL VERIFIKASI ADMIN */
            .verification-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 31, 55, .58);
                display: none;
                align-items: center;
                justify-content: center;
                padding: 20px;
                z-index: 10001;
            }

            .verification-overlay.show {
                display: flex;
            }

            .verification-modal {
                width: 100%;
                max-width: 480px;
                background: #fff;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 15px 45px rgba(0, 0, 0, .18);
                animation: verificationAppear .2s ease;
            }

            @keyframes verificationAppear {
                from {
                    opacity: 0;
                    transform: translateY(12px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .verification-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 22px 24px;
                border-bottom: 1px solid #edf0f4;
            }

            .verification-header h3 {
                margin: 0;
                color: #173d50;
                font-size: 18px;
                font-weight: 700;
            }

            .verification-header p {
                margin: 6px 0 0;
                color: #718096;
                font-size: 12px;
            }

            .verification-close {
                border: none;
                background: #f1f5f9;
                color: #64748b;
                width: 30px;
                height: 30px;
                border-radius: 8px;
                font-size: 22px;
                cursor: pointer;
            }

            .verification-body {
                padding: 24px;
            }

            .verification-label {
                display: block;
                margin-bottom: 10px;
                color: #173d50;
                font-size: 13px;
                font-weight: 700;
            }

            .admin-signature-wrapper {
                width: 100%;
                height: 160px;
                border: 1.5px dashed #9acbd8;
                border-radius: 10px;
                overflow: hidden;
                background: #f8fcfd;
            }

            #adminSignatureCanvas {
                width: 100%;
                height: 180px;
                display: block;
                touch-action: none;
                cursor: crosshair;
            }

            .signature-hint,
            .verification-hint {
                display: block;
                margin-top: 9px;
                color: #8493a2;
                font-size: 11px;
            }

            .signature-clear {
                margin-top: 12px;
                padding: 8px 12px;
                background: #eef8fb;
                color: #087c9c;
                border: 1px solid #c9eaf1;
                border-radius: 7px;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
            }

            .verification-textarea {
                width: 100%;
                padding: 12px;
                border: 1px solid #dce5eb;
                border-radius: 9px;
                outline: none;
                resize: vertical;
                font-family: inherit;
                font-size: 13px;
                color: #334155;
            }

            .verification-textarea:focus {
                border-color: #16a6c5;
                box-shadow: 0 0 0 3px rgba(22, 166, 197, .1);
            }

            .verification-footer {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                padding: 16px 24px;
                border-top: 1px solid #edf0f4;
                background: #fbfdfe;
            }

            .verification-cancel,
            .verification-submit {
                padding: 10px 17px;
                border-radius: 8px;
                font-family: inherit;
                font-size: 12px;
                font-weight: 600;
                cursor: pointer;
            }

            .verification-cancel {
                background: white;
                border: 1px solid #dce5eb;
                color: #64748b;
            }

            .verification-submit {
                background: #087c9c;
                border: 1px solid #087c9c;
                color: white;
            }

            .verification-submit:hover {
                background: #066782;
            }

            /* =====================================================
                                                                                                                                                                                                                                                                                                                                                                                                                DETAIL & CETAK LAPORAN
                                                                                                                                                                                                                                                                                                                                                                                                                ===================================================== */

            .pengeluaran-detail-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 31, 55, .58);
                display: none;
                align-items: center;
                justify-content: center;
                padding: 25px;
                z-index: 9999;
            }

            .pengeluaran-detail-overlay.show {
                display: flex;
            }

            .pengeluaran-detail-modal {
                width: 100%;
                max-width: 920px;
                max-height: 92vh;
                background: #f8fafc;
                border-radius: 16px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(15, 23, 42, .25);
                display: flex;
                flex-direction: column;
            }


            /* HEADER */

            .detail-report-header {
                min-height: 78px;
                background: #ffffff;
                border-bottom: 1px solid #e4ebf3;
                padding: 17px 22px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 20px;
            }

            .detail-report-brand {
                display: flex;
                align-items: center;
                gap: 10px;
                min-width: 210px;
            }

            .detail-report-brand strong {
                display: block;
                color: #172b4d;
                font-size: 16px;
                line-height: 1;
            }

            .detail-report-brand span {
                display: block;
                margin-top: 4px;
                color: #7890ae;
                font-size: 8px;
            }

            .detail-report-title {
                text-align: center;
                flex: 1;
            }

            .detail-report-title h2 {
                margin: 0;
                color: #172b4d;
                font-size: 17px;
                font-weight: 700;
            }

            .detail-report-title p {
                margin: 4px 0 0;
                color: #7890ae;
                font-size: 10px;
            }

            .detail-report-close {
                width: 34px;
                height: 34px;
                border: none;
                background: #f1f5f9;
                color: #68778c;
                border-radius: 8px;
                font-size: 22px;
                cursor: pointer;
            }


            /* BODY */

            .detail-report-body {
                padding: 22px;
                overflow-y: auto;
            }


            /* INFO TOP */

            .report-info-top {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 12px;
                margin-bottom: 20px;
            }

            .report-number-box,
            .report-date-box,
            .report-status-box {
                background: #ffffff;
                border: 1px solid #e1e9f2;
                border-radius: 10px;
                padding: 12px 14px;
            }

            .report-number-box span,
            .report-date-box span,
            .report-status-box span {
                display: block;
                color: #879ab0;
                font-size: 9px;
                margin-bottom: 5px;
            }

            .report-number-box strong {
                color: #0874c9;
                font-size: 11px;
            }

            .report-date-box strong {
                color: #172b4d;
                font-size: 11px;
            }

            .report-status-box strong {
                display: inline-block;
                padding: 5px 12px;
                border-radius: 6px;
            }

            .report-status-dot {
                width: 6px;
                height: 6px;
                background: #0cb98a;
                border-radius: 50%;
            }


            /* SECTION */

            .report-section {
                background: #ffffff;
                border: 1px solid #e1e9f2;
                border-radius: 12px;
                padding: 17px;
                margin-bottom: 15px;
            }

            .report-section-title {
                display: flex;
                align-items: center;
                gap: 10px;
                padding-bottom: 13px;
                margin-bottom: 15px;
                border-bottom: 1px solid #edf1f6;
            }

            .report-step {
                width: 27px;
                height: 27px;
                border-radius: 8px;
                background: #eaf4ff;
                color: #0874c9;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 700;
            }

            .report-section-title h3 {
                margin: 0;
                color: #172b4d;
                font-size: 13px;
                font-weight: 700;
            }

            .report-section-title p {
                margin: 3px 0 0;
                color: #879ab0;
                font-size: 9px;
            }


            /* REPORT GRID */

            .report-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 14px 20px;
            }

            .report-field span {
                display: block;
                color: #879ab0;
                font-size: 9px;
                margin-bottom: 5px;
            }

            .report-field strong {
                color: #263b5b;
                font-size: 11px;
                font-weight: 600;
            }


            /* MATERIAL */

            .material-report-card {
                border: 1px solid #dce7f2;
                background: #f8fafc;
                border-radius: 10px;
                padding: 14px;
            }

            .material-report-main {
                margin-bottom: 15px;
            }

            .material-code {
                color: #0874c9;
                font-size: 9px;
                font-weight: 700;
            }

            .material-report-main h4 {
                margin: 5px 0 3px;
                color: #172b4d;
                font-size: 12px;
                font-weight: 700;
            }

            .material-report-main p {
                margin: 0;
                color: #879ab0;
                font-size: 9px;
            }

            .material-report-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                padding-top: 13px;
                border-top: 1px solid #e5ebf2;
            }

            .material-report-grid span {
                display: block;
                color: #879ab0;
                font-size: 9px;
                margin-bottom: 4px;
            }

            .material-report-grid strong {
                color: #172b4d;
                font-size: 10px;
            }


            /* EVIDENCE */

            .evidence-report-grid {
                display: grid;
                grid-template-columns: 230px 1fr;
                gap: 18px;
            }

            .report-label {
                display: block;
                color: #263b5b;
                font-size: 10px;
                font-weight: 600;
                margin-bottom: 7px;
            }

            .report-photo-box {
                width: 100%;
                height: 150px;
                border: 1px solid #e5eaf0;
                border-radius: 9px;
                background: #f6f8fa;
                overflow: hidden;
                position: relative;
            }

            .report-photo-box img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                display: block;
            }

            .report-photo-placeholder {
                display: none;
                width: 100%;
                height: 100%;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                color: #8a98ad;
                font-size: 22px;
            }

            .report-photo-placeholder span {
                margin-top: 5px;
                font-size: 9px;
            }

            .report-description {
                min-height: 150px;
                padding: 12px 14px;
                border: 1px solid #e5eaf0;
                border-radius: 9px;
                background: #f8fafc;
                color: #5e7089;
                font-size: 10px;
                line-height: 1.7;
            }


            /* SIGNATURE */

            .signature-report-wrapper {
                display: flex;
                align-items: flex-end;
                gap: 18px;
            }

            .signature-report-box {
                width: 250px;
                height: 120px;
                border: 1px solid #e5eaf0;
                border-radius: 9px;
                background: #ffffff;
                overflow: hidden;
            }

            .signature-report-box img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                display: block;
            }

            .signature-report-placeholder {
                display: none;
                width: 100%;
                height: 100%;
                align-items: center;
                justify-content: center;
                flex-direction: column;
                color: #8a98ad;
                font-size: 25px;
            }

            .signature-report-placeholder span {
                font-size: 9px;
                margin-top: 5px;
            }

            .signature-report-info {
                display: flex;
                flex-direction: column;
                gap: 4px;
                padding-bottom: 4px;
            }

            .signature-report-info span {
                color: #879ab0;
                font-size: 9px;
            }

            .signature-report-info strong {
                color: #172b4d;
                font-size: 11px;
            }

            .signature-report-info small {
                color: #68778c;
                font-size: 9px;
            }


            /* AGREEMENT */

            .report-agreement {
                display: flex;
                align-items: flex-start;
                gap: 9px;
                margin-top: 15px;
                padding: 11px 13px;
                background: #f2fbf5;
                border: 1px solid #d9eee2;
                border-radius: 8px;
            }

            .agreement-icon {
                width: 19px;
                height: 19px;
                border-radius: 5px;
                background: #18a957;
                color: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 10px;
                font-weight: 700;
            }

            .report-agreement p {
                margin: 1px 0 0;
                color: #41624f;
                font-size: 9px;
                line-height: 1.6;
            }


            /* FOOTER */

            .detail-report-footer {
                background: #ffffff;
                border-top: 1px solid #e4ebf3;
                padding: 13px 20px;
                display: flex;
                justify-content: flex-end;
                gap: 9px;
            }

            .report-btn-close,
            .report-btn-print {
                height: 34px;
                padding: 0 14px;
                border-radius: 8px;
                font-family: inherit;
                font-size: 10px;
                font-weight: 600;
                cursor: pointer;
            }

            .report-btn-close {
                border: 1px solid #dbe5ef;
                background: #ffffff;
                color: #526b87;
            }

            .report-btn-print {
                border: none;
                background: #1653eb;
                color: #ffffff;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }

            .report-btn-print svg {
                width: 15px;
                height: 15px;
            }


            /* RESPONSIVE */

            @media (max-width: 768px) {

                .pengeluaran-detail-overlay {
                    padding: 10px;
                }

                .report-info-top {
                    grid-template-columns: 1fr;
                }

                .report-grid {
                    grid-template-columns: 1fr;
                }

                .material-report-grid {
                    grid-template-columns: 1fr 1fr;
                }

                .evidence-report-grid {
                    grid-template-columns: 1fr;
                }

                .signature-report-wrapper {
                    flex-direction: column;
                    align-items: flex-start;
                }

            }


            /* PRINT */

            @media print {

                body * {
                    visibility: hidden !important;
                }

                .pengeluaran-detail-overlay,
                .pengeluaran-detail-overlay * {
                    visibility: visible !important;
                }

                .pengeluaran-detail-overlay {
                    position: absolute;
                    inset: 0;
                    background: #ffffff;
                    padding: 0;
                    display: flex !important;
                }

                .pengeluaran-detail-modal {
                    max-width: none;
                    max-height: none;
                    width: 100%;
                    box-shadow: none;
                    border-radius: 0;
                }

                .detail-report-body {
                    overflow: visible;
                }

                .detail-report-close,
                .detail-report-footer {
                    display: none !important;
                }

                @page {
                    size: A4;
                    margin: 12mm;
                }
            }

            .detail-report-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 10px;
            }

            .detail-report-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-right: auto;
            }

            .report-btn-approve,
            .report-btn-revision,
            .report-btn-reject {
                height: 36px;
                padding: 0 15px;
                border-radius: 8px;
                font-family: inherit;
                font-size: 11px;
                font-weight: 600;
                cursor: pointer;
                transition: .2s ease;
            }

            .report-btn-approve {
                background: #16834a;
                border: 1px solid #16834a;
                color: white;
            }

            .report-btn-approve:hover {
                background: #116b3b;
            }

            .report-btn-revision {
                border: 1px solid #e5a00000;
                background: #e5a000;
                color: white;
            }

            .report-btn-revision:hover {
                background: #d49000;
            }

            .report-btn-reject {
                border: 1px solid #dc354500;
                background: #dc3545;
                color: white;
            }

            .report-btn-reject:hover {
                background: #c82333;
            }

            @media (max-width: 700px) {
                .detail-report-actions {
                    width: 100%;
                    margin-right: 0;
                    flex-wrap: wrap;
                }
            }

            /* AREA TANDA TANGAN */
            .signature-input-wrapper {
                width: 100%;
                height: 150px;
                background: #f8fafc;
                border: 1px solid #d5e1ef;
                border-radius: 9px;
                overflow: hidden;
            }

            #signatureCanvas {
                display: block;
                width: 100%;
                height: 100%;
                cursor: crosshair;
                touch-action: none;
            }

            .signature-input-footer {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                margin-top: 8px;
            }

            .signature-input-footer small {
                color: #879ab0;
                font-size: 10px;
            }

            #clearSignature {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 12px;
                border: 1px solid #dc3545;
                border-radius: 7px;
                background: #fff;
                color: #dc3545;
                font-family: inherit;
                font-size: 10px;
                font-weight: 600;
                cursor: pointer;
            }

            #clearSignature:hover {
                background: #fff0f0;
            }
        </style>

        <script>
            let currentPengeluaranRow = null;


            function openPengeluaranDetail(button) {
                let data;

                try {
                    data = JSON.parse(button.dataset.report);
                    currentPengeluaranRow = data;
                } catch (error) {
                    console.error('Gagal membaca data laporan:', error);
                    alert('Data laporan tidak dapat dibaca.');
                    return;
                }

                const setText = (id, value) => {
                    const element = document.getElementById(id);

                    if (element) {
                        element.textContent = value ?? '-';
                    }
                };


                setText('detailStatus', data.status_laporan);

                // IDENTITAS LAPORAN
                setText(
                    'detailTransaction',
                    'RPT-' + String(data.id).padStart(6, '0')
                );

                setText('detailDate', data.tanggal_laporan ?? '-');

                // INFORMASI WO
                setText('detailWO', data.nomor_wo);
                setText('detailMaintenance', data.jenis_pengembalian);
                setText('detailUnit', data.status_lokasi);
                setText('detailLocation', data.lokasi_gudang);
                setText('detailTechnician', data.nama_worker);
                setText('detailWarehouseOfficer', data.nama_pembuat);

                // DETAIL BARANG
                setText('detailMaterialCode', data.item_number);
                setText('detailMaterial', data.nama_barang);
                setText('detailSpecification', data.material_base);

                setText(
                    'detailQuantity',
                    `${data.jumlah_barang ?? '-'} ${data.satuan ?? ''}`
                );

                setText('detailRack', data.kondisi_barang);
                setText('detailPrice', data.estimasi);
                setText('detailTotal', data.status_kondisi);

                // CATATAN
                setText('detailNote', data.deskripsi);

                // FOTO BUKTI
                const evidencePhoto = document.getElementById('detailEvidencePhoto');
                const evidencePlaceholder = document.querySelector('.report-photo-placeholder');

                if (evidencePhoto) {
                    if (data.foto_bukti) {
                        evidencePhoto.src = data.foto_bukti;
                        evidencePhoto.style.display = 'block';

                        if (evidencePlaceholder) {
                            evidencePlaceholder.style.display = 'none';
                        }
                    } else {
                        evidencePhoto.removeAttribute('src');
                        evidencePhoto.style.display = 'none';

                        if (evidencePlaceholder) {
                            evidencePlaceholder.style.display = 'flex';
                        }
                    }
                }

                // TANDA TANGAN WORKER
                const signature = document.getElementById('detailSignature');
                const signaturePlaceholder = document.querySelector('.signature-report-placeholder');

                if (signature) {
                    if (data.tanda_tangan) {
                        signature.src = data.tanda_tangan;
                        signature.style.display = 'block';

                        if (signaturePlaceholder) {
                            signaturePlaceholder.style.display = 'none';
                        }
                    } else {
                        signature.removeAttribute('src');
                        signature.style.display = 'none';

                        if (signaturePlaceholder) {
                            signaturePlaceholder.style.display = 'flex';
                        }
                    }
                }

                setText('detailSigner', data.nama_worker);
                setText('detailSignerRole', 'Teknisi / Worker');
                setText('detailSignatureDate', 'Tanda tangan pelapor');

                // STATUS LAPORAN DARI DATABASE
                // STATUS LAPORAN DAN WARNA
                const statusElement = document.querySelector('.report-status-box strong');

                if (statusElement) {
                    const status = (data.status_laporan || '-').toLowerCase().trim();

                    statusElement.textContent = data.status_laporan || '-';

                    // Reset warna sebelumnya
                    statusElement.style.color = '';

                    switch (status) {
                        case 'menunggu verifikasi':
                            statusElement.style.color = '#616161'; // Abu-Abu
                            statusElement.style.backgroundColor = '#f5f5f5';
                            break;

                        case 'ditolak':
                            statusElement.style.color = '#dc3545'; // Merah
                            statusElement.style.backgroundColor = '#fde8e8';
                            break;

                        case 'disetujui':
                            statusElement.style.color = '#16a34a'; // Hijau
                            statusElement.style.backgroundColor = '#dcfce7';
                            break;

                        case 'perlu revisi':
                        case 'revisi':
                            statusElement.style.color = '#f59e0b'; // Orange
                            statusElement.style.backgroundColor = '#fef3c7';
                            break;

                        default:
                            statusElement.style.color = '#616161';
                            statusElement.style.backgroundColor = '#f1f5f9';
                    }
                }

                // TOMBOL VERIFIKASI
                const verificationActions = document.getElementById(
                    'detailVerificationActions'
                );

                if (verificationActions) {
                    const isWorkerPending =
                        data.sumber_input === 'worker' &&
                        data.status_laporan === 'Menunggu Verifikasi';

                    verificationActions.style.display = isWorkerPending ? 'flex' : 'none';
                }

                // BUKA MODAL
                document.getElementById('pengeluaranDetailModal').classList.add('show');
                document.body.style.overflow = 'hidden';
            }


            function closePengeluaranDetail() {

                document.getElementById('pengeluaranDetailModal').classList.remove('show');
                document.body.style.overflow = '';
                currentPengeluaranRow = null;
            }


            // =====================================
            // VERIFIKASI LAPORAN ADMIN
            // =====================================

            let currentVerificationAction = null;
            let adminSignatureHasDrawing = false;
            let adminSignatureDrawing = false;

            const adminSignatureCanvas = document.getElementById('adminSignatureCanvas');
            const adminSignatureContext = adminSignatureCanvas ?
                adminSignatureCanvas.getContext('2d') :
                null;


            // Membuka modal verifikasi
            function openVerificationForm(action) {

                if (!currentPengeluaranRow || !currentPengeluaranRow.id) {
                    alert('Data laporan tidak ditemukan.');
                    return;
                }

                currentVerificationAction = action;

                const modal = document.getElementById('verificationModal');
                const title = document.getElementById('verificationTitle');
                const subtitle = document.getElementById('verificationSubtitle');
                const signatureSection = document.getElementById('approvalSignatureSection');
                const reasonSection = document.getElementById('verificationReasonSection');
                const submitButton = document.getElementById('verificationSubmitButton');

                if (!modal || !title || !subtitle || !signatureSection ||
                    !reasonSection || !submitButton) {
                    console.error('Elemen modal verifikasi tidak ditemukan.');
                    alert('Komponen verifikasi tidak lengkap.');
                    return;
                }

                signatureSection.style.display = 'none';
                reasonSection.style.display = 'none';

                document.getElementById('verificationReason').value = '';

                if (action === 'approve') {

                    title.textContent = 'Setujui Laporan';
                    subtitle.textContent = 'Berikan tanda tangan digital sebagai bukti verifikasi.';
                    signatureSection.style.display = 'block';
                    submitButton.textContent = 'Setujui Laporan';

                } else if (action === 'revision') {

                    title.textContent = 'Ajukan Revisi';
                    subtitle.textContent = 'Tuliskan bagian laporan yang perlu diperbaiki.';
                    reasonSection.style.display = 'block';
                    submitButton.textContent = 'Kirim Revisi';

                } else if (action === 'reject') {

                    title.textContent = 'Tolak Laporan';
                    subtitle.textContent = 'Tuliskan alasan penolakan laporan.';
                    reasonSection.style.display = 'block';
                    submitButton.textContent = 'Tolak Laporan';

                } else {
                    return;
                }

                modal.classList.add('show');
                document.body.style.overflow = 'hidden';

                if (action === 'approve') {
                    setTimeout(initAdminSignatureCanvas, 100);
                }
            }


            // Menyiapkan canvas tanda tangan Admin
            function initAdminSignatureCanvas() {

                if (!adminSignatureCanvas || !adminSignatureContext) return;

                const rect = adminSignatureCanvas.getBoundingClientRect();

                if (!rect.width || !rect.height) return;

                const ratio = window.devicePixelRatio || 1;

                adminSignatureCanvas.width = Math.round(rect.width * ratio);
                adminSignatureCanvas.height = Math.round(rect.height * ratio);

                adminSignatureContext.setTransform(ratio, 0, 0, ratio, 0, 0);

                adminSignatureContext.strokeStyle = '#173d50';
                adminSignatureContext.lineWidth = 2.5;
                adminSignatureContext.lineCap = 'round';
                adminSignatureContext.lineJoin = 'round';

                adminSignatureHasDrawing = false;
            }


            // Menghapus tanda tangan Admin
            function clearAdminSignature() {

                if (!adminSignatureCanvas || !adminSignatureContext) return;

                const rect = adminSignatureCanvas.getBoundingClientRect();

                adminSignatureContext.clearRect(0, 0, rect.width, rect.height);

                adminSignatureHasDrawing = false;
            }


            // Menggambar tanda tangan menggunakan mouse atau layar sentuh
            if (adminSignatureCanvas) {

                adminSignatureCanvas.addEventListener('pointerdown', function(event) {

                    adminSignatureDrawing = true;
                    adminSignatureHasDrawing = true;

                    const rect = this.getBoundingClientRect();

                    adminSignatureContext.beginPath();

                    adminSignatureContext.moveTo(
                        event.clientX - rect.left,
                        event.clientY - rect.top
                    );

                    this.setPointerCapture(event.pointerId);

                });

                adminSignatureCanvas.addEventListener('pointermove', function(event) {

                    if (!adminSignatureDrawing) return;

                    const rect = this.getBoundingClientRect();

                    adminSignatureContext.lineTo(
                        event.clientX - rect.left,
                        event.clientY - rect.top
                    );

                    adminSignatureContext.stroke();

                });

                function stopAdminSignature() {
                    adminSignatureDrawing = false;
                }

                adminSignatureCanvas.addEventListener('pointerup', stopAdminSignature);
                adminSignatureCanvas.addEventListener('pointercancel', stopAdminSignature);
                adminSignatureCanvas.addEventListener('lostpointercapture', stopAdminSignature);
            }


            // Menutup modal verifikasi
            function closeVerificationModal() {

                const modal = document.getElementById('verificationModal');

                if (modal) {
                    modal.classList.remove('show');
                }

                document.body.style.overflow = '';

                currentVerificationAction = null;
            }


            // Mengirim proses verifikasi
            function submitVerification() {

                if (!currentPengeluaranRow || !currentPengeluaranRow.id) {
                    alert('Data laporan tidak ditemukan.');
                    return;
                }

                const reportId = currentPengeluaranRow.id;

                let url = '';
                let alasan = '';

                if (currentVerificationAction === 'approve') {

                    if (!adminSignatureHasDrawing) {
                        alert('Silakan bubuhkan tanda tangan digital terlebih dahulu.');
                        return;
                    }

                    url = @json(route('pengeluaranBarang.setujui', ['id' => '__ID__']))
                        .replace('__ID__', reportId);

                } else if (currentVerificationAction === 'revision') {

                    alasan = document.getElementById('verificationReason').value.trim();

                    if (!alasan) {
                        alert('Alasan revisi wajib diisi.');
                        return;
                    }

                    url = @json(route('pengeluaranBarang.revisi', ['id' => '__ID__']))
                        .replace('__ID__', reportId);

                } else if (currentVerificationAction === 'reject') {

                    alasan = document.getElementById('verificationReason').value.trim();

                    if (!alasan) {
                        alert('Alasan penolakan wajib diisi.');
                        return;
                    }

                    url = @json(route('pengeluaranBarang.tolak', ['id' => '__ID__']))
                        .replace('__ID__', reportId);

                } else {
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ?.getAttribute('content');

                if (!csrfToken) {
                    alert('Token keamanan tidak ditemukan.');
                    return;
                }

                const form = document.createElement('form');

                form.method = 'POST';
                form.action = url;

                function addHiddenInput(name, value) {

                    const input = document.createElement('input');

                    input.type = 'hidden';
                    input.name = name;
                    input.value = value;

                    form.appendChild(input);
                }

                addHiddenInput('_token', csrfToken);
                addHiddenInput('_method', 'PATCH');

                if (currentVerificationAction === 'approve') {

                    addHiddenInput(
                        'tanda_tangan_admin',
                        adminSignatureCanvas.toDataURL('image/png')
                    );

                } else {

                    addHiddenInput('alasan_penolakan', alasan);

                }

                document.body.appendChild(form);

                form.submit();
            }



            function printPengeluaranReport() {
                window.print();
            }


            document.getElementById('pengeluaranDetailModal').addEventListener('click', function(event) {
                if (event.target === this) {
                    closePengeluaranDetail();
                }
            });


            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closePengeluaranDetail();
                }
            });

            // =====================================
            // CANVAS TANDA TANGAN WORKER
            // =====================================

            const signatureCanvas = document.getElementById('signatureCanvas');
            const signatureInput = document.getElementById('tandaTanganInput');
            const clearSignatureButton = document.getElementById('clearSignature');
            const signatureForm = document.getElementById('formPengeluaranBarang');

            let signatureContext = null;
            let signatureDrawing = false;
            let signatureHasDrawing = false;
            let signatureInitialized = false;

            function initSignatureCanvas() {
                if (!signatureCanvas || !signatureInput) return;

                const rect = signatureCanvas.getBoundingClientRect();

                // Tunggu sampai modal benar-benar terlihat
                if (!rect.width || !rect.height) return;

                const ratio = window.devicePixelRatio || 1;

                // Simpan gambar sebelum ukuran canvas diubah
                const previousImage = signatureHasDrawing ?
                    signatureCanvas.toDataURL('image/png') :
                    null;

                signatureCanvas.width = Math.round(rect.width * ratio);
                signatureCanvas.height = Math.round(rect.height * ratio);

                signatureContext = signatureCanvas.getContext('2d');

                signatureContext.setTransform(ratio, 0, 0, ratio, 0, 0);
                signatureContext.lineWidth = 2.5;
                signatureContext.lineCap = 'round';
                signatureContext.lineJoin = 'round';
                signatureContext.strokeStyle = '#173d50';

                if (previousImage) {
                    const image = new Image();

                    image.onload = function() {
                        signatureContext.drawImage(
                            image,
                            0,
                            0,
                            rect.width,
                            rect.height
                        );

                        signatureInput.value = signatureCanvas.toDataURL('image/png');
                    };

                    image.src = previousImage;
                }

                signatureInitialized = true;
            }

            // Ambil posisi pointer sesuai ukuran visual canvas
            function getSignaturePosition(event) {
                const rect = signatureCanvas.getBoundingClientRect();

                return {
                    x: event.clientX - rect.left,
                    y: event.clientY - rect.top
                };
            }

            // Mulai menggambar
            if (signatureCanvas) {
                signatureCanvas.addEventListener('pointerdown', function(event) {
                    event.preventDefault();

                    if (!signatureInitialized) {
                        initSignatureCanvas();
                    }

                    if (!signatureContext) return;

                    signatureDrawing = true;
                    signatureCanvas.setPointerCapture(event.pointerId);

                    const position = getSignaturePosition(event);

                    signatureContext.beginPath();
                    signatureContext.moveTo(position.x, position.y);

                    // Membuat titik jika pengguna hanya mengetuk canvas
                    signatureContext.fillStyle = '#173d50';
                    signatureContext.beginPath();
                    signatureContext.arc(position.x, position.y, 1.25, 0, Math.PI * 2);
                    signatureContext.fill();

                    signatureHasDrawing = true;
                    signatureInput.value = signatureCanvas.toDataURL('image/png');
                });

                // Menggambar garis
                signatureCanvas.addEventListener('pointermove', function(event) {
                    if (!signatureDrawing || !signatureContext) return;

                    event.preventDefault();

                    const position = getSignaturePosition(event);

                    signatureContext.lineTo(position.x, position.y);
                    signatureContext.stroke();

                    signatureHasDrawing = true;
                });

                // Selesaikan tanda tangan
                function finishSignature() {
                    if (!signatureDrawing) return;

                    signatureDrawing = false;

                    if (signatureHasDrawing) {
                        signatureInput.value = signatureCanvas.toDataURL('image/png');
                    }
                }

                signatureCanvas.addEventListener('pointerup', finishSignature);
                signatureCanvas.addEventListener('pointercancel', finishSignature);
                signatureCanvas.addEventListener('lostpointercapture', finishSignature);
            }

            // Tombol Hapus TTD
            if (clearSignatureButton) {
                clearSignatureButton.addEventListener('click', function() {
                    if (!signatureContext) {
                        initSignatureCanvas();
                    }

                    if (signatureContext) {
                        const rect = signatureCanvas.getBoundingClientRect();

                        signatureContext.clearRect(
                            0,
                            0,
                            rect.width,
                            rect.height
                        );
                    }

                    signatureDrawing = false;
                    signatureHasDrawing = false;
                    signatureInput.value = '';
                });
            }

            // Validasi sebelum form dikirim
            if (signatureForm) {
                signatureForm.addEventListener('submit', function(event) {
                    if (!signatureInput.value || !signatureHasDrawing) {
                        event.preventDefault();

                        alert('Silakan bubuhkan tanda tangan Worker terlebih dahulu.');
                        signatureCanvas.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                });
            }

            // Sesuaikan ukuran canvas ketika ukuran layar berubah
            window.addEventListener('resize', function() {
                if (signatureCanvas && signatureCanvas.offsetWidth > 0) {
                    initSignatureCanvas();
                }
            });
        </script>

    @endsection
