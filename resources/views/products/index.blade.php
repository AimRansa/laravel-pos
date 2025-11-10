@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3 class="mb-3">Stock</h3>

    <form action="{{ route('products.index') }}" method="GET" class="mb-3">
        <input type="text" name="search" class="form-control w-25 d-inline" placeholder="Cari produk..." value="{{ request('search') }}">
        <button class="btn btn-primary">Cari</button>
        <a href="{{ route('products.create') }}" class="btn btn-success float-end">+ Tambah Produk</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Produk</th>
                <th>Nama Produk</th>
                <th>Jumlah Stok</th>
                <th>Satuan</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Expired</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <td>{{ $product->id_produk }}</td>
                <td>{{ $product->nama_stok }}</td>
                <td>{{ $product->jumlah_stok }}</td>
                <td>{{ $product->satuan }}</td>
                <td>{{ $product->tanggal_masuk }}</td>
                <td>{{ $product->tanggal_expired }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data ini?')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data stok</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links() }}
</div>
@endsection
