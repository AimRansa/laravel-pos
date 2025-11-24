@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>Laporan Cetak #{{ $laporan->id_laporan }}</h3>

    <p><strong>Tanggal:</strong> 
        {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}
    </p>

    <p><strong>Jumlah Transaksi:</strong> {{ $laporan->jumlah_transaksi }}</p>
    <p><strong>Total Stok Keluar:</strong> {{ $laporan->total_stok }}</p>

    <hr>

    <h5>Menu Terjual</h5>
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Nama Menu</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail as $d)
            <tr>
                <td>{{ $d->nama_menu }}</td>
                <td>{{ $d->quantity }}</td>
                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach

            @if($detail->isEmpty())
            <tr>
                <td colspan="3" class="text-center text-muted">Tidak ada detail transaksi.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <h5>Stok Keluar</h5>
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Berkurang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stok as $s)
            <tr>
                <td>{{ $s->nama_produk }}</td>
                <td>{{ $s->jumlah_berkurang }}</td>
            </tr>
            @endforeach

            @if($stok->isEmpty())
            <tr>
                <td colspan="2" class="text-center text-muted">Tidak ada data stok keluar.</td>
            </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection
