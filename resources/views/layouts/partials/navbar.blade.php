<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #E5E5E5; border-bottom: 1px solid #d0d0d0;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link nav-link-custom" data-widget="pushmenu" href="{{ route('home') }}" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link nav-link-custom">{{ __('dashboard.title') }}</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        

        <!-- User Account Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link nav-link-custom" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i> {{ auth()->user()->getFullname() }}
            </a>
            <div class="dropdown-menu dropdown-menu-right custom-dropdown">
                <a href="{{ route('logout') }}" class="dropdown-item custom-dropdown-item logout-item"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> {{ __('common.Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<style>
    /* ========================================
       NAVBAR DEEEN COFFEE - PREMIUM STYLE
       ======================================== */

    /* Navbar Background */
    .main-header.navbar {
        background-color: #E5E5E5 !important;
        border-bottom: 1px solid #d0d0d0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 1rem;
    }

    /* Nav Links Custom */
    .nav-link-custom {
        color: #5a5a5a !important;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 8px;
        padding: 8px 16px !important;
        margin: 0 4px;
    }

    .nav-link-custom:hover {
        background-color: rgba(35, 69, 52, 0.1);
        color: #234534 !important;
        transform: translateY(-2px);
    }

    .nav-link-custom i {
        transition: all 0.3s ease;
    }

    .nav-link-custom:hover i {
        color: #234534 !important;
        transform: scale(1.1);
    }

    /* Hamburger Menu Button */
    .nav-link-custom[data-widget="pushmenu"] {
        font-size: 1.2rem;
    }

    .nav-link-custom[data-widget="pushmenu"]:hover {
        background-color: rgba(35, 69, 52, 0.15);
    }

    /* Dropdown Menu Custom */
    .custom-dropdown {
        background-color: #ffffff;
        border: 1px solid #d0d0d0;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        padding: 8px 0;
        margin-top: 8px;
        min-width: 220px;
    }

    /* Dropdown Items */
    .custom-dropdown-item {
        color: #5a5a5a !important;
        padding: 10px 20px;
        transition: all 0.3s ease;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .custom-dropdown-item:hover {
        background: linear-gradient(90deg, rgba(35, 69, 52, 0.1) 0%, transparent 100%);
        color: #234534 !important;
        padding-left: 24px;
    }

    .custom-dropdown-item i {
        color: #b0b0b0;
        transition: color 0.3s ease;
    }

    .custom-dropdown-item:hover i {
        color: #234534 !important;
    }

    /* Logout Item (Red) */
    .logout-item {
        color: #d9534f !important;
    }

    .logout-item:hover {
        background: linear-gradient(90deg, rgba(217, 83, 79, 0.1) 0%, transparent 100%);
        color: #c9302c !important;
    }

    .logout-item i {
        color: #d9534f !important;
    }

    .logout-item:hover i {
        color: #c9302c !important;
    }

    /* Dropdown Divider */
    .dropdown-divider {
        border-top: 1px solid #e5e5e5;
        margin: 8px 0;
    }

    /* User Name & Language Indicator */
    .nav-link-custom .fas.fa-user-circle,
    .nav-link-custom .fas.fa-globe {
        font-size: 1.1rem;
        margin-right: 6px;
    }

    /* Active State for Navbar Items */
    .navbar-nav .nav-item.active .nav-link-custom {
        background-color: rgba(35, 69, 52, 0.15);
        color: #234534 !important;
        font-weight: 600;
    }

    /* Responsive: Mobile View */
    @media (max-width: 768px) {
        .nav-link-custom {
            padding: 6px 12px !important;
            font-size: 0.9rem;
        }

        .custom-dropdown {
            min-width: 180px;
        }
    }

    /* Animation for Dropdown */
    .dropdown-menu.show {
        animation: dropdownFadeIn 0.3s ease;
    }

    @keyframes dropdownFadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>