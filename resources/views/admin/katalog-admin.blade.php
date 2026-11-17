@extends('layouts.app-admin')

@section('title', 'Katalog Spare Part')

@section('page-title', 'Katalog Master')

@section('page-subtitle', 'PLN Nusantara Power Services')

@section('Profile', 'Admin')

@section('content')

    <style>
        /* INFORMASI STOK BARCODE */
        .barcode-stock {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;

            margin-bottom: 15px;

            color: #7b91a8;
            font-size: 10px;
        }

        .barcode-stock strong {
            color: #263d5b;
            font-weight: 700;
        }

        .stock-divider {
            margin: 0 5px;
            color: #cbd5e1;
        }

        /* =====================================================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       FILTER
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ===================================================== */

        .catalog-filter {
            min-height: 70px;

            padding: 13px 16px;

            display: flex;
            align-items: center;
            gap: 10px;

            background: #ffff;

            border: 1px solid #e8edf3;

            border-radius: 16px;

            margin-bottom: 18px;
            box-shadow: 0 2px 5px rgba(15, 23, 42, 0.06);
        }

        .search-box {
            height: 37px;

            flex: 1;

            min-width: 200px;

            position: relative;
        }

        .search-box svg {
            position: absolute;

            width: 17px;
            height: 17px;

            left: 13px;
            top: 10px;

            color: #6e7d96;
        }

        .search-box input {
            width: 100%;
            height: 100%;

            padding: 0 12px 0 39px;

            border: 1px solid #dfe5ec;
            border-radius: 7px;

            outline: none;

            background: #f8fafc;

            color: #95a1b2;

            font-family: inherit;
            font-size: 11px;
        }

        .search-box input::placeholder {
            color: #738099;
        }

        .search-box input:focus {
            border-color: #2873d8;
        }


        .filter-select {
            height: 37px;

            width: 175px;

            padding: 0 11px;

            border-radius: 9px;
            border: 1px solid #dce4ef;

            background: #ffffff;

            color: #263d5b;

            outline: none;

            font-family: 'Poppins', sans-serif;
            font-size: 12px;

            cursor: pointer;
            transition: 0.2s;
        }

        .filter-select:focus {
            border-color: #2873d8;
        }


        .sort-button {
            width: 37px;
            height: 37px;

            border: none;

            background: #1653eb;

            color: white;

            border-radius: 9px;

            font-size: 17px;
            font-weight: 500;
            font-family: 'Poppins', sans-serif;

            cursor: pointer;
            transition: 0.2s;
            white-space: nowrap;
        }


        /* =====================================================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       TABLE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ===================================================== */

        .catalog-table-wrapper {
            background: #ffffff;

            border: 1px solid #e8edf5;
            box-shadow: 0 3px 12px rgba(15, 39, 70, 0.04);

            border-radius: 18px;

            overflow-x: auto;
        }

        .catalog-table {
            width: 100%;

            border-collapse: collapse;

            min-width: 1100px;
        }

        .catalog-table th {

            padding: 15px 17px;

            text-align: left;

            background: #fafbfd;

            color: #8494aa;

            font-size: 10px;
            font-weight: 600;

            border-bottom: 1px solid #edf1f6;
            white-space: nowrap;
        }

        .catalog-table td {
            padding: 16px 17px;

            border-bottom: 1px solid #f0f3f7;

            color: #526680;

            font-size: 11px;
            white-space: nowrap;
        }

        .catalog-table tr:last-child td {
            border-bottom: none;
        }

        /* Item Number */

        .item-number {
            color: #075dcc;
            font-weight: 600;
        }


        /* Description */

        .item-description strong {
            display: block;

            color: #1b304d;

            font-weight: 600;

            margin-bottom: 3px;
        }

        .item-description span {
            color: #526680;

            font-size: 10px;
        }


        /* Category */

        .category-badge {
            display: inline-flex;
            align-items: center;

            padding: 5px 9px;

            border-radius: 14px;

            background: #075eaec4;

            color: #ffffff;

            font-size: 10px;
            white-space: nowrap;
        }


        /* Location */

        .location-badge {
            display: inline-block;

            padding: 5px 7px;

            border-radius: 4px;

            /* background: #27364b; */

            color: #526680;

            font-size: 9px;
            font-weight: 600;

            line-height: 1.25;
        }


        /* Stock */

        .stock-value {
            display: flex;
            align-items: center;
            gap: 4px;

            white-space: nowrap;
        }

        .stock-main {
            color: ##526680;

            font-weight: 700;
            font-size: 10px;
        }

        .stock-maximo {
            color: #526680;
            font-size: 9px;
        }


        .stock-status {
            display: inline-flex;

            margin-top: 5px;

            padding: 3px 8px;

            border-radius: 10px;

            font-size: 9px;
            font-weight: 600;
        }

        .stock-status.match {
            color: #0b9d50;
            background: #ecfaf2;
            border: 1px solid #c9efd9;
        }

        .stock-status.warning {
            color: #cc8b12;
            background: #faf5ec;
            border: 1px solid #efe5c9;
        }


        /* Price */

        .price {
            color: #526680;

            font-size: 10px;
            white-space: nowrap;
        }


        /* Print */

        .btn-print {
            height: 30px;

            padding: 0 10px;

            display: inline-flex;
            align-items: center;

            border: 1px solid #d8e5f8;
            border-radius: 7px;

            background: #e5ecf3d7;

            color: #616974;

            font-family: 'Poppins' sans-serif;
            font-size: 9px;
            font-weight: 600;

            cursor: pointer;
        }

        .btn-print:hover {
            background: #a6cbfc98;
            border-color: #e1edfc;
        }

        .btn-print img {
            margin-right: 5px;
        }


        /* Action */

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-icon-button {
            cursor: pointer;
            border: none;
        }

        /* =========================================================
                                                                                                                                                                               MODAL BARCODE
                                                                                                                                                                            ========================================================= */

        .barcode-modal-overlay {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;

            background: rgba(4, 12, 30, 0.68);

            z-index: 10000;

            opacity: 0;
            visibility: hidden;

            transition:
                opacity .2s ease,
                visibility .2s ease;
        }

        .barcode-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }


        /* =========================================================
                                                                                                                                                                               MODAL
                                                                                                                                                                            ========================================================= */

        .barcode-modal {
            width: 100%;
            max-width: 470px;

            background: #ffffff;

            border-radius: 14px;

            overflow: hidden;

            box-shadow:
                0 25px 70px rgba(15, 39, 70, .30);

            transform:
                translateY(10px) scale(.98);

            transition:
                transform .2s ease;
        }

        .barcode-modal-overlay.show .barcode-modal {
            transform:
                translateY(0) scale(1);
        }


        /* =========================================================
                                                                                                                                                                               HEADER
                                                                                                                                                                            ========================================================= */

        .barcode-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            padding: 20px 22px;

            border-bottom: 1px solid #e8edf3;

            background: #ffffff;
        }

        .barcode-modal-header h2 {
            margin: 0 0 4px;

            color: #123b68;

            font-size: 18px;
            font-weight: 700;
        }

        .barcode-modal-header p {
            margin: 0;

            color: #7b91a8;

            font-size: 10px;
        }

        .barcode-modal-close {
            width: 32px;
            height: 32px;

            display: flex;
            align-items: center;
            justify-content: center;

            border: none;
            border-radius: 7px;

            background: transparent;

            color: #7890a7;

            font-size: 25px;

            cursor: pointer;

            transition: .2s ease;
        }

        .barcode-modal-close:hover {
            background: #f0f5fa;
            color: #164e82;
        }


        /* =========================================================
                                                                                                                                                                               BODY
                                                                                                                                                                            ========================================================= */

        .barcode-modal-body {
            padding: 25px;
            background: #f7faff;
        }


        /* =========================================================
                                                                                                                                                                               LABEL
                                                                                                                                                                            ========================================================= */

        .barcode-label {
            width: 100%;

            padding: 22px 20px;

            box-sizing: border-box;

            background: #ffffff;

            border: 1px solid #dce6f2;

            border-radius: 10px;

            text-align: center;

            box-shadow:
                0 3px 12px rgba(15, 39, 70, .05);
        }


        /* BRAND */

        .barcode-brand {
            display: flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            margin-bottom: 12px;

            color: #123b68;
        }

        .barcode-brand strong {
            font-size: 17px;
            font-weight: 700;
        }

        .barcode-brand span {
            color: #6e8299;

            font-size: 10px;
        }


        /* ITEM NUMBER */

        .barcode-item-number {
            margin-bottom: 5px;

            color: #075dcc;

            font-size: 17px;
            font-weight: 700;

            letter-spacing: .5px;
        }


        /* DESCRIPTION */

        .barcode-description {
            max-width: 370px;

            margin: 0 auto 13px;

            color: #526680;

            font-size: 10px;

            line-height: 1.5;
        }


        /* LOCATION */

        .barcode-location {
            display: flex;

            justify-content: center;

            gap: 5px;

            margin-bottom: 17px;

            color: #7b91a8;

            font-size: 10px;
        }

        .barcode-location strong {
            color: #263d5b;
        }


        /* BARCODE */

        .barcode-image-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;

            width: 100%;

            min-height: 95px;

            margin: 5px 0 8px;
        }

        #barcodeImage {
            max-width: 100%;
            height: auto;
        }


        /* CODE */

        .barcode-code {
            margin-top: 3px;

            color: #526680;

            font-family: monospace;

            font-size: 11px;

            letter-spacing: 1px;
        }


        /* =========================================================
                                                                                                                                                                               FOOTER
                                                                                                                                                                            ========================================================= */

        .barcode-modal-footer {
            display: flex;

            justify-content: flex-end;
            align-items: center;

            gap: 8px;

            padding: 15px 22px;

            border-top: 1px solid #e8edf3;

            background: #ffffff;
        }


        /* BATAL */

        .barcode-btn-cancel {
            height: 34px;

            padding: 0 14px;

            border: 1px solid #d8e2ec;

            border-radius: 7px;

            background: #ffffff;

            color: #526680;

            font-family: 'Poppins', sans-serif;

            font-size: 10px;
            font-weight: 600;

            cursor: pointer;

            transition: .2s ease;
        }

        .barcode-btn-cancel:hover {
            background: #f4f7fa;
        }


        /* PRINT */

        .barcode-btn-print {
            height: 34px;

            padding: 0 15px;

            display: inline-flex;

            align-items: center;
            justify-content: center;

            gap: 6px;

            border: none;

            border-radius: 7px;

            background: linear-gradient(135deg,
                    #0874c9,
                    #0756a8);

            color: #ffffff;

            font-family: 'Poppins', sans-serif;

            font-size: 10px;
            font-weight: 600;

            cursor: pointer;

            box-shadow:
                0 4px 10px rgba(7, 86, 168, .18);

            transition: .2s ease;
        }

        .barcode-btn-print:hover {
            transform: translateY(-1px);

            box-shadow:
                0 6px 14px rgba(7, 86, 168, .24);
        }


        /* =========================================================
                                                                                                                                                                               PRINT
                                                                                                                                                                            ========================================================= */

        @media print {

            @page {
                size: auto;
                margin: 10mm;
            }

            body * {
                visibility: hidden !important;
            }

            #barcodeModal,
            #barcodeModal * {
                visibility: visible !important;
            }

            #barcodeModal {
                position: absolute !important;

                inset: 0 !important;

                display: flex !important;

                align-items: center !important;
                justify-content: center !important;

                background: #ffffff !important;

                opacity: 1 !important;
                visibility: visible !important;
            }

            .barcode-modal {
                max-width: 470px;

                box-shadow: none !important;

                border: 1px solid #dce6f2;
            }

            .barcode-modal-header,
            .barcode-modal-footer {
                display: none !important;
            }

            .barcode-modal-body {
                padding: 0 !important;

                background: #ffffff !important;
            }

            .barcode-label {
                box-shadow: none !important;

                border: 1px solid #dce6f2 !important;
            }
        }

        /* =====================================================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       RESPONSIVE
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ===================================================== */

        @media (max-width: 900px) {

            .catalog-filter {
                flex-wrap: balance;
            }

            .search-box {
                flex-basis: 100%;
            }

            .filter-select {
                flex: 1;
                min-width: 140px;
            }

        }
    </style>

    @if (session('success'))
        <div
            style="
        padding: 12px 16px;
        margin-bottom: 15px;
        border-radius: 8px;
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
        font-size: 12px;
    ">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            style="
        padding: 12px 16px;
        margin-bottom: 15px;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-size: 12px;
    ">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div
            style="
        padding: 12px 16px;
        margin-bottom: 15px;
        border-radius: 8px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-size: 12px;
    ">
            <strong>Data belum dapat disimpan:</strong>
            <ul style="margin: 6px 0 0 18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- =====================================================
     FILTER
====================================================== --}}

    <div class="catalog-filter">

        {{-- SEARCH --}}

        <div class="search-box">

            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="7" />
                <path d="M20 20l-4-4" />
            </svg>

            <input type="text" placeholder="Cari Item Number, Deskripsi, Rak, atau Barcode...">

        </div>


        {{-- KATEGORI --}}

        <select class="filter-select">

            <option>
                Semua Kategori
            </option>

            <option>
                Mekanikal
            </option>

            <option>
                Elektrikal
            </option>

            <option>
                I&C
            </option>

            <option>
                Kimia & Pelumas
            </option>

            <option>
                Consumable & Fastener
            </option>

            <option>
                Safety & K3
            </option>

        </select>


        {{-- STATUS --}}

        <select class="filter-select">

            <option>
                Semua Status Stok
            </option>

            <option>
                Match
            </option>

            <option>
                Selisih
            </option>

        </select>


        {{-- SORT --}}

        <select class="filter-select">

            <option>
                Urutkan: Item Number
            </option>

            <option>
                Urutkan: Nama
            </option>

            <option>
                Urutkan: Harga
            </option>

            <option>
                Urutkan: Stok
            </option>

        </select>


        <button class="sort-button">
            ⇅
        </button>

    </div>


    {{-- =====================================================
     TABLE
====================================================== --}}

    <div class="catalog-table-wrapper">

        <table class="catalog-table">

            <thead>

                <tr>

                    <th>
                        Item Number
                    </th>

                    <th>
                        Deskripsi & Spesifikasi
                    </th>

                    <th>
                        Kategori
                    </th>

                    <th>
                        Lokasi Bin / Rak
                    </th>

                    <th>
                        Fisik vs Maximo
                    </th>

                    <th>
                        Harga Satuan
                    </th>

                    <th>
                        Label / Barcode
                    </th>

                    <th>
                        Aksi
                    </th>

                </tr>

            </thead>



            <tbody>

                @forelse ($barang as $item)
                    <tr>

                        {{-- ITEM NUMBER --}}
                        <td>
                            <div class="item-number">
                                {{ $item->kode_barang }}
                            </div>
                        </td>

                        {{-- DESKRIPSI --}}
                        <td>
                            <div class="item-description">
                                <strong>
                                    {{ $item->deskripsi }}
                                </strong>
                            </div>
                        </td>

                        {{-- KATEGORI --}}
                        <td>
                            <span class="category-badge">
                                {{ $item->kategori }}
                            </span>
                        </td>

                        {{-- LOKASI RAK --}}
                        <td>
                            <span class="location-badge">
                                {{ $item->lokasi_rak }}
                            </span>
                        </td>

                        {{-- STOK FISIK VS MAXIMO --}}
                        <td>
                            <div class="stock-value">
                                <span class="stock-main">{{ $item->stok_fisik }}</span>
                                <span class="stock-maximo">/ {{ $item->stok_maximo }}</span>
                            </div>

                        </td>

                        {{-- HARGA --}}
                        <td>
                            <span class="price">
                                {{ $item->harga_satuan }}
                            </span>
                        </td>

                        {{-- BARCODE --}}
                        <td>
                            <button type="button" class="btn-print">

                                <span>
                                    <img src="{{ asset('assets/images/icon-barcode2.png') }}" alt="Icon Barcode"
                                        style="width: 20px; height: 20px">
                                </span>

                                Cetak Label

                            </button>
                        </td>

                        {{-- AKSI --}}
                        <td>
                            <div class="action-buttons">

                                <a href="{{ url('/katalogAdmin/edit/' . $item->kode_barang) }}" class="table-icon-button">
                                    <img src="{{ asset('assets/images/icon-edit.png') }}" alt="Icon Edit"
                                        style="width: 15px; height: 15px">
                                </a>
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px;">
                            Belum ada data barang.
                        </td>
                    </tr>
                @endforelse

            </tbody>


        </table>

    </div>

    {{-- =========================================================
     MODAL BARCODE BARANG
========================================================= --}}
    <div class="barcode-modal-overlay" id="barcodeModal">

        <div class="barcode-modal">

            {{-- HEADER --}}
            <div class="barcode-modal-header">

                <div>
                    <h2>Label Spare Part</h2>

                    <p>
                        Barcode material dari katalog master
                    </p>
                </div>

                <button type="button" class="barcode-modal-close" id="closeBarcodeModal">
                    &times;
                </button>

            </div>


            {{-- BODY --}}
            <div class="barcode-modal-body">

                <div class="barcode-label">

                    <div class="barcode-brand">
                        <strong>PLN</strong>
                        <span>Nusantara Power Services</span>
                    </div>

                    <div class="barcode-item-number" id="barcodeItemNumber">
                        -
                    </div>

                    <div class="barcode-description" id="barcodeDescription">
                        -
                    </div>

                    <div class="barcode-location">
                        <span>Lokasi Rak:</span>
                        <strong id="barcodeLocation">-</strong>
                    </div>

                    <div class="barcode-stock">
                        <span>Stok Fisik:</span>
                        <strong id="barcodeStokFisik">0</strong>

                        <span class="stock-divider">|</span>

                        <span>Stok Maximo:</span>
                        <strong id="barcodeStokMaximo">0</strong>
                    </div>

                    <div class="barcode-image-wrapper">

                        <svg id="barcodeImage"></svg>

                    </div>

                    <div class="barcode-code" id="barcodeCode">
                        -
                    </div>

                </div>

            </div>


            {{-- FOOTER --}}
            <div class="barcode-modal-footer">

                <button type="button" class="barcode-btn-cancel" id="cancelBarcodeModal">
                    Batal
                </button>

                <button type="button" class="barcode-btn-print" id="printBarcode">
                    Cetak Barcode
                </button>

            </div>

        </div>

    </div>

    {{-- =========================================================
         LIBRARY BARCODE
    ========================================================= --}}
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    {{-- =========================================================
         SCRIPT BARCODE LABEL
    ========================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const barcodeModal =
                document.getElementById('barcodeModal');

            const closeBarcodeModal =
                document.getElementById('closeBarcodeModal');

            const cancelBarcodeModal =
                document.getElementById('cancelBarcodeModal');

            const printBarcode =
                document.getElementById('printBarcode');

            const barcodeImage =
                document.getElementById('barcodeImage');

            const barcodeItemNumber =
                document.getElementById('barcodeItemNumber');

            const barcodeDescription =
                document.getElementById('barcodeDescription');

            const barcodeLocation =
                document.getElementById('barcodeLocation');

            const barcodeStokFisik =
                document.getElementById('barcodeStokFisik');

            const barcodeStokMaximo =
                document.getElementById('barcodeStokMaximo');

            const barcodeCode =
                document.getElementById('barcodeCode');

            document.querySelectorAll('.btn-print').forEach(function(button) {

                button.addEventListener('click', function() {

                    const row = this.closest('tr');

                    if (!row) return;

                    const itemNumberElement =
                        row.querySelector('.item-number');

                    const itemNumber =
                        itemNumberElement ?
                        itemNumberElement.textContent.trim() :
                        '-';

                    const descriptionElement =
                        row.querySelector('.item-description strong');

                    const description =
                        descriptionElement ?
                        descriptionElement.textContent.trim() :
                        '-';

                    const locationElement =
                        row.querySelector('.location-badge');

                    const location =
                        locationElement ?
                        locationElement.textContent.trim() :
                        '-';

                    // Ambil stok fisik
                    const stokFisikElement =
                        row.querySelector('.stock-main');

                    const stokFisik =
                        stokFisikElement ?
                        stokFisikElement.textContent.trim() :
                        '0';

                    // Ambil stok Maximo
                    const stokMaximoElement =
                        row.querySelector('.stock-maximo');

                    const stokMaximo =
                        stokMaximoElement ?
                        stokMaximoElement.textContent.trim().replace('/', '').trim() :
                        '0';

                    barcodeItemNumber.textContent = itemNumber;
                    barcodeDescription.textContent = description;
                    barcodeLocation.textContent = location;

                    barcodeStokFisik.textContent = stokFisik;
                    barcodeStokMaximo.textContent = stokMaximo;

                    barcodeCode.textContent = itemNumber;

                    // Bersihkan barcode lama
                    barcodeImage.innerHTML = '';

                    // Generate barcode
                    if (typeof JsBarcode !== 'undefined') {

                        JsBarcode(
                            barcodeImage,
                            itemNumber, {
                                format: 'CODE128',
                                width: 2,
                                height: 70,
                                displayValue: false,
                                margin: 5,
                                background: '#ffffff',
                                lineColor: '#111827'
                            }
                        );

                    } else {

                        console.error('JsBarcode belum tersedia.');

                    }

                    barcodeModal.classList.add('show');

                    document.body.style.overflow = 'hidden';

                });

            });

            function closeBarcode() {
                barcodeModal.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (closeBarcodeModal) {
                closeBarcodeModal.addEventListener(
                    'click',
                    closeBarcode
                );
            }

            if (cancelBarcodeModal) {
                cancelBarcodeModal.addEventListener(
                    'click',
                    closeBarcode
                );
            }

            if (barcodeModal) {
                barcodeModal.addEventListener(
                    'click',
                    function(event) {

                        if (event.target === barcodeModal) {
                            closeBarcode();
                        }

                    }
                );
            }

            document.addEventListener(
                'keydown',
                function(event) {

                    if (
                        event.key === 'Escape' &&
                        barcodeModal &&
                        barcodeModal.classList.contains('show')
                    ) {
                        closeBarcode();
                    }

                }
            );

            if (printBarcode) {
                printBarcode.addEventListener(
                    'click',
                    function() {
                        window.print();
                    }
                );
            }

        });
    </script>
@endsection
