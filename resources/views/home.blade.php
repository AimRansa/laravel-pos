@extends('layouts.admin')
@section('content-header', 'Dashboard')

@section('content')

<style>
/* ========================= PREMIUM UI ========================= */

/* Kartu statistik atas */
.stat-card {
    border-radius: 16px;
    padding: 30px;
    height: 180px; /* DIPERBESAR agar sama seperti kotak merah */
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    transition: .3s cubic-bezier(.4,0,.2,1);
}
.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.18);
}

/* Scrollable restock list */
.restock-box {
    max-height: 260px;
    overflow-y: auto;
}

.restock-box::-webkit-scrollbar {
    width: 6px;
}
.restock-box::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

/* Card global */
.card {
    border-radius: 16px;
    animation: fadeIn .6s ease;
}

/* Fade animation */
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}

</style>


<div class="container-fluid">

    <!-- ======================= TOP STATS ======================= -->
    <div class="row">

        <!-- TOTAL ORDERS -->
        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#0062ff,#3c9dff);">
                <h5>Total Orders</h5>
                <h2 class="fw-bold">{{ $orders_count }}</h2>
                <small>
                    <a href="{{ route('orders.index') }}" class="text-white">More info →</a>
                </small>
            </div>
        </div>

        <!-- TOTAL INCOME -->
        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#009c59,#36df8a);">
                <h5>Total Income</h5>
                <h2 class="fw-bold">Rp {{ number_format($income,0,',','.') }}</h2>
                <small>
                    <a href="{{ route('orders.index') }}" class="text-white">More info →</a>
                </small>
            </div>
        </div>

        <!-- NEED RESTOCK -->
        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#c10000,#ff4141);">

                <h5>Need Restock (Critical Stock)</h5>

                <div class="restock-box mt-2">

                    @forelse ($low_stock_products as $item)
                        @if ($item->jumlah_stok < 0)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white text-dark rounded">
                                <div>
                                    <strong>{{ $item->nama_stok }}</strong><br>
                                    <small>ID: {{ $item->id_produk }}</small>
                                </div>
                                <span class="badge bg-danger">{{ $item->jumlah_stok }} {{ $item->satuan }}</span>
                            </div>
                        @endif
                    @empty
                        <p class="text-white">Semua stok aman</p>
                    @endforelse

                </div>

                <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">Manage Stock</a>
            </div>
        </div>

    </div>



    <!-- ======================= CHART ======================= -->
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">Sales Overview (6 Months)</h5>
                    <canvas id="salesChart" height="90"></canvas> <!-- Grafik DIKECILKAN -->
                </div>
            </div>
        </div>
    </div>




    <!-- ======================= BEST SELLING + LOW STOCK ======================= -->
    <div class="row mt-4">

        <!-- BEST SELLING -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">Best Selling (This Year)</h5>

                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th>ID Menu</th>
                                <th>Nama</th>
                                <th>Qty</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($best_selling_products as $p)
                                <tr>
                                    <td>{{ $p->id_menu }}</td>
                                    <td>{{ $p->nama_menu }}</td>
                                    <td><span class="badge bg-success">{{ $p->total_terjual }}</span></td>
                                    <td>Rp {{ number_format($p->total_pendapatan,0,',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- LOW STOCK -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h5 class="fw-bold">Low Stock</h5>
                    <p class="text-muted" style="margin-top:-6px">Stok < 300 (belum minus)</p>

                    @php
                        $low_stock_paginate = \App\Models\Product::where('jumlah_stok','<',300)
                            ->where('jumlah_stok','>=',0)
                            ->orderBy('jumlah_stok','asc')
                            ->paginate(5);
                    @endphp

                    <table class="table table-hover mt-2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Produk</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($low_stock_paginate as $p)
                                <tr>
                                    <td>{{ $p->id_produk }}</td>
                                    <td>{{ $p->nama_stok }}</td>
                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            {{ $p->jumlah_stok }} {{ $p->satuan }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-2">
                        {{ $low_stock_paginate->links() }}
                    </div>

                </div>
            </div>
        </div>

    </div>

</div>



<!-- ======================= CHART.JS ======================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {

    new Chart(document.getElementById("salesChart"), {
        type: "line",
        data: {
            labels: ["Jan","Feb","Mar","Apr","May","Jun"],
            datasets: [{
                label: "Pendapatan",
                data: [120000, 150000, 100000, 210000, 180000, 230000],
                borderColor: "#007bff",
                backgroundColor: "rgba(0,123,255,0.25)",
                tension: 0.35,
                borderWidth: 3,
                fill: true,
            }]
        },
        options: {
            animation: {
                duration: 1600,
                easing: "easeOutQuart"
            },
            scales: {
                y: {
                    beginAtZero: false,
                    grid: { color: "#efefef" }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

});
</script>

@endsection
