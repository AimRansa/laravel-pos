@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Tambah Menu</h3>

    {{-- ⚠️ Error Validation --}}
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

    <form action="{{ route('cart.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>ID Menu</label>
            <input type="text" name="id_menu" value="{{ $newId }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu') }}" required>
        </div>

        <div class="mb-3">
            <label>Takaran</label>
            <input type="text" name="takaran" class="form-control" value="{{ old('takaran') }}" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
