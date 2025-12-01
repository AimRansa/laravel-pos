@extends('layouts.admin')

@section('content')
<div class="container mt-4">

    <h3>🧾 Detail Transaksi #{{ $order->idtransaksi }}</h3>

    <p><strong>Waktu Upload CSV:</strong>
        {{ $order->upload_at ? \Carbon\Carbon::parse($order->upload_at)->format('d-m-Y H:i') : '-' }}
    </p>

    <p><strong>Tanggal Transaksi (CSV):</strong>
        {{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d-m-Y') }}
    </p>

    <p><strong>Total Pesanan:</strong> {{ $order->total_pesanan }}</p>
    <p><strong>Total Harga:</strong> Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>

    <hr>

    <h5>📌 Daftar Menu</h5>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Menu</th>
                <th>Nama Menu</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>

        <tbody>
            @foreach($order->details as $d)
                <tr>
                    <td>{{ $d->id_menu }}</td>
                    <td>{{ $d->menu->nama_menu ?? '-' }}</td>
                    <td>{{ $d->quantity }}</td>
                    <td>Rp {{ number_format($d->menu->harga ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(($d->menu->harga ?? 0) * $d->quantity, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Kembali</a>

</div>
@endsection
