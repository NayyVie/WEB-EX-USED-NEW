@extends('layouts.app-admin')

@section('title', 'Pemindai Barcode')

@section('page-title', 'Pemindai Barcode')

@section('content')

    <div class="scanner-page">

        {{-- SCANNER STATUS --}}

        <div class="scanner-status-row">

            <span>

                <i class="fa-solid fa-qrcode"></i>

                Scanner Barcode & QR Code Terminal Pergudangan

            </span>

        </div>



        {{-- MAIN SCANNER AREA --}}

        <div class="scanner-main-grid">

            {{-- LEFT --}}

            <div class="scanner-left">

                <div class="scanner-card">

                    <div class="scanner-card-top">

                        <span>OPTICAL SCANNER READY</span>

                        <span>MAXIMO LIVE BRIDGE</span>

                    </div>

                    <div class="scanner-box">

                        <div id="cameraPreview" class="camera-preview"></div>

                        <button type="button" id="cameraToggle" class="camera-toggle" title="Ganti kamera depan / belakang"
                            aria-label="Ganti kamera depan atau belakang">
                            <i class="fa-solid fa-camera-rotate"></i>
                        </button>

                        <div class="scanner-corner top-left"></div>

                        <div class="scanner-corner top-right"></div>

                        <div class="scanner-corner bottom-left"></div>

                        <div class="scanner-corner bottom-right"></div>

                        <div class="scanner-barcode-icon">

                            <span></span>

                            <span></span>

                            <span></span>

                            <span></span>

                            <span></span>

                        </div>

                        <div class="scanner-line"></div>

                        <div class="scanner-instruction">

                            <strong>

                                Arahkan Barcode atau QR Code Material Pembangkit

                            </strong>

                            <span>

                                Kamera Otomatis / Scan Gun USB & Bluetooth didukung

                            </span>

                        </div>

                    </div>



                    {{-- INPUT MANUAL --}}

                    <div class="manual-scan">

                        <label>

                            Input Manual / Serial Scanner Gun Reader:

                        </label>

                        <div class="manual-input-row">

                            <div class="barcode-input-wrapper">

                                <i class="fa-solid fa-barcode"></i>

                                <input type="text" id="barcodeInput"
                                    placeholder="Ketik Barcode, Item Number (MP-...), atau Rak Bin..." autocomplete="off">

                            </div>

                            <button type="button" class="scan-button" onclick="scanBarcode()">

                                Scan & Temukan

                            </button>

                        </div>

                    </div>

                </div>




                {{-- SIMULATION --}}
                <div class="simulation-card">

                    <div class="simulation-title">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        Simulasi Cepat Pemindaian
                        <span>(Pilih Material dari Database):</span>
                    </div>

                    <div class="simulation-grid">

                        @forelse ($barangSimulasi as $item)
                            <button type="button" class="simulation-item"
                                onclick="selectMaterial(@js($item->kode_barang))">

                                <div class="simulation-item-header">

                                    <strong>
                                        {{ $item->kode_barang }}
                                    </strong>

                                    <span>
                                        {{ $item->lokasi_rak ?: 'Lokasi belum diatur' }}
                                    </span>

                                </div>

                                <div class="simulation-name">
                                    {{ $item->deskripsi }}
                                </div>

                                <div class="simulation-detail">

                                    <span>
                                        Kode: {{ $item->kode_barang }}
                                    </span>

                                    <strong>
                                        Stok fisik:
                                        {{ $item->stok_fisik }}
                                        {{ $item->satuan }}
                                    </strong>

                                </div>

                            </button>

                        @empty

                            <div class="history-empty" style="grid-column: 1 / -1;">
                                Belum ada data barang di database.
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>



            {{-- RIGHT --}}

            <div class="scanner-right">

                {{-- HASIL SCAN --}}

                <div class="scan-result-card" id="scanResult">

                    <div class="empty-scan">

                        <div class="empty-scan-icon">

                            <i class="fa-solid fa-barcode"></i>

                        </div>

                        <strong>

                            Menunggu Pemindaian Barcode

                        </strong>

                        <span>

                            Scan barcode fisik menggunakan laser scanner

                            atau pilih salah satu tombol simulasi di samping.

                        </span>

                    </div>

                </div>



                {{-- RIWAYAT --}}

                <div class="scan-history-card">

                    <div class="history-title">

                        <i class="fa-solid fa-clock-rotate-left"></i>

                        Riwayat Scan Terminal Terakhir:

                    </div>

                    <div id="scanHistory" class="history-empty">

                        Belum ada rekaman scan pada sesi ini.

                    </div>

                </div>

            </div>

        </div>

    </div>



    <style>
        /* =========================================================*

                        *                   SCANNER PAGE*

                        /* ========================================================= */

        .scanner-page {

            width: 100%;

            color: #142b4f;

        }



        /* STATUS ROW */

        .scanner-status-row {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 12px;

            padding: 0 2px;

        }

        .scanner-status-row>span {

            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 16px;

            font-weight: 800;

            color: #142b4f;

        }

        .scanner-status-row>span i {

            color: #0874c9;

        }

        .audio-status {

            border: 1px solid #9ddbc8;

            background: #f1fffa;

            color: #008f6b;

            border-radius: 20px;

            height: 28px;

            padding: 0 13px;

            font-size: 9px;

            font-weight: 700;

        }



        /* MAIN */

        .scanner-main-grid {

            display: grid;

            grid-template-columns: minmax(0, 1.45fr) minmax(280px, .8fr);

            gap: 18px;

        }

        .scanner-left,

        .scanner-right {

            min-width: 0;

        }



        /* SCANNER CARD */

        .scanner-card {

            background: #fff;

            border: 1px solid #dbe5f0;

            border-radius: 14px;

            padding: 16px;

            box-shadow: 0 5px 18px rgba(28, 70, 110, .06);

        }

        .scanner-card-top {

            display: flex;

            justify-content: space-between;

            color: #75869d;

            font-size: 9px;

            font-weight: 700;

            letter-spacing: .5px;

            margin-bottom: 10px;

        }

        .scanner-box {

            position: relative;

            height: 270px;

            border: 1px solid #d6e3ef;

            border-radius: 10px;

            background:

                radial-gradient(circle at 1px 1px, #dce8f3 1px, transparent 1px);

            background-size: 16px 16px;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

        }



        /* SCANNER CORNERS */

        .camera-preview {
            position: absolute;
            inset: 0;
            z-index: 1;
            display: none;
            background: #0b1f33;
        }

        .camera-preview.active {
            display: block;
        }

        .camera-preview video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }

        .camera-toggle {
            position: absolute;
            top: 18px;
            right: 18px;
            z-index: 8;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, .75);
            border-radius: 50%;
            background: rgba(8, 116, 201, .9);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
        }

        .camera-toggle.active {
            display: flex;
        }

        .camera-toggle:hover {
            background: #0756a8;
        }

        .scanner-box.camera-active .scanner-barcode-icon,
        .scanner-box.camera-active .scanner-instruction {
            display: none;
        }

        .scanner-box.camera-active .scanner-line {
            z-index: 5;
        }

        .scanner-corner {

            position: absolute;

            z-index: 6;

            width: 25px;

            height: 25px;

            border-color: #0874c9;

            border-style: solid;

        }

        .scanner-corner.top-left {

            top: 12px;

            left: 12px;

            border-width: 2px 0 0 2px;

        }

        .scanner-corner.top-right {

            top: 12px;

            right: 12px;

            border-width: 2px 2px 0 0;

        }

        .scanner-corner.bottom-left {

            bottom: 12px;

            left: 12px;

            border-width: 0 0 2px 2px;

        }

        .scanner-corner.bottom-right {

            bottom: 12px;

            right: 12px;

            border-width: 0 2px 2px 0;

        }



        /* BARCODE */

        .scanner-barcode-icon {

            display: flex;

            gap: 5px;

            align-items: flex-end;

            height: 45px;

            position: relative;

            z-index: 2;

        }

        .scanner-barcode-icon span {

            display: block;

            width: 4px;

            height: 39px;

            background: #08a5df;

            border-radius: 2px;

        }

        .scanner-barcode-icon span :nth-child(2) {

            height: 47px;

        }

        .scanner-barcode-icon span :nth-child(3) {

            height: 33px;

        }

        .scanner-barcode-icon span :nth-child(4) {

            height: 47px;

        }

        .scanner-barcode-icon span :nth-child(5) {

            height: 38px;

        }



        /* SCAN LINE */

        .scanner-line {

            position: absolute;

            left: 0;

            right: 0;

            top: 52%;

            height: 2px;

            background: #e91e3d;

            box-shadow: 0 0 7px rgba(233, 30, 61, .35);

        }



        /* INSTRUCTION */

        .scanner-instruction {

            position: absolute;

            bottom: 43px;

            left: 0;

            right: 0;

            text-align: center;

            display: flex;

            flex-direction: column;

            gap: 6px;

        }

        .scanner-instruction strong {

            font-size: 11px;

            color: #41617f;

        }

        .scanner-instruction span {

            font-size: 9px;

            color: #8999ad;

        }



        /* MANUAL INPUT */

        .manual-scan {

            margin-top: 12px;

        }

        .manual-scan label {

            display: block;

            margin-bottom: 6px;

            color: #596f89;

            font-size: 9px;

            font-weight: 600;

        }

        .manual-input-row {

            display: flex;

            gap: 8px;

        }

        .barcode-input-wrapper {

            flex: 1;

            min-width: 0;

            height: 37px;

            border: 1px solid #d4dfec;

            background: #f7faff;

            border-radius: 7px;

            display: flex;

            align-items: center;

            padding: 0 11px;

            gap: 8px;

        }

        .barcode-input-wrapper i {

            color: #8295aa;

        }

        .barcode-input-wrapper input {

            width: 100%;

            border: 0;

            outline: 0;

            background: transparent;

            color: #263f5e;

            font-family: inherit;

            font-size: 10px;

        }

        .barcode-input-wrapper input::placeholder {

            color: #9aa9ba;

        }

        .scan-button {

            height: 37px;

            border: 0;

            border-radius: 7px;

            background: linear-gradient(135deg, #0874c9, #0756a8);

            color: #fff;

            padding: 0 17px;

            font-family: inherit;

            font-size: 10px;

            font-weight: 700;

            cursor: pointer;

        }

        .scan-button :hover {

            opacity: .92;

        }



        /* SIMULATION */

        .simulation-card {

            margin-top: 14px;

            border: 1px solid #dbe5f0;

            border-radius: 13px;

            padding: 13px;

            background: #fff;

        }

        .simulation-title {

            color: #526b86;

            font-size: 10px;

            font-weight: 700;

            margin-bottom: 9px;

        }

        .simulation-title i {

            color: #f0a000;

            margin-right: 4px;

        }

        .simulation-title span {

            color: #8b9bae;

            font-weight: 500;

        }

        .simulation-grid {

            display: grid;

            grid-template-columns: repeat(2, minmax(0, 1fr));

            gap: 7px;

        }

        .simulation-item {

            border: 1px solid #dbe5f0;

            background: #f8fbff;

            border-radius: 7px;

            padding: 9px;

            text-align: left;

            cursor: pointer;

            transition: .2s ease;

        }

        .simulation-item :hover {

            border-color: #70b5eb;

            background: #f2f8ff;

        }

        .simulation-item-header {

            display: flex;

            justify-content: space-between;

            gap: 5px;

        }

        .simulation-item-header strong {

            color: #008ed3;

            font-size: 9px;

        }

        .simulation-item-header span {

            background: #edf3f9;

            color: #6d7f93;

            padding: 3px 5px;

            border-radius: 4px;

            font-size: 7px;

            font-weight: 700;

        }

        .simulation-name {

            margin-top: 5px;

            color: #3d5874;

            font-size: 8px;

            font-weight: 700;

            line-height: 1.35;

        }

        .simulation-detail {

            margin-top: 5px;

            display: flex;

            justify-content: space-between;

            gap: 5px;

            color: #8b9bad;

            font-size: 7px;

        }

        .simulation-detail strong {

            color: #425a75;

        }



        /* RIGHT SIDE */

        .scanner-right {

            display: flex;

            flex-direction: column;

            gap: 13px;

        }

        .scan-result-card,

        .scan-history-card {

            border: 1px solid #dbe5f0;

            border-radius: 13px;

            background: #fff;

            box-shadow: 0 5px 18px rgba(28, 70, 110, .05);

        }

        .scan-result-card {

            min-height: 270px;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 20px;

        }

        .empty-scan {

            display: flex;

            flex-direction: column;

            align-items: center;

            text-align: center;

            gap: 9px;

        }

        .empty-scan-icon {

            width: 50px;

            height: 50px;

            border-radius: 50%;

            background: #edf3f9;

            color: #778aa0;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 22px;

        }

        .empty-scan strong {

            color: #4c627b;

            font-size: 12px;

        }

        .empty-scan span {

            max-width: 250px;

            color: #91a0b1;

            font-size: 9px;

            line-height: 1.5;

        }



        /* RESULT */

        .scan-result-content {

            width: 100%;

        }

        .scan-result-label {

            color: #8a9aac;

            font-size: 9px;

            font-weight: 700;

        }

        .scan-result-name {

            margin-top: 7px;

            color: #193858;

            font-size: 15px;

            font-weight: 800;

            line-height: 1.35;

        }

        .scan-result-code {

            margin-top: 5px;

            color: #0874c9;

            font-size: 10px;

            font-weight: 700;

        }

        .result-information {

            margin-top: 15px;

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 8px;

        }

        .result-box {

            background: #f5f9fd;

            border-radius: 7px;

            padding: 9px;

        }

        .result-box span {

            display: block;

            color: #8b9bac;

            font-size: 8px;

        }

        .result-box strong {

            display: block;

            margin-top: 3px;

            color: #2b4866;

            font-size: 10px;

        }



        /* HISTORY */

        .scan-history-card {

            min-height: 100px;

            padding: 14px;

        }

        .history-title {

            color: #526981;

            font-size: 10px;

            font-weight: 700;

        }

        .history-title i {

            margin-right: 5px;

            color: #6d8095;

        }

        .history-empty {

            text-align: center;

            color: #9aa8b7;

            font-size: 9px;

            padding: 25px 5px 12px;

        }

        .history-item {

            display: flex;

            justify-content: space-between;

            gap: 8px;

            padding: 9px 0;

            border-bottom: 1px solid #edf1f5;

        }

        .history-item :last-child {

            border-bottom: 0;

        }

        .history-item strong {

            color: #31506f;

            font-size: 9px;

        }

        .history-item span {

            color: #8b9aaa;

            font-size: 8px;

        }



        /* =========================================================*

                        *                   RESPONSIVE*

                        /* ========================================================= */

        @media (max-width: 1200px) {

            .scanner-header {

                flex-direction: column;

            }

            .scanner-header-actions {

                width: 100%;

                justify-content: flex-start;

            }

            .scanner-main-grid {

                grid-template-columns: 1fr;

            }

            .scanner-right {

                display: grid;

                grid-template-columns: 1fr 1fr;

            }

            .scan-result-card {

                min-height: 220px;

            }

        }



        @media (max-width: 768px) {

            .scanner-page {

                width: 100%;

            }

            .scanner-title {

                font-size: 18px;

            }

            .scanner-description {

                font-size: 10px;

            }

            .scanner-header-actions {

                gap: 5px;

            }

            .scanner-status-btn,

            .scanner-select {

                font-size: 9px;

                height: 32px;

                padding: 0 9px;

            }

            .scanner-select :nth-of-type(4) {

                display: none;

            }

            .scanner-icon-btn {

                display: none;

            }

            .scanner-status-row {

                align-items: flex-start;

                gap: 10px;

            }

            .scanner-status-row>span {

                font-size: 13px;

            }

            .audio-status {

                display: none;

            }

            .scanner-box {

                height: 230px;

            }

            .scanner-right {

                grid-template-columns: 1fr;

            }

            .simulation-grid {

                grid-template-columns: 1fr 1fr;

            }

        }



        @media (max-width: 520px) {

            .scanner-title {

                font-size: 16px;

            }

            .scanner-description {

                font-size: 9px;

            }

            .scanner-header-actions {

                width: 100%;

            }

            .scanner-status-btn,

            .scanner-select {

                flex: 1;

                justify-content: center;

            }

            .scanner-status-row>span {

                font-size: 11px;

            }

            .scanner-card {

                padding: 11px;

            }

            .scanner-box {

                height: 200px;

            }

            .scanner-instruction {

                bottom: 28px;

                padding: 0 15px;

            }

            .scanner-instruction strong {

                font-size: 9px;

            }

            .scanner-instruction span {

                font-size: 8px;

            }

            .manual-input-row {

                flex-direction: column;

            }

            .barcode-input-wrapper,

            .scan-button {

                width: 100%;

            }

            .simulation-grid {

                grid-template-columns: 1fr;

            }

            .simulation-item {

                min-height: 72px;

            }

        }



        @media (max-width: 360px) {

            .scanner-title {

                font-size: 15px;

            }

            .scanner-status-btn,

            .scanner-select {

                font-size: 8px;

                padding: 0 6px;

            }

            .scanner-box {

                height: 175px;

            }

            .scanner-card-top {

                font-size: 7px;

            }

        }
    </style>



    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        let scanHistory = [];
        let html5QrCode = null;
        let cameraDevices = [];
        let currentCameraIndex = 0;
        let scannerRunning = false;
        let lastScannedCode = '';
        let lastScanTime = 0;



        /* =========================================================
           PILIH MATERIAL DARI SIMULASI
        ========================================================= */

        function selectMaterial(kodeBarang) {
            document.getElementById('barcodeInput').value = kodeBarang;
            scanBarcode();
        }

        /* =========================================================
           SCAN BARCODE DAN CARI KE DATABASE
        ========================================================= */

        async function scanBarcode() {
            const input = document.getElementById('barcodeInput');
            const kodeBarang = input.value.trim();

            if (!kodeBarang) {
                await startCameraScanner();
                return;
            }

            const result = document.getElementById('scanResult');

            result.innerHTML = `
        <div class="empty-scan">
            <strong>Mencari data barang...</strong>
            <span>Mohon tunggu sebentar.</span>
        </div>
    `;

            try {
                const response = await fetch(
                    `/katalogAdmin/pemindai/cari/${encodeURIComponent(kodeBarang)}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    }
                );

                const responseData = await response.json();

                if (!response.ok || !responseData.success) {
                    showNotFound(kodeBarang);
                    return;
                }

                const material = responseData.data;

                showScanResult(material);

            } catch (error) {
                console.error('Gagal mencari barang:', error);

                result.innerHTML = `
            <div class="empty-scan">
                <strong>Terjadi Kesalahan</strong>
                <span>
                    Tidak dapat mengambil data barang dari server.
                    Silakan coba kembali.
                </span>
            </div>
        `;
            }
        }


        async function startCameraScanner(cameraIndex = null) {

            if (typeof Html5Qrcode === 'undefined') {
                alert('Modul kamera scanner belum berhasil dimuat. Periksa koneksi internet lalu coba lagi.');
                return;
            }

            const preview = document.getElementById('cameraPreview');
            const scannerBox = document.querySelector('.scanner-box');
            const toggleButton = document.getElementById('cameraToggle');

            try {
                if (!cameraDevices.length) {
                    cameraDevices = await Html5Qrcode.getCameras();
                }
                if (!cameraDevices.length) {
                    throw new Error('Kamera tidak ditemukan pada perangkat ini.');
                }

                currentCameraIndex = cameraIndex === null ?
                    findRearCameraIndex(cameraDevices) :
                    cameraIndex;

                if (html5QrCode && scannerRunning) {
                    await stopCameraScanner();
                }

                html5QrCode = new Html5Qrcode('cameraPreview');
                preview.classList.add('active');
                scannerBox.classList.add('camera-active');
                toggleButton.classList.add('active');

                await html5QrCode.start(
                    cameraDevices[currentCameraIndex].id, {
                        fps: 10,
                        qrbox: {
                            width: 260,
                            height: 120
                        },
                        aspectRatio: 1.777778,
                        formatsToSupport: [
                            Html5QrcodeSupportedFormats.CODE_128,
                            Html5QrcodeSupportedFormats.CODE_39,
                            Html5QrcodeSupportedFormats.CODE_93,
                            Html5QrcodeSupportedFormats.EAN_13,
                            Html5QrcodeSupportedFormats.EAN_8,
                            Html5QrcodeSupportedFormats.UPC_A,
                            Html5QrcodeSupportedFormats.UPC_E,
                            Html5QrcodeSupportedFormats.ITF,
                            Html5QrcodeSupportedFormats.QR_CODE
                        ],
                        rememberLastUsedCamera: true,
                        showTorchButtonIfSupported: true
                    },
                    function(decodedText) {
                        handleCameraScan(decodedText);
                    },
                    function() {}
                );

                scannerRunning = true;
            } catch (error) {
                scannerRunning = false;
                preview.classList.remove('active');
                scannerBox.classList.remove('camera-active');
                toggleButton.classList.remove('active');
                console.error('Camera scanner error:', error);
                alert(
                    'Kamera tidak dapat digunakan. Pastikan izin kamera diberikan pada browser dan halaman dibuka melalui localhost/HTTPS.'
                );
            }
        }

        function findRearCameraIndex(cameras) {
            const rearIndex = cameras.findIndex(camera => {
                const label = (camera.label || '').toLowerCase();
                return /back|rear|environment|belakang|utama/.test(label);
            });
            return rearIndex >= 0 ? rearIndex : 0;
        }

        async function switchCamera() {
            if (!cameraDevices.length) {
                await startCameraScanner();
                return;
            }
            const nextIndex = (currentCameraIndex + 1) % cameraDevices.length;
            await startCameraScanner(nextIndex);
        }

        async function stopCameraScanner() {
            if (!html5QrCode) return;
            try {
                if (scannerRunning) await html5QrCode.stop();
            } catch (error) {
                console.warn('Gagal menghentikan kamera:', error);
            } finally {
                try {
                    html5QrCode.clear();
                } catch (error) {}
                html5QrCode = null;
                scannerRunning = false;
                document.getElementById('cameraPreview')?.classList.remove('active');
                document.querySelector('.scanner-box')?.classList.remove('camera-active');
                document.getElementById('cameraToggle')?.classList.remove('active');
            }
        }


        async function handleCameraScan(decodedText) {
            const barcode = (decodedText || '').trim();

            if (!barcode) return;

            const now = Date.now();

            if (barcode === lastScannedCode && now - lastScanTime < 2500) {
                return;
            }

            lastScannedCode = barcode;
            lastScanTime = now;

            document.getElementById('barcodeInput').value = barcode;

            await scanBarcode();

            if (navigator.vibrate) {
                navigator.vibrate(120);
            }

            playScanFeedback();

            setTimeout(() => stopCameraScanner(), 600);
        }

        function playScanFeedback() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const context = new AudioContext();
                const oscillator = context.createOscillator();
                const gain = context.createGain();
                oscillator.type = 'sine';
                oscillator.frequency.value = 880;
                gain.gain.value = 0.05;
                oscillator.connect(gain);
                gain.connect(context.destination);
                oscillator.start();
                oscillator.stop(context.currentTime + 0.12);
            } catch (error) {}
        }

        /* =========================================================*

        *           HASIL SCAN*

        /* ========================================================= */


        /* =========================================================
           HASIL SCAN DARI DATABASE
        ========================================================= */

        function showScanResult(material) {
            const result = document.getElementById('scanResult');

            const itemNumber = material.kode_barang;
            const itemName = material.deskripsi || '-';
            const barcode = material.kode_barang;
            const rack = material.lokasi_rak || 'Belum diatur';

            const stokFisik = Number(material.stok_fisik ?? 0);
            const satuan = material.satuan || '';

            // Stok 0 belum tentu berarti stok aktual benar-benar kosong.
            const stock = material.stok_fisik === null ?
                'Belum tersedia' :
                `${stokFisik} ${satuan}`.trim();

            // Mengamankan teks dari database sebelum dimasukkan ke HTML.
            const escapeHTML = (value) => {
                return String(value).replace(/[&<>"']/g, function(char) {
                    return {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    } [char];
                });
            };

            result.innerHTML = `
        <div class="scan-result-content">

            <div class="scan-result-label">
                HASIL PEMINDAIAN
            </div>

            <div class="scan-result-name">
                ${escapeHTML(itemName)}
            </div>

            <div class="scan-result-code">
                ${escapeHTML(itemNumber)}
            </div>

            <div class="result-information">

                <div class="result-box">
                    <span>Barcode</span>
                    <strong>${escapeHTML(barcode)}</strong>
                </div>

                <div class="result-box">
                    <span>Rak / Lokasi</span>
                    <strong>${escapeHTML(rack)}</strong>
                </div>

                <div class="result-box">
                    <span>Stok Fisik</span>
                    <strong>${escapeHTML(stock)}</strong>
                </div>

                <div class="result-box">
                    <span>Kategori</span>
                    <strong>${escapeHTML(material.kategori || 'Belum diatur')}</strong>
                </div>

            </div>

        </div>
    `;

            addHistory(itemNumber, barcode);
        }

        /* =========================================================*

        *           DATA TIDAK DITEMUKAN*

        /* ========================================================= */


        /* =========================================================
           DATA TIDAK DITEMUKAN
        ========================================================= */

        function showNotFound(barcode) {
            const result = document.getElementById('scanResult');

            const safeBarcode = String(barcode).replace(/[&<>"']/g, function(char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                } [char];
            });

            result.innerHTML = `
        <div class="empty-scan">

            <div
                class="empty-scan-icon"
                style="background:#fff3f3; color:#dc3545;"
            >
                <i class="fa-solid fa-xmark"></i>
            </div>

            <strong>
                Material Tidak Ditemukan
            </strong>

            <span>
                Kode barang <b>${safeBarcode}</b>
                belum ditemukan pada database material.
            </span>

        </div>
    `;
        }

        /* =========================================================*

        *           RIWAYAT SCAN*

        /* ========================================================= */

        function addHistory(

            itemNumber,

            barcode

        ) {

            scanHistory.unshift({

                item: itemNumber,

                barcode: barcode,

                time: new Date().toLocaleTimeString(

                    'id-ID', {

                        hour: '2-digit',

                        minute: '2-digit'

                    }

                )

            });



            scanHistory =

                scanHistory.slice(0, 4);



            renderHistory();

        }



        function renderHistory() {

            const history =

                document.getElementById('scanHistory');



            if (!scanHistory.length) {

                history.innerHTML =

                    'Belum ada rekaman scan pada sesi ini.';

                history.className =

                    'history-empty';

                return;

            }



            history.className = 'history-list';



            history.innerHTML =

                scanHistory.map(scan => `

            <div class="history-item">

                <div>

                    <strong>

                        ${scan.item}

                    </strong>

                    <span>

                        ${scan.barcode}

                    </span>

                </div>

                <span>

                    ${scan.time}

                </span>

            </div>

        `).join('');

        }



        /* =========================================================*

        *           ENTER = SCAN*

        /* ========================================================= */

        document.addEventListener(
            'DOMContentLoaded',
            function() {
                const input = document.getElementById('barcodeInput');
                const cameraToggle = document.getElementById('cameraToggle');

                if (input) {
                    input.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            scanBarcode();
                        }
                    });
                }

                if (cameraToggle) {
                    cameraToggle.addEventListener('click', function() {
                        switchCamera();
                    });
                }

                const scannerBox = document.querySelector('.scanner-box');
                if (scannerBox) {
                    scannerBox.addEventListener('dblclick', function() {
                        if (!scannerRunning) startCameraScanner();
                    });
                }
            }
        );

        window.addEventListener('beforeunload', function() {
            stopCameraScanner();
        });
    </script>

@endsection
