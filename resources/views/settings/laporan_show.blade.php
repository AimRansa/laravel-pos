@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">
            Detail Laporan #{{ $laporan->id_laporan }}
            ({{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }})
        </h3>

        <div>
            <a href="{{ route('laporan.export.pdf', $laporan->id_laporan) }}" 
               class="btn btn-danger btn-sm me-2">
                <i class="fa fa-file-pdf"></i> Export PDF
            </a>

            <a href="{{ route('laporan.export.excel', $laporan->id_laporan) }}" 
               class="btn btn-success btn-sm">
                <i class="fa fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Informasi Laporan -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Jumlah Transaksi:</strong> {{ $laporan->jumlah_transaksi }}</p>
            <p><strong>Total Stok Keluar:</strong> {{ $laporan->total_stok }}</p>
        </div>
    </div>

    <!-- Menu Terjual -->
    <h5 class="fw-bold">Menu Terjual</h5>
    <table class="table table-bordered table-hover rounded">
        <thead class="table-dark">
            <tr>
                <th>ID Menu</th>
                <th>Nama Menu</th>
                <th>Qty</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detail as $d)
                <tr>
                    <td>{{ $d->id_menu }}</td>
                    <td>{{ $d->nama_menu }}</td>
                    <td>{{ $d->quantity }}</td>
                    <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada menu terjual.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Stok Keluar -->
    <h5 class="fw-bold mt-4">Stok Keluar</h5>
    <table class="table table-bordered table-hover rounded">
        <thead class="table-dark">
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Berkurang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stok as $s)
                <tr>
                    <td>{{ $s->nama_produk }}</td>
                    <td>{{ $s->jumlah_berkurang }} {{ $s->satuan ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center text-muted">Tidak ada stok keluar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
