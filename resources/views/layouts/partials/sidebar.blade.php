<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light elevation-4" style="background-color: #f8f9fa;">
    <!-- Brand Logo (ENHANCED - LEBIH BESAR & LUXURY) -->
    <div class="text-center py-4 logo-container">
        <div class="logo-wrapper">
            <img src="{{ asset('images/logo-deeen.png') }}" alt="Logo Deeen Coffee" 
                 class="logo-image">
            <div class="logo-glow"></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-4">
            <ul class="nav nav-pills nav-sidebar flex-column text-center" role="menu">

                {{-- Dashboard --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->is('admin') || request()->is('admin/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gauge"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Stock --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Stock</p>
                    </a>
                </li>

                {{-- Menu --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('cart.index') }}" class="nav-link {{ request()->is('admin/cart*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>Menu</p>
                    </a>
                </li>

                {{-- Transaksi --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-receipt"></i>
                        <p>Transaksi</p>
                    </a>
                </li>

                {{-- Laporan (FIXED ROUTE) --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('laporan.index') }}" 
                       class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                {{-- Upload CSV --}}
                <li class="nav-item mb-2">
                    <a href="{{ route('csv.upload.form') }}" class="nav-link {{ request()->is('admin/upload-csv') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-upload"></i>
                        <p>Upload CSV</p>
                    </a>
                </li>

                {{-- Logout --}}
                <li class="nav-item mt-3">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-arrow-right-from-bracket"></i>
                        <p>Logout</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>

<style>
    /* SEMUA CSS TETAP SAMA EXACT */
    /* =========================
       SIDEBAR STYLE DEEEN COFFEE
       LUXURY PREMIUM DESIGN
       ========================= */

    .main-sidebar {
        background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%) !important;
        color: #a0a0a0;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.08) !important;
    }

    .logo-container {
        padding: 28px 0 !important;
        position: relative;
        background: linear-gradient(135deg, #ffffff 0%, #f5f7f6 100%);
        border-bottom: 1px solid rgba(35, 69, 52, 0.1);
    }

    .logo-wrapper {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .logo-image {
        width: 180px !important;
        height: auto;
        border-radius: 24px;
        transition: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        z-index: 2;
        filter: drop-shadow(0 8px 16px rgba(35, 69, 52, 0.15));
    }

    .logo-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(35, 69, 52, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        opacity: 0;
        transition: all 0.6s ease;
        z-index: 1;
        filter: blur(20px);
    }

    .logo-wrapper:hover .logo-image {
        transform: scale(1.08) translateY(-3px);
        filter: drop-shadow(0 16px 32px rgba(35, 69, 52, 0.25));
        border-radius: 28px;
    }

    .logo-wrapper:hover .logo-glow {
        opacity: 1;
        width: 220px;
        height: 220px;
        animation: pulse-glow 2s ease-in-out infinite;
    }

    @keyframes pulse-glow {
        0%, 100% {
            transform: translate(-50%, -50%) scale(1);
            opacity: 0.6;
        }
        50% {
            transform: translate(-50%, -50%) scale(1.1);
            opacity: 0.8;
        }
    }

    .nav-sidebar .nav-item {
        margin: 5px 10px;
        border-radius: 12px;
    }

    .nav-sidebar .nav-link {
        display: flex;
        align-items: center;
        color: #b0b0b0 !important;
        border-radius: 12px;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 12px 16px;
        position: relative;
        overflow: hidden;
    }

    .nav-sidebar .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 0;
        height: 100%;
        background: linear-gradient(90deg, rgba(35, 69, 52, 0.1) 0%, transparent 100%);
        transition: width 0.4s ease;
        border-radius: 12px;
    }

    .nav-sidebar .nav-link:hover::before {
        width: 100%;
    }

    .nav-sidebar .nav-link:hover {
        background-color: rgba(35, 69, 52, 0.08);
        color: #234534 !important;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(35, 69, 52, 0.12);
    }

    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #234534 0%, #2d5940 100%) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(35, 69, 52, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .nav-sidebar .nav-link.active i {
        color: #ffffff !important;
        filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
        animation: icon-glow 2s ease-in-out infinite;
    }

    @keyframes icon-glow {
        0%, 100% {
            filter: drop-shadow(0 0 4px rgba(255, 255, 255, 0.3));
        }
        50% {
            filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.6));
        }
    }

</style>
