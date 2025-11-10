@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Edit Stok Produk</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>ID Produk</label>
            <input type="text" class="form-control" value="{{ $product->id_produk }}" readonly maxlength="3">
        </div>

        <div class="mb-3">
            <label>Nama Produk</label>
            <input type="text" name="nama_stok" value="{{ $product->nama_stok }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Jumlah Stok</label>
            <input type="number" name="jumlah_stok" value="{{ $product->jumlah_stok }}" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label for="satuan">Satuan</label>
            <select name="satuan" id="satuan" class="form-control" required>
                <option value="">-- Pilih Satuan --</option>
                <option value="pcs" {{ $product->satuan == 'pcs' ? 'selected' : '' }}>pcs</option>
                <option value="kg" {{ $product->satuan == 'kg' ? 'selected' : '' }}>kg</option>
                <option value="liter" {{ $product->satuan == 'liter' ? 'selected' : '' }}>liter</option>
                <option value="pack" {{ $product->satuan == 'pack' ? 'selected' : '' }}>pack</option>
                <option value="dus" {{ $product->satuan == 'dus' ? 'selected' : '' }}>dus</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="{{ $product->tanggal_masuk }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" value="{{ $product->tanggal_expired }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
