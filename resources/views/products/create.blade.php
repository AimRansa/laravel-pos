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
            <label>Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Keluar</label>
            <input type="date" name="tanggal_keluar" value="{{ old('tanggal_keluar') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Tanggal Expired</label>
            <input type="date" name="tanggal_expired" value="{{ old('tanggal_expired') }}" class="form-control" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
