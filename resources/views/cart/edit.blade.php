@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Edit Menu</h3>

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

    <form action="{{ route('cart.update', $cart->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>ID Menu</label>
            <input type="text" class="form-control" value="{{ $cart->id_menu }}" readonly>
        </div>

        <div class="mb-3">
            <label>Nama Menu</label>
            <input type="text" name="nama_menu" class="form-control" value="{{ old('nama_menu', $cart->nama_menu) }}" required>
        </div>

        <div class="mb-3">
            <label>Takaran</label>
            <input type="text" name="takaran" class="form-control" value="{{ old('takaran', $cart->takaran) }}" required>
        </div>

        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $cart->harga) }}" required>
        </div>

        <button class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
