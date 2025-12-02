@extends('layouts.admin')
@section('content-header', 'Dashboard')

@section('content')

<style>
/* ========================= PREMIUM UI ========================= */

.stat-card {
    border-radius: 16px;
    padding: 30px;
    height: 180px;
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

.card {
    border-radius: 16px;
    animation: fadeIn .6s ease;
}
@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}

/* Slider/chart styles */
.chart-slider { position: relative; overflow: hidden; }
.chart-wrapper { display:flex; transition: transform .6s ease; }
.chart-slide { min-width:100%; padding:10px; }
.chart-slide canvas { max-height:270px !important; height:270px !important; }
.chart-btn { position:absolute; top:50%; transform:translateY(-50%); background:#009c59; color:#fff; border:none; padding:10px 14px; border-radius:50%; cursor:pointer; z-index:5; box-shadow:0 4px 10px rgba(0,0,0,0.2); }
.chart-btn:hover { background:#00b86a; }
.chart-btn.prev { left:10px; }
.chart-btn.next { right:10px; }

.badge-low { background:#d9534f; color:white; }
</style>

<div class="container-fluid">

    <!-- TOP STATS -->
    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#0062ff,#3c9dff);">
                <h5>Total Orders</h5>
                <h2 class="fw-bold">{{ $orders_count }}</h2>
                <small><a href="{{ route('orders.index') }}" class="text-white">More info →</a></small>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#009c59,#36df8a);">
                <h5>Total Income</h5>
                <h2 class="fw-bold">Rp {{ number_format($income,0,',','.') }}</h2>
                <small><a href="{{ route('orders.index') }}" class="text-white">More info →</a></small>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg,#c10000,#ff4141);">
                <h5>Need Restock (≤ 1)</h5>
                <div class="restock-box mt-2">
                    @if($need_restock->count())
                        @foreach($need_restock as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-white text-dark rounded">
                                <div>
                                    <strong>{{ $item->nama_stok }}</strong><br>
                                    <small>ID: {{ $item->id_produk }}</small>
                                </div>
                                <span class="badge badge-low">{{ $item->jumlah_stok }} {{ $item->satuan }}</span>
                            </div>
                        @endforeach
                    @else
                        <p class="text-white">Tidak ada produk kritis (≤ 1)</p>
                    @endif
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-sm">Manage Stock</a>
            </div>
        </div>
    </div>

    <!-- SLIDER CHARTS -->
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card shadow-sm p-3">
                <h5 class="fw-bold">Analytics Dashboard</h5>
                <div class="chart-slider mt-3">

                    <button class="chart-btn prev" onclick="prevSlide()">‹</button>
                    <button class="chart-btn next" onclick="nextSlide()">›</button>

                    <div class="chart-wrapper" id="chartWrapper">

                        <div class="chart-slide">
                            <h6 class="text-center mb-2">Top 10 Produk Berdasarkan Stok</h6>
                            <canvas id="chart1"></canvas>
                        </div>

                        <div class="chart-slide">
                            <h6 class="text-center mb-2">Stock Status (Low vs Sufficient)</h6>
                            <canvas id="chart2"></canvas>
                        </div>

                        <div class="chart-slide">
                            <h6 class="text-center mb-2">Jumlah Order (6 Bulan Terakhir)</h6>
                            <canvas id="chart3"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SALES CHART -->
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">Sales Overview (6 Months)</h5>
                    <canvas id="salesChart" height="90"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- BEST SELLING + LOW STOCK -->
    <div class="row mt-4">
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

        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold">Low Stock</h5>
                    <p class="text-muted" style="margin-top:-6px">Stok mendekati habis</p>

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

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* ========= AUTO SLIDE + PAUSE WHEN USER INTERACTS ========= */

let currentSlide = 0;
const totalSlides = 3;
const wrapper = document.getElementById('chartWrapper');

let autoSlideInterval;
let autoSlidePaused = false;
let resumeTimeout;

// tampilkan slide
function showSlide(i) {
    currentSlide = (i + totalSlides) % totalSlides;
    wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
}

// next/prev karena tombol user → auto slide pause
function nextSlide(){ pauseAutoSlide(); showSlide(currentSlide + 1); }
function prevSlide(){ pauseAutoSlide(); showSlide(currentSlide - 1); }

// mulai autoslide
function startAutoSlide() {
    autoSlideInterval = setInterval(() => {
        if (!autoSlidePaused) showSlide(currentSlide + 1);
    }, 5000);
}

// jeda auto-slide jika user interaksi
function pauseAutoSlide() {
    autoSlidePaused = true;
    clearInterval(autoSlideInterval);
    clearTimeout(resumeTimeout);

    // 10 detik tidak ada interaksi → auto jalan lagi
    resumeTimeout = setTimeout(() => {
        autoSlidePaused = false;
        startAutoSlide();
    }, 10000);
}

// swipe gesture
let startX = 0;
wrapper.addEventListener('touchstart', e => startX = e.touches[0].clientX);
wrapper.addEventListener('touchend', e => {
    const diff = e.changedTouches[0].clientX - startX;
    if (diff > 50) prevSlide();
    if (diff < -50) nextSlide();
});

// jalankan auto-slide saat halaman siap
startAutoSlide();

/* ========= CHARTS ========= */

const chart1Labels = {!! $chart1_labels !!};
const chart1Data = {!! $chart1_data !!};

const chart2Labels = {!! $chart2_labels !!};
const chart2Data = {!! $chart2_data !!};

const chart3Labels = {!! $chart3_labels !!};
const chart3Data = {!! $chart3_data !!};

const salesLabels = {!! $sales_labels !!};
const salesData = {!! $sales_data !!};

document.addEventListener('DOMContentLoaded', () => {

    new Chart(document.getElementById('chart1'), {
        type: 'bar',
        data: {
            labels: chart1Labels,
            datasets: [{ label: 'Jumlah Stok', data: chart1Data, backgroundColor: '#007bff' }]
        }
    });

    new Chart(document.getElementById('chart2'), {
        type: 'doughnut',
        data: {
            labels: chart2Labels,
            datasets: [{ data: chart2Data, backgroundColor: ['#dc3545','#198754'] }]
        }
    });

    new Chart(document.getElementById('chart3'), {
        type: 'bar',
        data: {
            labels: chart3Labels,
            datasets: [{ label: 'Jumlah Order', data: chart3Data, backgroundColor: '#009c59' }]
        }
    });

    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Pendapatan',
                data: salesData,
                borderColor:'#007bff',
                backgroundColor:'rgba(0,123,255,0.18)',
                tension:0.35,
                fill:true
            }]
        }
    });

});
</script>

@endsection
