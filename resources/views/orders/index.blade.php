@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3 class="mb-4">📦 Daftar Transaksi</h3>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Cari ID Transaksi</label>
            <input type="text" name="search" class="form-control" placeholder="cth: 001" value="{{ request('search') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal</th>
                <th>Total Pesanan</th>
                <th>Total Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="fw-bold">{{ $order->idtransaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td>{{ $order->total_pesanan }}</td>
                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->idtransaksi) }}" class="btn btn-info btn-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
