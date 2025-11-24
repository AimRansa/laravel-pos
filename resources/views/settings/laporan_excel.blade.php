<table>
    <tr>
        <th colspan="4"><strong>Laporan #{{ $laporan->id_laporan }}</strong></th>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td colspan="3">{{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</td>
    </tr>
    <tr>
        <td>Jumlah Transaksi</td>
        <td colspan="3">{{ $laporan->jumlah_transaksi }}</td>
    </tr>
    <tr>
        <td>Total Stok Keluar</td>
        <td colspan="3">{{ $laporan->total_stok }}</td>
    </tr>

    <tr><td colspan="4"></td></tr>

    <tr><th colspan="4">Menu Terjual</th></tr>

    <tr>
        <th>ID Menu</th>
        <th>Nama Menu</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>

    @foreach($detail as $d)
        <tr>
            <td>{{ $d->id_menu }}</td>
            <td>{{ $d->nama_menu }}</td>
            <td>{{ $d->quantity }}</td>
            <td>{{ $d->subtotal }}</td>
        </tr>
    @endforeach

    <tr><td colspan="4"></td></tr>

    <tr><th colspan="4">Stok Keluar</th></tr>

    <tr>
        <th>Nama Produk</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th></th>
    </tr>

    @foreach($stok as $s)
        <tr>
            <td>{{ $s->nama_produk }}</td>
            <td>{{ $s->jumlah_berkurang }}</td>
            <td>{{ $s->satuan }}</td>
            <td></td>
        </tr>
    @endforeach
</table>
