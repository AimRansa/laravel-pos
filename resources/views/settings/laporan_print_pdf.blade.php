<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #444; padding: 6px; font-size: 12px; }
        th { background: #eee; }
        h3 { margin-bottom: 5px; }
    </style>
</head>
<body>

<h3>Laporan Cetak #{{ $laporan->id_laporan }}</h3>
<p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</p>
<p><strong>Jumlah Transaksi:</strong> {{ $laporan->jumlah_transaksi }}</p>
<p><strong>Total Stok Keluar:</strong> {{ $laporan->total_stok }}</p>

<h4>Menu Terjual</h4>
<table>
    <thead>
        <tr>
            <th>ID Menu</th>
            <th>Nama Menu</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detail as $d)
        <tr>
            <td>{{ $d->id_menu }}</td>
            <td>{{ $d->nama_menu }}</td>
            <td>{{ $d->quantity }}</td>
            <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<h4>Stok Keluar</h4>
<table>
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Jumlah</th>
            <th>Satuan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stok as $s)
        <tr>
            <td>{{ $s->nama_produk }}</td>
            <td>{{ $s->jumlah_berkurang }}</td>
            <td>{{ $s->satuan }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
