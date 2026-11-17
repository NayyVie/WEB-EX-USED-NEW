@extends('layouts.app-admin')

@section('title', 'Edit Katalog Spare Part')

@section('page-title', 'Edit Katalog Master')

@section('page-subtitle', 'PLN Nusantara Power Services')

@section('Profile', 'Admin')

@section('content')

    <style>
        /* =========================================
                                   EDIT KATALOG
                                   Mengikuti desain form katalog yang sudah ada
                                ========================================= */

        .edit-page {
            background: #ffffff;
            border: 1px solid #e8edf3;
            border-radius: 10px;
            box-shadow: 0 3px 12px rgba(15, 39, 70, .04);
            overflow: hidden;
        }

        .edit-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            padding: 22px 26px;

            border-bottom: 1px solid #e8edf3;

            background: #ffffff;
        }

        .edit-header h2 {
            margin: 0 0 5px;

            color: #123b68;

            font-size: 19px;
            font-weight: 700;
        }

        .edit-header p {
            margin: 0;

            color: #7b91a8;

            font-size: 11px;
            line-height: 1.5;
        }

        .edit-body {
            padding: 25px;
            background: #f7faff;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-row.three-column {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .form-row.four-column {
            grid-template-columns: repeat(4, 1fr);
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            color: #263d5b;

            font-size: 11px;
            font-weight: 600;
        }

        .form-group label span {
            color: #e53935;
        }

        .form-control {
            width: 100%;
            height: 38px;

            padding: 0 12px;

            border: 1px solid #dce4ef;
            border-radius: 7px;

            background: #ffffff;

            color: #263d5b;

            font-family: 'Poppins', sans-serif;
            font-size: 12px;

            outline: none;

            transition: .2s ease;

            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #0874c9;

            box-shadow: 0 0 0 3px rgba(8, 116, 201, .09);
        }

        textarea.form-control {
            height: auto;
            min-height: 82px;

            padding: 11px 13px;

            resize: vertical;

            line-height: 1.5;
        }

        select.form-control {
            cursor: pointer;
        }

        .edit-footer {
            display: flex;

            justify-content: flex-end;

            align-items: center;

            gap: 8px;

            padding: 15px 22px;

            border-top: 1px solid #e8edf3;

            background: #ffffff;
        }

        .btn-edit-cancel {
            height: 34px;

            padding: 0 13px;

            border-radius: 6px;

            border: none;

            background: #185bd1;

            color: #ffffff;

            font-family: inherit;

            font-size: 11px;

            cursor: pointer;
        }

        .btn-edit-save {
            height: 34px;

            padding: 0 15px;

            border: none;

            border-radius: 6px;

            background: #185bd1;

            color: #ffffff;

            font-family: inherit;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;
        }

        @media (max-width: 900px) {

            .form-row,
            .form-row.three-column,
            .form-row.four-column {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media (max-width: 600px) {

            .edit-body {
                padding: 18px;
            }

            .form-row,
            .form-row.three-column,
            .form-row.four-column {
                grid-template-columns: 1fr;
            }

            .edit-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-edit-cancel,
            .btn-edit-save {
                width: 100%;
            }

        }
    </style>



    <div class="edit-page">

        {{-- HEADER --}}
        <div class="edit-header">
            <div>
                <h2>Edit Data Spare Part</h2>
                <p>Perbarui data katalog material</p>
            </div>
        </div>

        {{-- FORM EDIT --}}
        <form action="{{ route('katalogAdmin.update', $item->kode_barang) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="edit-body">

                {{-- BARIS 1 --}}
                <div class="form-row">

                    <div class="form-group">
                        <label>Item Number</label>
                        <input type="text" class="form-control" value="{{ $item->kode_barang }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Kategori Material</label>
                        <select name="kategori" class="form-control">
                            <option value="">Pilih kategori</option>

                            @foreach (['General', 'Mechanical', 'Elektrikal', 'I&C', 'Lubricant', 'Consumable & Fastener', 'Safety & K3'] as $kategori)
                                <option value="{{ $kategori }}"
                                    {{ old('kategori', $item->kategori) == $kategori ? 'selected' : '' }}>
                                    {{ $kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- DESKRIPSI --}}
                <div class="form-group">
                    <label>Deskripsi Spare Part <span>*</span></label>

                    <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi', $item->deskripsi) }}</textarea>

                    @error('deskripsi')
                        <small style="color:red">{{ $message }}</small>
                    @enderror
                </div>

                {{-- BARIS 2 --}}
                <div class="form-row three-column">

                    <div class="form-group">
                        <label>Lokasi Bin / Rak</label>
                        <input type="text" name="lokasi_rak" class="form-control"
                            value="{{ old('lokasi_rak', $item->lokasi_rak) }}">
                    </div>

                    <div class="form-group">
                        <label>Satuan Ukur</label>
                        <select name="satuan" class="form-control">
                            <option value="">Pilih satuan</option>

                            @foreach (['PCS', 'SET', 'KG', 'LTR', 'MTR'] as $satuan)
                                <option value="{{ $satuan }}"
                                    {{ old('satuan', $item->satuan) == $satuan ? 'selected' : '' }}>
                                    {{ $satuan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Harga Satuan (IDR)</label>
                        <input type="number" name="harga_satuan" class="form-control" min="0" step="0.01"
                            value="{{ old('harga_satuan', $item->harga_satuan) }}">
                    </div>

                </div>

                {{-- BARIS 3 --}}
                <div class="form-row">

                    <div class="form-group">
                        <label>Stok Fisik</label>
                        <input type="number" name="stok_fisik" class="form-control" min="0"
                            value="{{ old('stok_fisik', $item->stok_fisik) }}">
                    </div>

                    <div class="form-group">
                        <label>Stok Maximo</label>
                        <input type="number" name="stok_maximo" class="form-control" min="0"
                            value="{{ old('stok_maximo', $item->stok_maximo) }}">
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="edit-footer">

                <a href="{{ route('katalogAdmin') }}" class="btn-edit-cancel"
                    style="text-decoration:none; display:flex; align-items:center; justify-content:center;">
                    Batal
                </a>

                <button type="submit" class="btn-edit-save">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>


@endsection
