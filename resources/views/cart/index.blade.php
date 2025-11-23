@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Daftar Menu</h3>

    {{-- ✅ Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 🔍 Form Pencarian --}}
    <form method="GET" action="{{ route('cart.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari ID atau Nama Menu...">
            <button class="btn btn-primary">Cari</button>
        </div>
    </form>

    {{-- 🔒 Tombol tambah di-nonaktifkan --}}
    {{-- <a href="{{ route('cart.create') }}" class="btn btn-success mb-3">+ Tambah Menu</a> --}}

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID Menu</th>
                <th>Nama Menu</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($carts as $cart)
                <tr>
                    <td>{{ $cart->id_menu }}</td>
                    <td>{{ $cart->nama_menu }}</td>
                    <td>Rp {{ number_format($cart->harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('cart.edit', $cart->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        {{-- 🔒 Tombol hapus dihapus --}}
                        <a href="{{ route('resep.index', $cart->id_menu) }}" 
                            class="btn btn-primary btn-sm mt-1">
                            Kelola Resep
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Keranjang kosong.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔢 Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $carts->links() }}
    </div>
</div>
@endsection
