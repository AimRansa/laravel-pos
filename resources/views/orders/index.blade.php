@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Daftar Transaksi</h3>

    {{-- ✅ Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID Transaksi</th>
                <th>Tanggal Transaksi</th>
                <th>Total Pesanan</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->idtransaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d-m-Y') }}</td>
                    <td>{{ $order->total_pesanan }}</td>
                    <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Belum ada transaksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- 🔢 Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $orders->links() }}
    </div>
</div>
@endsection
