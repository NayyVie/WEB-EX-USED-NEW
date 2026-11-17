@extends('layouts.app')

@section('title', 'Edit Revisi')
@section('page-title', 'Perbaikan Laporan')
@section('page-subtitle', 'PLN Nusantara Power Services')

@section('content')

    <div class="revision-page">

        {{-- HEADER HALAMAN --}}
        <div class="revision-heading">
            <div>
                <div class="revision-breadcrumb">
                    <a href="{{ route('riwayatEmployee') }}">Riwayat Laporan</a>
                    <span>/</span>
                    <span>Perbaikan Laporan</span>
                </div>
                <h2>Perbaikan Laporan Ex Barang</h2>
                <p>Periksa catatan Admin Gudang dan perbaiki data laporan sebelum dikirim ulang.</p>
            </div>

            <div class="revision-id">
                <span>ID Laporan</span>
                <strong>RPT-{{ str_pad($laporan->id, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>
        </div>

        {{-- ALASAN REVISI --}}
        <div class="revision-alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-content">
                <strong>Laporan Memerlukan Perbaikan</strong>
                <p>{{ $laporan->alasan_penolakan ?: 'Admin Gudang meminta laporan ini diperiksa dan diperbaiki.' }}</p>
            </div>
        </div>

        {{-- PESAN VALIDASI --}}
        @if ($errors->any())
            <div class="revision-error">
                <strong>Periksa kembali data yang kamu masukkan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="revision-error">{{ session('error') }}</div>
        @endif

        <form action="{{ route('riwayatEmployee.revisi.update', $laporan->id) }}" method="POST"
            enctype="multipart/form-data" id="revisionForm">
            @csrf
            @method('PATCH')

            {{-- BAGIAN 1: INFORMASI LAPORAN --}}
            <section class="revision-card">
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h3>Informasi Laporan</h3>
                        <p>Periksa tanggal laporan dan bidang pekerjaan.</p>
                    </div>
                </div>

                <div class="revision-grid">
                    <div class="form-group">
                        <label>Tanggal Laporan <span>*</span></label>
                        <input type="date" name="tanggal_laporan"
                            value="{{ old('tanggal_laporan', \Illuminate\Support\Carbon::parse($laporan->tanggal_laporan)->format('Y-m-d')) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Bidang <span>*</span></label>
                        <select name="bidang" required>
                            <option value="">Pilih Bidang</option>
                            @foreach ([
            'mekanik' => 'Mekanik',
            'listrik' => 'Listrik',
            'controlling_instrumen' => 'Controlling Instrumen',
            'gudang' => 'Gudang',
        ] as $value => $label)
                                <option value="{{ $value }}" @selected(old('bidang', $laporan->bidang) == $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </section>

            {{-- BAGIAN 2: INFORMASI PEKERJAAN --}}
            <section class="revision-card">
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h3>Informasi Pekerjaan</h3>
                        <p>Data Work Order dan pekerjaan yang berkaitan dengan barang.</p>
                    </div>
                </div>

                <div class="revision-grid">
                    <div class="form-group">
                        <label>Nomor WO <span>*</span></label>
                        <input type="text" name="nomor_wo" value="{{ old('nomor_wo', $laporan->nomor_wo) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Pekerjaan <span>*</span></label>
                        <input type="text" name="nama_pekerjaan"
                            value="{{ old('nama_pekerjaan', $laporan->nama_pekerjaan) }}" required>
                    </div>
                </div>
            </section>

            {{-- BAGIAN 3: INFORMASI BARANG --}}
            <section class="revision-card">
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div>
                        <h3>Informasi Barang</h3>
                        <p>Perbaiki informasi barang yang akan dikembalikan ke gudang.</p>
                    </div>
                </div>

                <div class="revision-grid">
                    <div class="form-group">
                        <label>Item Number <span>*</span></label>
                        <input type="text" name="item_number" value="{{ old('item_number', $laporan->item_number) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Nama Barang <span>*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $laporan->nama_barang) }}"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Pengembalian <span>*</span></label>
                        <select name="jenis_pengembalian" required>
                            <option value="">Pilih Jenis</option>
                            <option value="barang_lebihan" @selected(old('jenis_pengembalian', $laporan->jenis_pengembalian) == 'barang_lebihan')>
                                Barang Lebihan
                            </option>
                            <option value="barang_pasca_pakai" @selected(old('jenis_pengembalian', $laporan->jenis_pengembalian) == 'barang_pasca_pakai')>
                                Barang Pasca Pakai
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kategori Barang <span>*</span></label>
                        <select name="kondisi_barang" required>
                            <option value="">Pilih Kategori</option>
                            <option value="investasi" @selected(old('kondisi_barang', $laporan->kondisi_barang) == 'investasi')>
                                Inventaris
                            </option>
                            <option value="operasi" @selected(old('kondisi_barang', $laporan->kondisi_barang) == 'operasi')>
                                Non Inventaris / Operasi
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Material Base <span>*</span></label>
                        <select name="material_base" required>
                            <option value="">Pilih Material</option>
                            @foreach (['besi/baja', 'aluminium', 'tembaga', 'plastik', 'campuran'] as $material)
                                <option value="{{ $material }}" @selected(old('material_base', $laporan->material_base) == $material)>
                                    {{ ucfirst($material) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Lokasi Gudang <span>*</span></label>
                        <input type="text" name="lokasi_gudang"
                            value="{{ old('lokasi_gudang', $laporan->lokasi_gudang) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Barang <span>*</span></label>
                        <input type="number" name="jumlah_barang" min="1"
                            value="{{ old('jumlah_barang', $laporan->jumlah_barang) }}" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan <span>*</span></label>
                        <select name="satuan" required>
                            <option value="">Pilih Satuan</option>
                            @foreach (['pcs', 'unit', 'meter', 'set', 'buah'] as $satuan)
                                <option value="{{ $satuan }}" @selected(old('satuan', $laporan->satuan) == $satuan)>
                                    {{ strtoupper($satuan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Estimasi Nilai Barang <span>*</span></label>
                        <input type="number" name="estimasi" min="0" step="0.01"
                            value="{{ old('estimasi', $laporan->estimasi) }}" required>
                    </div>
                </div>
            </section>

            {{-- BAGIAN 4: KONDISI DAN BUKTI --}}
            <section class="revision-card">
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div>
                        <h3>Kondisi dan Bukti Barang</h3>
                        <p>Periksa kondisi barang dan lampirkan foto bukti jika diperlukan.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status Kondisi Barang <span>*</span></label>
                    <select name="status_kondisi" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="rusak" @selected(old('status_kondisi', $laporan->status_kondisi) == 'rusak')>
                            Rusak
                        </option>
                        <option value="repairable" @selected(old('status_kondisi', $laporan->status_kondisi) == 'repairable')>
                            Dapat Diperbaiki
                        </option>
                        <option value="kehilangan_fungsi" @selected(old('status_kondisi', $laporan->status_kondisi) == 'kehilangan_fungsi')>
                            Kehilangan Fungsi
                        </option>
                    </select>
                </div>

                <div class="form-group photo-group">
                    <label>Foto Bukti Sebelumnya</label>

                    @if ($laporan->foto_bukti)
                        <div class="old-photo">
                            <img src="{{ asset('storage/' . $laporan->foto_bukti) }}" alt="Foto bukti laporan sebelumnya">
                            <span>Foto yang tersimpan saat pengajuan sebelumnya</span>
                        </div>
                    @else
                        <div class="photo-empty">Belum ada foto bukti sebelumnya.</div>
                    @endif
                </div>

                <div class="form-group">
                    <label>Unggah Foto Pengganti</label>
                    <div class="upload-box">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <strong>Pilih foto bukti baru</strong>
                        <span>Format JPG, JPEG, atau PNG. Maksimal 5 MB.</span>
                        <input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            id="revisionPhoto">
                        <small id="photoFileName">Tidak ada file baru dipilih. Foto lama akan tetap digunakan.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi / Keterangan</label>
                    <textarea name="deskripsi" rows="4" placeholder="Tambahkan keterangan kondisi barang...">{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                </div>
            </section>

            {{-- BAGIAN 5: TANDA TANGAN DAN PERNYATAAN --}}
            <section class="revision-card">
                <div class="section-heading">
                    <div class="section-icon">
                        <i class="fas fa-signature"></i>
                    </div>
                    <div>
                        <h3>Konfirmasi Pengiriman</h3>
                        <p>Berikan tanda tangan dan konfirmasi sebelum mengirim ulang laporan.</p>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tanda Tangan Worker <span>*</span></label>

                    <div class="revision-signature-wrapper">
                        <canvas id="signatureCanvas"></canvas>
                    </div>

                    <input type="hidden" name="tanda_tangan" id="tandaTanganInput" value="{{ old('tanda_tangan') }}">

                    <div class="revision-signature-footer">
                        <small>Silakan bubuhkan tanda tangan pada area di atas.</small>

                        <button type="button" id="clearSignature">
                            <i class="fas fa-eraser"></i>
                            Hapus TTD
                        </button>
                    </div>
                </div>

                <label class="revision-checkbox">
                    <input type="checkbox" name="pernyataan" value="1" required>
                    <span>
                        Saya menyatakan bahwa informasi dan bukti yang disampaikan dalam laporan ini
                        telah diperiksa dan sesuai dengan kondisi barang sebenarnya.
                    </span>
                </label>
            </section>

            {{-- TOMBOL AKSI --}}
            <div class="revision-actions">
                <a href="{{ route('riwayatEmployee') }}" class="btn-cancel">
                    Kembali
                </a>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Ulang Laporan
                </button>
            </div>

        </form>
    </div>

    <style>
        .revision-signature-wrapper {
            width: 100%;
            height: 180px;
            border: 1px solid #dce4ec;
            border-radius: 10px;
            background: #fbfcfe;
            overflow: hidden;
            cursor: crosshair;
        }

        #signatureCanvas {
            display: block;
            width: 100%;
            height: 100%;
            touch-action: none;
        }

        .revision-signature-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .revision-signature-footer small {
            color: #8997a8;
            font-size: 10px;
        }

        #clearSignature {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border: 1px solid #f1caca;
            border-radius: 8px;
            background: #fff5f5;
            color: #c34b4b;
            font-family: inherit;
            font-size: 10px;
            font-weight: 600;
            cursor: pointer;
        }

        #clearSignature:hover {
            background: #ffe9e9;
        }

        .revision-page {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 35px;
            color: #26384a;
            font-family: 'Poppins', sans-serif;
        }

        .revision-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 22px;
        }

        .revision-breadcrumb {
            display: flex;
            gap: 9px;
            align-items: center;
            margin-bottom: 10px;
            font-size: 12px;
            color: #8997a8;
        }

        .revision-breadcrumb a {
            color: #0874c9;
            text-decoration: none;
        }

        .revision-heading h2 {
            font-size: 23px;
            font-weight: 700;
            color: #173d50;
            margin: 0 0 5px;
        }

        .revision-heading p {
            margin: 0;
            font-size: 12px;
            color: #8997a8;
        }

        .revision-id {
            background: #eef7ff;
            border: 1px solid #d6eaff;
            border-radius: 12px;
            padding: 12px 18px;
            min-width: 150px;
            text-align: center;
        }

        .revision-id span {
            display: block;
            font-size: 10px;
            color: #70869a;
            margin-bottom: 4px;
        }

        .revision-id strong {
            font-size: 14px;
            color: #0874c9;
        }

        .revision-alert {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 18px 20px;
            margin-bottom: 20px;
            border: 1px solid #f3d59b;
            border-left: 4px solid #e9a23b;
            background: #fff9ed;
            border-radius: 12px;
        }

        .alert-icon {
            color: #d99022;
            font-size: 21px;
            padding-top: 2px;
        }

        .alert-content strong {
            display: block;
            color: #95651d;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .alert-content p {
            margin: 0;
            color: #826b46;
            font-size: 12px;
            line-height: 1.7;
        }

        .revision-card {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 15px;
            padding: 24px;
            margin-bottom: 18px;
            box-shadow: 0 4px 16px rgba(25, 55, 85, 0.035);
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            padding-bottom: 18px;
            margin-bottom: 20px;
            border-bottom: 1px solid #edf1f5;
        }

        .section-icon {
            width: 42px;
            height: 42px;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #eaf6ff;
            color: #0874c9;
            border-radius: 12px;
            font-size: 16px;
        }

        .section-heading h3 {
            font-size: 14px;
            font-weight: 700;
            color: #173d50;
            margin: 0 0 4px;
        }

        .section-heading p {
            font-size: 11px;
            color: #93a0af;
            margin: 0;
        }

        .revision-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 22px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 18px;
        }

        .revision-grid .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            color: #42566b;
            font-size: 11px;
            font-weight: 600;
        }

        .form-group label span {
            color: #e05252;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid #dce4ec;
            border-radius: 9px;
            background: #fbfcfe;
            padding: 11px 13px;
            color: #33475b;
            font-family: inherit;
            font-size: 12px;
            outline: none;
            transition: .2s ease;
        }

        .form-group input,
        .form-group select {
            min-height: 42px;
        }

        .form-group textarea {
            resize: vertical;
            line-height: 1.6;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #159fc5;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(21, 159, 197, .09);
        }

        .old-photo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px;
            background: #f8fafc;
            border: 1px solid #e8edf3;
            border-radius: 11px;
        }

        .old-photo img {
            width: 105px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e1e7ee;
        }

        .old-photo span {
            font-size: 11px;
            color: #78899a;
        }

        .photo-empty {
            padding: 18px;
            background: #f8fafc;
            border: 1px dashed #dce4ec;
            border-radius: 10px;
            color: #8997a8;
            font-size: 11px;
        }

        .upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            padding: 23px 15px;
            border: 1px dashed #a9d7eb;
            border-radius: 12px;
            background: #f7fcff;
            text-align: center;
        }

        .upload-box>i {
            font-size: 27px;
            color: #159fc5;
            margin-bottom: 3px;
        }

        .upload-box strong {
            color: #31576b;
            font-size: 12px;
        }

        .upload-box span,
        .upload-box small {
            color: #8a9aaa;
            font-size: 10px;
        }

        .upload-box input {
            max-width: 300px;
            border: none;
            background: transparent;
            font-size: 11px;
        }

        .field-hint {
            font-size: 10px;
            color: #91a0af;
        }

        .revision-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 15px;
            background: #f6faff;
            border: 1px solid #e2edf8;
            border-radius: 10px;
            color: #566b7e;
            font-size: 11px;
            line-height: 1.7;
            cursor: pointer;
        }

        .revision-checkbox input {
            margin-top: 3px;
            accent-color: #0874c9;
            flex-shrink: 0;
        }

        .revision-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            padding: 18px 0;
        }

        .btn-cancel,
        .btn-submit {
            min-height: 43px;
            padding: 0 20px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 9px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-cancel {
            background: #fff;
            color: #647789;
            border: 1px solid #dce4ec;
        }

        .btn-cancel:hover {
            background: #f5f8fb;
        }

        .btn-submit {
            border: none;
            color: #fff;
            background: linear-gradient(135deg, #0874c9, #009fc1);
            box-shadow: 0 4px 12px rgba(0, 130, 190, .18);
        }

        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 130, 190, .25);
        }

        .revision-error {
            padding: 15px 18px;
            margin-bottom: 18px;
            border: 1px solid #f2caca;
            background: #fff5f5;
            border-radius: 10px;
            color: #a83d3d;
            font-size: 12px;
        }

        .revision-error ul {
            padding-left: 20px;
            margin: 8px 0 0;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .revision-page {
                padding: 0 2px 25px;
            }

            .revision-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .revision-heading h2 {
                font-size: 19px;
            }

            .revision-id {
                text-align: left;
                width: 100%;
            }

            .revision-card {
                padding: 18px 15px;
            }

            .revision-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .revision-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
            }

            .old-photo {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // =====================================
            // FOTO BUKTI
            // =====================================
            const photoInput = document.getElementById('revisionPhoto');
            const photoFileName = document.getElementById('photoFileName');

            if (photoInput && photoFileName) {
                photoInput.addEventListener('change', function() {
                    photoFileName.textContent = this.files.length ?
                        'File dipilih: ' + this.files[0].name :
                        'Tidak ada file baru dipilih. Foto lama akan tetap digunakan.';
                });
            }

            // =====================================
            // CANVAS TANDA TANGAN
            // =====================================
            const canvas = document.getElementById('signatureCanvas');
            const hiddenInput = document.getElementById('tandaTanganInput');
            const clearButton = document.getElementById('clearSignature');
            const form = document.getElementById('revisionForm');

            if (!canvas || !hiddenInput || !form) return;

            const ctx = canvas.getContext('2d');

            let isDrawing = false;
            let hasSignature = false;

            function setupCanvas() {
                const rect = canvas.getBoundingClientRect();
                const ratio = window.devicePixelRatio || 1;

                canvas.width = rect.width * ratio;
                canvas.height = rect.height * ratio;

                ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

                ctx.lineWidth = 2.5;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.strokeStyle = '#173d50';
            }

            setupCanvas();

            function getPosition(event) {
                const rect = canvas.getBoundingClientRect();

                return {
                    x: event.clientX - rect.left,
                    y: event.clientY - rect.top
                };
            }

            canvas.addEventListener('pointerdown', function(event) {
                event.preventDefault();

                isDrawing = true;
                hasSignature = true;

                canvas.setPointerCapture(event.pointerId);

                const pos = getPosition(event);

                ctx.beginPath();
                ctx.moveTo(pos.x, pos.y);
                ctx.lineTo(pos.x + 0.1, pos.y + 0.1);
                ctx.stroke();
            });

            canvas.addEventListener('pointermove', function(event) {
                if (!isDrawing) return;

                event.preventDefault();

                const pos = getPosition(event);

                ctx.lineTo(pos.x, pos.y);
                ctx.stroke();
            });

            function stopDrawing() {
                isDrawing = false;
                ctx.beginPath();
            }

            canvas.addEventListener('pointerup', stopDrawing);
            canvas.addEventListener('pointercancel', stopDrawing);
            canvas.addEventListener('lostpointercapture', stopDrawing);

            // Hapus tanda tangan
            clearButton.addEventListener('click', function() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                hiddenInput.value = '';
                hasSignature = false;
            });

            // Simpan hasil canvas ke input hidden sebelum form dikirim
            form.addEventListener('submit', function(event) {
                if (!hasSignature) {
                    event.preventDefault();

                    alert('Silakan bubuhkan tanda tangan terlebih dahulu.');
                    return;
                }

                hiddenInput.value = canvas.toDataURL('image/png');

                if (!hiddenInput.value) {
                    event.preventDefault();
                    alert('Tanda tangan belum berhasil disimpan.');
                }
            });
        });
    </script>

@endsection
