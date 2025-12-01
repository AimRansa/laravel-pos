@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>📦 Daftar Transaksi</h3>

        @php
            $lastOrder = \App\Models\Order::orderByDesc('upload_at')->first();
        @endphp

        @if($lastOrder && $lastOrder->upload_at)
            <small class="text-muted">
                <i class="bi bi-clock-history"></i>
                CSV terakhir di-upload: 
                <strong>{{ $lastOrder->upload_at->format('d-m-Y H:i') }}</strong>
            </small>
        @endif
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label class="form-label">Tanggal Transaksi</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Cari ID Transaksi</label>
            <input type="text" name="search" placeholder="cth: 001" 
                   class="form-control" value="{{ request('search') }}">
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-md-2 d-flex align-items-end">
            <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Total Pesanan</th>
                <th>Total Harga</th>
                <th>Di-upload Pada</th>
                <th style="width: 90px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="fw-bold">{{ $order->idtransaksi }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d-m-Y') }}
                    </td>

                    <td>{{ $order->total_pesanan }}</td>

                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>

                    {{-- TANGGAL UPLOAD --}}
                    <td>
                        @if($order->upload_at)
                            <span class="badge bg-info text-dark">
                                {{ $order->upload_at->format('d-m-Y H:i') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('orders.show', $order->idtransaksi) }}" 
                           class="btn btn-info btn-sm">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-3">
                        Belum ada transaksi.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
