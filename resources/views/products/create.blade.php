@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Tambah Stok Produk</h3>

    {{-- ✅ Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ⚠️ Notifikasi Error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>⚠️ {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>ID Produk (maksimal 3 karakter)</label>
            <input type="text" name="id_produk" value="{{ $newId }}" class="form-control" readonly maxlength="3">
        </div>

        <div class="mb-3">
            <label>Nama Stok</label>
            <input type="text" name="nama_stok" value="{{ old('nama_stok') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Stok</label>
            <input type="number" name="jumlah_stok" value="{{ old('jumlah_stok') }}" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label for="satuan">Satuan</label>
            <select name="satuan" id="satuan" class="form-control" required>
                <option value="">-- Pilih Satuan --</option>
                <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>pcs</option>
                <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>kg</option>
                <option value="liter" {{ old('satuan') == 'liter' ? 'selected' : '' }}>liter</option>
                <option value="pack" {{ old('satuan') == 'pack' ? 'selected' : '' }}>pack</option>
                <option value="dus" {{ old('satuan') == 'dus' ? 'selected' : '' }}>dus</option>
            </select>
        </div>


        <div class="mb-3">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" value="{{ old('tanggal_expired') }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
