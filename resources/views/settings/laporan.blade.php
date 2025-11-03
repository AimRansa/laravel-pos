@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">📊 Laporan</h2>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="date" name="tanggal_laporan" class="form-control" value="{{ request('tanggal_laporan') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID Laporan</th>
                <th>Jumlah Transaksi</th>
                <th>Tanggal Laporan</th>
                <th>Total Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $item)
                <tr>
                    <td>{{ $item->id_laporan }}</td>
                    <td>{{ $item->jumlah_transaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_laporan)->format('d-m-Y') }}</td>
                    <td>{{ $item->total_stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
