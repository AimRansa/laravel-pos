@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h3>Laporan Harian</h3>

    {{-- Filter tanggal --}}
    <form class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="date" name="tanggal_laporan" class="form-control" 
                   value="{{ request('tanggal_laporan') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    {{-- Chart --}}
    <div class="mb-4">
        <canvas id="chartTop" height="120"></canvas>
    </div>

    {{-- Tabel laporan --}}
    <table class="table table-bordered">
        <thead class="table-secondary">
            <tr>
                <th>ID Laporan</th>
                <th>Jumlah Transaksi</th>
                <th>Tanggal Laporan</th>
                <th>Total Stok Keluar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>

            {{-- Gunakan variabel $laporans dari controller --}}
            @foreach($laporans as $l)
                <tr>
                    <td>{{ $l->id_laporan }}</td>
                    <td>{{ $l->jumlah_transaksi }}</td>
                    <td>{{ \Carbon\Carbon::parse($l->tanggal_laporan)->format('d-m-Y') }}</td>
                    <td>{{ $l->total_stok }}</td>
                    <td>
                        <a href="{{ route('laporan.show', $l->id_laporan) }}" 
                           class="btn btn-info btn-sm">Detail</a>

                        <a href="{{ route('laporan.print', $l->id_laporan) }}" 
                           target="_blank" class="btn btn-secondary btn-sm">Print</a>
                    </td>
                </tr>
            @endforeach

            {{-- Jika data kosong --}}
            @if($laporans->isEmpty())
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Belum ada laporan
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch("{{ route('laporan.chart.data') }}")
.then(r => r.json())
.then(json => {
    const ctx = document.getElementById('chartTop').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: json.top_menus.labels,
            datasets: [{
                label: 'Menu Terlaris (qty)',
                data: json.top_menus.data,
                borderWidth: 1
            }]
        },
        options: { 
            responsive: true, 
            maintainAspectRatio: false 
        }
    });
})
.catch(err => console.error(err));
</script>
@endsection
