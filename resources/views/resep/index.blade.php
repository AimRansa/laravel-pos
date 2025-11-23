@extends('layouts.admin')

@section('content')
<div class="container">

    <h3>Resep Menu: {{ $menu->nama_menu }}</h3>

    @if(session('success'))
        <div class="alert alert-success mt-2">{{ session('success') }}</div>
    @endif

    <form action="{{ route('resep.store', $menu->id_menu) }}" method="POST" class="mt-3">
        @csrf
        <div class="row">

            <div class="col-md-4">
                <label>Bahan (Produk)</label>
                <select name="id_produk" class="form-control" required>
                    <option value="">Pilih Produk</option>
                    @foreach($produk as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->nama_stok }} (Stok: {{ $p->jumlah_stok }} {{ $p->satuan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Takaran</label>
                <input type="number" step="0.01" name="takaran" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Satuan</label>
                <input type="text" name="satuan" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button class="btn btn-primary w-100">Tambah</button>
            </div>

        </div>
    </form>

    <hr>

    <h4>Daftar Resep</h4>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Bahan</th>
                <th>Takaran</th>
                <th>Satuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resep as $r)
            <tr>
                <td>{{ $r->produk->nama_stok }}</td>
                <td>{{ $r->takaran }}</td>
                <td>{{ $r->satuan }}</td>
                <td>
                    <form action="{{ route('resep.destroy', $r->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
