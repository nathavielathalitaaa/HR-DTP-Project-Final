<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>HR | Sinergi Hotel & Vila - HR Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Minimal Admin & Dashboard Template" name="description">
    <meta content="Sinergi Hotel & Vila" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::to('assets/images/favicon.ico') }}">
    <!-- Layout config Js -->
    <script src="{{ URL::to('assets/js/layout.js') }}"></script>
    <!-- Sinergi Hotel & Vila CSS -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/starcode2.css') }}">
    
    <!-- Sinergi Hotel — Font Profesional -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
<!-- Sinergi Hotel & Vila — Brand Override -->
<style>

  /* =============================================
     FONT
  ============================================= */
  body, html, .font-public {
    font-family: 'Plus Jakarta Sans', 'Public Sans', sans-serif !important;
  }


  /* =============================================
     SIDEBAR — LATAR BELAKANG
  ============================================= */
  [data-sidebar="dark"] #sidebar-scrollable,
  [data-sidebar="dark"] .app-menu,
  [data-sidebar="dark"] nav.sidebar-nav {
    background-color: #1a3d2b !important;
  }

  [data-sidebar="dark"] .sidebar-header,
  [data-sidebar="dark"] #sidebar-logo-wrapper {
    background-color: #142e20 !important;
    border-bottom-color: #0d6b3a !important;
  }

  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:bg-vertical-menu-dark {
    background-color: #1a3d2b !important;
  }


  /* =============================================
     SIDEBAR — TEKS MENU NORMAL
  ============================================= */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:text-vertical-menu-item-dark {
    color: #c8d8c8 !important;
    font-weight: 400 !important;
  }

  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:text-vertical-menu-sub-item-dark {
    color: #b0c8b8 !important;
    font-weight: 400 !important;
  }

  /* label section (MENU UTAMA, HALAMAN) */
  [data-sidebar="dark"] .menu-title {
    color: #7aab87 !important;
  }


  /* =============================================
     SIDEBAR — DOT BULLET SUB MENU
  ============================================= */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:before\:bg-vertical-menu-sub-item-dark\/50::before {
    background-color: rgba(255, 255, 255, 0.25) !important;
  }


  /* =============================================
     SIDEBAR — HOVER (putih kusam, bukan hijau)
  ============================================= */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:hover\:bg-vertical-menu-item-bg-hover-dark:hover {
    background-color: rgba(255, 255, 255, 0.07) !important;
  }

  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:hover\:text-vertical-menu-item-hover-dark:hover,
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:hover\:text-vertical-menu-sub-item-hover-dark:hover {
    color: #ffffff !important;
  }

  /* dot bullet hover — putih */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:hover\:before\:bg-vertical-menu-sub-item-hover-dark:hover::before {
    background-color: rgba(255, 255, 255, 0.6) !important;
  }


  /* =============================================
     SIDEBAR — PARENT MENU AKTIF
     (background putih tipis)
  ============================================= */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:\[\&\.active\]\:bg-vertical-menu-item-bg-active-dark.active {
    background-color: rgba(255, 255, 255, 0.08) !important;
  }

  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:\[\&\.active\]\:text-vertical-menu-item-active-dark.active {
    color: #ffffff !important;
    font-weight: 600 !important;
  }


  /* =============================================
     SIDEBAR — SUB MENU AKTIF
     (HANYA bold, tidak ada background)
  ============================================= */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:\[\&\.active\]\:text-vertical-menu-sub-item-active-dark.active {
    color: #ffffff !important;
    font-weight: 700 !important;
    background-color: transparent !important;
  }

  /* dot bullet sub menu aktif — putih penuh */
  .group[data-sidebar=dark] .group-data-\[sidebar\=dark\]\:\[\&\.active\]\:before\:bg-vertical-menu-sub-item-active-dark.active::before {
    background-color: #ffffff !important;
  }


  /* =============================================
     AREA KONTEN — TETAP TERANG
  ============================================= */
  body {
    background-color: #f1f5f9 !important;
  }

  .card {
    background-color: #ffffff !important;
  }

  thead tr th {
    background-color: #f8fafc !important;
    color: #475569 !important;
  }


  /* =============================================
     AKSEN GLOBAL — BIRU DIGANTI HIJAU SINERGI
  ============================================= */

  /* tombol & background */
  .bg-custom-500 {
    background-color: #04A54C !important;
    border-color: #04A54C !important;
  }
  .bg-custom-600 { background-color: #038f40 !important; }
  .bg-custom-100 { background-color: #dcf5e7 !important; }
  .bg-custom-50  { background-color: #f0faf4 !important; }

  /* hover tombol */
  .hover\:bg-custom-600:hover,
  .hover\:bg-custom-700:hover,
  .btn.bg-custom-500:hover {
    background-color: #038f40 !important;
    border-color: #038f40 !important;
  }

  /* teks */
  .text-custom-500 { color: #04A54C !important; }
  .text-custom-600 { color: #038f40 !important; }
  .text-custom-100 { color: #dcf5e7 !important; }
  .hover\:text-custom-500:hover { color: #04A54C !important; }

  /* border */
  .border-custom-500      { border-color: #04A54C !important; }
  .border-custom-100      { border-color: #dcf5e7 !important; }
  .border-t-custom-500    { border-top-color: #04A54C !important; }
  .border-l-custom-500    { border-left-color: #04A54C !important; }
  .hover\:border-custom-600:hover { border-color: #038f40 !important; }

  /* fokus input */
  .focus\:border-custom-500:focus {
    border-color: #04A54C !important;
  }
  .focus\:ring-custom-500\/20:focus,
  .focus\:ring-custom-100:focus {
    --tw-ring-color: rgba(4, 165, 76, 0.2) !important;
  }

  /* active & checked */
  .active\:bg-custom-600:active      { background-color: #038f40 !important; }
  .checked\:bg-custom-500:checked    { background-color: #04A54C !important; }
  .checked\:border-custom-500:checked { border-color: #04A54C !important; }

  /* fill SVG / chart */
  .fill-custom-500\/50 { fill: rgba(4, 165, 76, 0.5) !important; }
  .fill-custom-400     { fill: #2dbd6e !important; }
  .fill-custom-300     { fill: #57cc8a !important; }
  .fill-custom-200     { fill: #93ddb0 !important; }
  .fill-custom-100     { fill: #dcf5e7 !important; }

  /* flatpickr date picker */
  .flatpickr-day.selected,
  .flatpickr-day.startRange,
  .flatpickr-day.endRange,
  .flatpickr-day.today {
    background-color: #04A54C !important;
    border-color: #04A54C !important;
  }

  /* swal2 confirm button */
  .swal2-confirm { background-color: #04A54C !important; }


  /*
     DESIGN SYSTEM — SINERGI HRIS
  */

  /* === STAT CARD === */
  .ds-card {
    background: #ffffff;
    border: 1px solid #e8f0e8;
    border-radius: 16px;
    padding: 20px 24px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
  }
  .ds-card::after {
    /* progress bar dekoratif di bawah card */
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    height: 3px; width: 100%;
    background: linear-gradient(to right, #04A54C, #dcf5e7);
    opacity: 0.6;
  }

  /* === STAT CARD — VARIAN WARNA PROGRESS BAR === */
  .ds-card.ds-warn::after  { background: linear-gradient(to right, #f59e0b, #fef3c7); }
  .ds-card.ds-info::after  { background: linear-gradient(to right, #3b82f6, #dbeafe); }
  .ds-card.ds-danger::after { background: linear-gradient(to right, #ef4444, #fee2e2); }

  /* === ICON BOX === */
  .ds-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .ds-icon.ic-green  { background: #dcf5e7; color: #04A54C; }
  .ds-icon.ic-blue   { background: #dbeafe; color: #2563eb; }
  .ds-icon.ic-amber  { background: #fef3c7; color: #d97706; }
  .ds-icon.ic-red    { background: #fee2e2; color: #dc2626; }
  .ds-icon.ic-purple { background: #ede9fe; color: #7c3aed; }
  .ds-icon.ic-teal   { background: #ccfbf1; color: #0d9488; }

  /* === BADGE LABEL === */
  .ds-badge {
    display: inline-flex; align-items: center;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 10px; font-weight: 700;
    letter-spacing: 0.05em; text-transform: uppercase;
  }
  .ds-badge.b-green  { background: #dcf5e7; color: #14532d; }
  .ds-badge.b-blue   { background: #dbeafe; color: #1e3a8a; }
  .ds-badge.b-amber  { background: #fef3c7; color: #78350f; }
  .ds-badge.b-red    { background: #fee2e2; color: #7f1d1d; }
  .ds-badge.b-gray   { background: #f1f5f9; color: #475569; }
  .ds-badge.b-purple { background: #ede9fe; color: #4c1d95; }

  /* === SECTION CARD (container dengan judul) === */
  .ds-section {
    background: #ffffff;
    border: 1px solid #e8f0e8;
    border-radius: 16px;
    padding: 20px 24px;
  }
  .ds-section-title {
    font-size: 11px; font-weight: 800;
    letter-spacing: 0.1em; text-transform: uppercase;
    color: #64748b;
    padding-left: 10px;
    border-left: 3px solid #04A54C;
    margin-bottom: 16px;
  }

  /* === LIST ROW (untuk daftar cuti, surat, dll) === */
  .ds-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
  }
  .ds-row:last-child { border-bottom: none; }

  /* === AVATAR INISIAL === */
  .ds-avatar {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
  }

  /* === BANNER WELCOME === */
  .ds-banner {
    background: linear-gradient(135deg, #04A54C 0%, #038f40 60%, #026d32 100%);
    color: white;
    border-radius: 20px;
    padding: 24px 32px;
    position: relative;
    overflow: hidden;
    margin-bottom: 24px;
  }
  .ds-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
  }
  .ds-banner::after {
    content: '';
    position: absolute;
    bottom: -30px; right: 100px;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
  }
  .ds-banner-info {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 14px;
    padding: 10px 18px;
    backdrop-filter: blur(4px);
  }

  /* === PAGE WRAPPER (ganti .sng) === */
  .ds-page {
    padding: 24px;
  }

  /* === TABLE === */
  .ds-table { width: 100%; border-collapse: collapse; }
  .ds-table thead th {
    background: #f8fdf9;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 12px 16px;
    border-bottom: 2px solid #e8f0e8;
    text-align: left;
  }
  .ds-table tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s;
  }
  .ds-table tbody tr:hover { background: #f8fdf9; }
  .ds-table tbody td { padding: 14px 16px; font-size: 14px; color: #334155; }

  /* === BUTTON === */
  .ds-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px; font-weight: 700;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
  }
  .ds-btn.btn-green {
    background: #04A54C; color: white;
  }
  .ds-btn.btn-outline {
    background: white; color: #04A54C;
    border: 1.5px solid #04A54C;
  }
  .ds-btn.btn-outline:hover { background: #f0faf4; }
  .ds-btn.btn-red { background: #fee2e2; color: #dc2626; }
  .ds-btn.btn-red:hover { background: #fecaca; }

</style>

<!-- style bawaan template — jangan diubah -->
<style>
  .invalid-feedback {
    color: red;
  }
  .is-invalid {
    border-color: red;
  }
  .choices {
    position: relative;
    overflow: hidden;
    margin-bottom: 0px !important;
    font-size: 16px;
  }
</style>
</head>
<body class="text-base bg-body-bg text-body font-public dark:text-zink-100 dark:bg-zink-800 group-data-[skin=bordered]:bg-body-bordered group-data-[skin=bordered]:dark:bg-zink-700">
    <div class="group-data-[sidebar-size=sm]:min-h-sm group-data-[sidebar-size=sm]:relative">
        <div class="app-menu w-vertical-menu bg-vertical-menu ltr:border-r rtl:border-l border-vertical-menu-border fixed bottom-0 top-0 z-[1003] transition-all duration-75 ease-linear group-data-[sidebar-size=md]:w-vertical-menu-md group-data-[sidebar-size=sm]:w-vertical-menu-sm group-data-[sidebar-size=sm]:pt-header group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar=dark]:border-vertical-menu-dark group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar=brand]:border-vertical-menu-brand group-data-[sidebar=modern]:bg-gradient-to-tr group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[layout=horizontal]:w-full group-data-[layout=horizontal]:bottom-auto group-data-[layout=horizontal]:top-header hidden md:block print:hidden group-data-[sidebar-size=sm]:absolute group-data-[sidebar=modern]:border-vertical-menu-border-modern group-data-[layout=horizontal]:dark:bg-zink-700 group-data-[layout=horizontal]:border-t group-data-[layout=horizontal]:dark:border-zink-500 group-data-[layout=horizontal]:border-r-0 group-data-[sidebar=dark]:dark:bg-zink-700 group-data-[sidebar=dark]:dark:border-zink-600 group-data-[layout=horizontal]:group-data-[navbar=scroll]:absolute group-data-[layout=horizontal]:group-data-[navbar=bordered]:top-[calc(theme('spacing.header')_+_theme('spacing.4'))] group-data-[layout=horizontal]:group-data-[navbar=bordered]:inset-x-4 group-data-[layout=horizontal]:group-data-[navbar=hidden]:top-0 group-data-[layout=horizontal]:group-data-[navbar=hidden]:h-16 group-data-[layout=horizontal]:group-data-[navbar=bordered]:w-[calc(100%_-_2rem)] group-data-[layout=horizontal]:group-data-[navbar=bordered]:[&.sticky]:top-header group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-md group-data-[layout=horizontal]:shadow-md group-data-[layout=horizontal]:shadow-slate-500/10 group-data-[layout=horizontal]:dark:shadow-zink-500/10 group-data-[layout=horizontal]:opacity-0">
            <div class="flex items-center justify-center px-5 text-center h-header !hidden group-data-[layout=horizontal]:hidden group-data-[sidebar-size=sm]:fixed group-data-[sidebar-size=sm]:top-0 group-data-[sidebar-size=sm]:bg-vertical-menu group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:bg-vertical-menu-dark group-data-[sidebar-size=sm]:group-data-[sidebar=brand]:bg-vertical-menu-brand group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-gradient-to-br group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:to-vertical-menu-to-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:from-vertical-menu-form-modern group-data-[sidebar-size=sm]:group-data-[sidebar=modern]:bg-vertical-menu-modern group-data-[sidebar-size=sm]:z-10 group-data-[sidebar-size=sm]:w-[calc(theme('spacing.vertical-menu-sm')_-_1px)] group-data-[sidebar-size=sm]:group-data-[sidebar=dark]:dark:bg-zink-700">
                <a href="{{ route('home') }}" class="group-data-[sidebar=dark]:hidden group-data-[sidebar=brand]:hidden group-data-[sidebar=modern]:hidden">
                    <span class="hidden group-data-[sidebar-size=sm]:block">
                        <img src="{{ URL::to('assets/images/logo.png') }}" alt="" class="h-6 mx-auto">
                    </span>
                    <span class="group-data-[sidebar-size=sm]:hidden">
                        <img src="{{ URL::to('assets/images/logo-dark.png') }}" alt="" class="h-6 mx-auto">
                    </span>
                </a>
                <a href="{{ route('home') }}" class="hidden group-data-[sidebar=dark]:block group-data-[sidebar=brand]:block group-data-[sidebar=modern]:block">
                    <span class="hidden group-data-[sidebar-size=sm]:block">
                        <img src="{{ URL::to('assets/images/logo.png') }}" alt="" class="h-6 mx-auto">
                    </span>
                    <span class="group-data-[sidebar-size=sm]:hidden">
                        <img src="{{ URL::to('assets/images/logo-light.png') }}" alt="" class="h-6 mx-auto">
                    </span>
                </a>
                <button type="button" class="hidden p-0 float-end" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>
            <!-- Left Sidebar -->
            <div id="scrollbar" class="group-data-[sidebar-size=md]:max-h-[calc(100vh_-_theme('spacing.header')_*_1.2)] group-data-[sidebar-size=lg]:max-h-[calc(100vh_-_theme('spacing.header')_*_1.2)] group-data-[layout=horizontal]:h-56 group-data-[layout=horizontal]:md:h-auto group-data-[layout=horizontal]:overflow-auto group-data-[layout=horizontal]:md:overflow-visible group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:mx-auto">
                @include('sidebar.sidebar')
                <!-- Left Sidebar End -->
            </div>
        </div>

        <!-- Left Sidebar End -->
        <div id="sidebar-overlay" class="absolute inset-0 z-[1002] bg-slate-500/30 hidden"></div>
        <header id="page-topbar" class="ltr:md:left-vertical-menu rtl:md:right-vertical-menu group-data-[sidebar-size=md]:ltr:md:left-vertical-menu-md group-data-[sidebar-size=md]:rtl:md:right-vertical-menu-md group-data-[sidebar-size=sm]:ltr:md:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:md:right-vertical-menu-sm group-data-[layout=horizontal]:ltr:left-0 group-data-[layout=horizontal]:rtl:right-0 fixed right-0 z-[1000] left-0 print:hidden group-data-[navbar=bordered]:m-4 group-data-[navbar=bordered]:[&.is-sticky]:mt-0 transition-all ease-linear duration-300 group-data-[navbar=hidden]:hidden group-data-[navbar=scroll]:absolute group/topbar group-data-[layout=horizontal]:z-[1004]">
            <div class="layout-width">
                <div class="flex items-center px-4 mx-auto bg-topbar border-b-2 border-topbar group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-brand shadow-md h-header shadow-slate-200/50 group-data-[navbar=bordered]:rounded-md group-data-[navbar=bordered]:group-[.is-sticky]/topbar:rounded-t-none group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:border-zink-700 dark:shadow-none group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-zink-500 group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-md group-data-[navbar=bordered]:shadow-none group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-none group-data-[layout=horizontal]:shadow-none group-data-[layout=horizontal]:dark:group-[.is-sticky]/topbar:shadow-none">
                    <div class="flex items-center w-full group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl navbar-header group-data-[layout=horizontal]:ltr:xl:pr-3 group-data-[layout=horizontal]:rtl:xl:pl-3">
                        <!-- LOGO -->
                        <div class="items-center justify-center hidden px-5 text-center h-header group-data-[layout=horizontal]:md:flex group-data-[layout=horizontal]:ltr::pl-0 group-data-[layout=horizontal]:rtl:pr-0">
                            <a href="{{ route('home') }}">
                                <span class="hidden">
                                    <img src="{{ URL::to('assets/images/logo.png') }}" alt="" class="h-6 mx-auto">
                                </span>
                                <span class="group-data-[topbar=dark]:hidden group-data-[topbar=brand]:hidden">
                                    <img src="{{ URL::to('assets/images/logo-dark.png') }}" alt="" class="h-6 mx-auto">
                                </span>
                            </a>
                            <a href="{{ route('home') }}" class="hidden group-data-[topbar=dark]:block group-data-[topbar=brand]:block">
                                <span class="group-data-[topbar=dark]:hidden group-data-[topbar=brand]:hidden">
                                    <img src="{{ URL::to('assets/images/logo.png') }}" alt="" class="h-6 mx-auto">
                                </span>
                                <span class="group-data-[topbar=dark]:block group-data-[topbar=brand]:block">
                                    <img src="{{ URL::to('assets/images/logo-light.png') }}" alt="" class="h-6 mx-auto">
                                </span>
                            </a>
                        </div>
        
                        <button type="button" class="inline-flex relative justify-center items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-75 ease-linear bg-topbar rounded-md btn hover:bg-slate-100 group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-dark group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-brand group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:dark:border-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[layout=horizontal]:flex group-data-[layout=horizontal]:md:hidden hamburger-icon" id="topnav-hamburger-icon">
                            <i data-lucide="chevrons-left" class="w-5 h-5 group-data-[sidebar-size=sm]:hidden"></i>
                            <i data-lucide="chevrons-right" class="hidden w-5 h-5 group-data-[sidebar-size=sm]:block"></i>
                        </button>
        
                        <div class="relative hidden ltr:ml-3 rtl:mr-3 lg:block group-data-[layout=horizontal]:hidden group-data-[layout=horizontal]:lg:block">
                            <input type="text" id="topbar-search" class="py-2 pr-4 text-sm text-topbar-item bg-topbar border border-topbar-border rounded pl-8 placeholder:text-slate-400 form-control focus-visible:outline-0 min-w-[300px] focus:border-blue-400 group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-border-dark group-data-[topbar=dark]:placeholder:text-slate-500 group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-border-brand group-data-[topbar=brand]:placeholder:text-blue-300 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:border-zink-500 group-data-[topbar=dark]:dark:text-zink-100" placeholder="cari karyawan atau departemen..." autocomplete="off">
                            <i data-lucide="search" class="inline-block size-4 absolute left-2.5 top-2.5 text-topbar-item fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:dark:fill-zink-600"></i>
                            <div id="search-results" style="display:none; position:absolute; top:42px; left:0; width:340px; background:#fff; border-radius:6px; box-shadow:0 4px 16px rgba(0,0,0,0.12); z-index:9999; max-height:320px; overflow-y:auto;" class="dark:bg-zink-700"></div>
                        </div>
        
                        <div class="flex gap-3 ms-auto">
                            <div class="relative flex items-center h-header">
                                <button type="button" class="inline-flex relative justify-center items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-200 ease-linear bg-topbar rounded-md btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:text-topbar-item-dark" id="light-dark-mode">
                                    <i data-lucide="sun" class="inline-block w-5 h-5 stroke-1 fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand"></i>
                                </button>
                            </div>
        
                            <div class="relative flex items-center dropdown h-header">
                                <button type="button" class="inline-flex justify-center relative items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-200 ease-linear bg-topbar rounded-md dropdown-toggle btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:text-topbar-item-dark" id="notificationDropdown" data-bs-toggle="dropdown">
                                    <i data-lucide="bell-ring" class="inline-block w-5 h-5 stroke-1 fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand"></i>
                                    @if(($totalNotif ?? 0) > 0)
                                    <span class="absolute top-0 right-0 flex w-1.5 h-1.5">
                                        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-sky-400"></span>
                                        <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                    </span>
                                    @endif
                                </button>
                                <div class="absolute z-50 hidden ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[20rem] lg:min-w-[26rem] dark:bg-zink-600" aria-labelledby="notificationDropdown">
                                    <div class="p-4">
                                        <h6 class="mb-4 text-16">notifikasi <span class="inline-flex items-center justify-center w-5 h-5 ml-1 text-[11px] font-medium border rounded-full text-white bg-orange-500 border-orange-500">{{ $totalNotif ?? 0 }}</span></h6>
                                        <p class="mb-3 text-sm text-slate-500 dark:text-zink-300">pengajuan cuti dan surat yang menunggu persetujuan</p>
                                    </div>
                                    <div data-simplebar="" class="max-h-[350px]">
                                        <div class="flex flex-col gap-1" id="notification-list">

                                            {{-- tampilkan notifikasi surat yang menunggu approval --}}
                                            @forelse($notifSurat ?? [] as $surat)
                                            <a href="{{ route('surat.show', $surat->id) }}" class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-md shrink-0" style="background:#e0f2fe;">
                                                    <i data-lucide="file-check" class="w-5 h-5" style="color:#0284c7;"></i>
                                                </div>
                                                <div class="grow">
                                                    <h6 class="mb-1 font-medium">{{ $surat->nomor_surat }}</h6>
                                                    <p class="mb-0 text-sm text-slate-500 dark:text-zink-300">
                                                        {{ substr($surat->perihal, 0, 35) }}{{ strlen($surat->perihal) > 35 ? '...' : '' }}
                                                    </p>
                                                    <p class="mb-0 text-xs text-slate-400 dark:text-zink-400">
                                                        <i data-lucide="clock" class="inline-block w-3 h-3 mr-1"></i>
                                                        {{ $surat->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                                    <div class="w-1.5 h-1.5 bg-blue-500 rounded-full"></div>
                                                </div>
                                            </a>
                                            @empty
                                            @endforelse

                                            {{-- tampilkan notifikasi cuti yang menunggu --}}
                                            @forelse($notifCuti ?? [] as $cuti)
                                            <a href="{{ route('hr/leave/employee/page') }}" class="flex gap-3 p-4 product-item hover:bg-slate-50 dark:hover:bg-zink-500">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-md shrink-0" style="background:#fef9c3;">
                                                    <i data-lucide="calendar-clock" class="w-5 h-5" style="color:#ca8a04;"></i>
                                                </div>
                                                <div class="grow">
                                                    <h6 class="mb-1 font-medium">{{ $cuti->employee_name ?? 'karyawan' }} mengajukan cuti</h6>
                                                    <p class="mb-0 text-sm text-slate-500 dark:text-zink-300">
                                                        jenis: {{ $cuti->leave_type }} &bull; {{ $cuti->number_of_day }} hari
                                                    </p>
                                                    <p class="mb-0 text-xs text-slate-400 dark:text-zink-400">
                                                        <i data-lucide="clock" class="inline-block w-3 h-3 mr-1"></i>
                                                        {{ $cuti->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <div class="flex items-center self-start gap-2 text-xs text-slate-500 shrink-0 dark:text-zink-300">
                                                    <div class="w-1.5 h-1.5 bg-custom-500 rounded-full"></div>
                                                </div>
                                            </a>
                                            @empty
                                            {{-- kalau tidak ada notifikasi --}}
                                            <div class="p-6 text-center">
                                                <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                                <p class="text-sm text-slate-500 dark:text-zink-300">tidak ada notifikasi baru</p>
                                            </div>
                                            @endforelse

                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 p-4 border-t border-slate-200 dark:border-zink-500">
                                        <div class="grow">
                                            <a href="javascript:void(0);">kelola notifikasi</a>
                                        </div>
                                        <div class="shrink-0">
                                            <a href="{{ route('hr/leave/employee/page') }}" type="button" class="px-2 py-1.5 text-xs text-white transition-all duration-200 ease-linear btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100">lihat semua notifikasi <i data-lucide="move-right" class="inline-block w-3.5 h-3.5 ml-1"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
        
                            <div class="relative items-center hidden h-header md:flex">
                                <button data-drawer-target="customizerButton" type="button" class="inline-flex justify-center items-center p-0 text-topbar-item transition-all w-[37.5px] h-[37.5px] duration-200 ease-linear bg-topbar group-data-[topbar=dark]:text-topbar-item-dark rounded-md btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200">
                                    <i data-lucide="settings" class="inline-block w-5 h-5 stroke-1 fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand"></i>
                                </button>
                            </div>
        
                            <div class="relative flex items-center dropdown h-header">
                                <button type="button" class="inline-block p-0 transition-all duration-200 ease-linear bg-topbar rounded-full text-topbar-item dropdown-toggle btn hover:bg-topbar-item-bg-hover hover:text-topbar-item-hover group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:hover:bg-topbar-item-bg-hover-dark group-data-[topbar=dark]:hover:text-topbar-item-hover-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:hover:bg-topbar-item-bg-hover-brand group-data-[topbar=brand]:hover:text-topbar-item-hover-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:hover:bg-zink-600 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:hover:text-zink-50 group-data-[topbar=dark]:dark:text-zink-200" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <div class="bg-pink-100 rounded-full">
                                        @if(!empty(Session::get('avatar')))
                                            <img src="{{ URL::to('assets/images/user/'.Session::get('avatar')) }}" alt="" class="w-[37.5px] h-[37.5px] rounded-full">
                                        @else  
                                            <div class="flex items-center justify-center font-medium rounded-full size-10 shrink-0 bg-slate-200 text-slate-800 dark:text-zink-50 dark:bg-zink-600">
                                                @php
                                                $fullName = Session::get('name');
                                                    $parts = explode(' ', $fullName);
                                                    $initials = '';
                                                    foreach ($parts as $part) {
                                                        $initials .= strtoupper(substr($part, 0, 1));
                                                    }
                                                @endphp
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                </button>
                                <div class="absolute z-50 hidden p-4 ltr:text-left rtl:text-right bg-white rounded-md shadow-md !top-4 dropdown-menu min-w-[14rem] dark:bg-zink-600" aria-labelledby="dropdownMenuButton">
                                    <h6 class="mb-2 text-sm font-normal text-slate-500 dark:text-zink-300">selamat datang, {{ Session::get('name') }}</h6>
                                    <a href="#!" class="flex gap-3 mb-3">
                                        <div class="relative inline-block shrink-0">
                                            <div class="rounded bg-slate-100 dark:bg-zink-500">
                                                @if(!empty(Session::get('avatar')))
                                                    <img src="{{ URL::to('assets/images/user/'.Session::get('avatar')) }}" alt="" class="w-[37.5px] h-[37.5px] rounded-full">
                                                @else  
                                                    <div class="flex items-center justify-center font-medium rounded-full size-10 shrink-0 bg-slate-200 text-slate-800 dark:text-zink-50 dark:bg-zink-600">
                                                        @php
                                                        $fullName = Session::get('name');
                                                            $parts = explode(' ', $fullName);
                                                            $initials = '';
                                                            foreach ($parts as $part) {
                                                                $initials .= strtoupper(substr($part, 0, 1));
                                                            }
                                                        @endphp
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="-top-1 ltr:-right-1 rtl:-left-1 absolute w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full dark:border-zink-600"></span>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-15">{{ Session::get('name') }}</h6>
                                            <p class="text-slate-500 dark:text-zink-300">{{ Session::get('position') }}</p>
                                        </div>
                                    </a>
                                    <ul>
                                        <li>
                                            <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500" href="{{ url('page/account/'.Session::get('user_id')) }}">
                                                <i data-lucide="user-2" class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i> profil saya
                                            </a>
                                        </li>
                                        <li>
                                            <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500" href="apps-mailbox.html">
                                                <i data-lucide="mail" class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i> kotak masuk 
                                                <span class="inline-flex items-center justify-center w-5 h-5 ltr:ml-2 rtl:mr-2 text-[11px] font-medium border rounded-full text-white bg-red-500 border-red-500">15</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500" href="apps-chat.html">
                                                <i data-lucide="messages-square" class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i> Chat
                                            </a>
                                        </li>
                                        <li class="pt-2 mt-2 border-t border-slate-200 dark:border-zink-500">
                                            <a class="block ltr:pr-4 rtl:pl-4 py-1.5 text-base font-medium transition-all duration-200 ease-linear text-slate-600 dropdown-item hover:text-custom-500 focus:text-custom-500 dark:text-zink-200 dark:hover:text-custom-500 dark:focus:text-custom-500" href="{{ route('logout') }}">
                                                <i data-lucide="log-out" class="inline-block size-4 ltr:mr-2 rtl:ml-2"></i> keluar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    
   
        <div class="relative min-h-screen group-data-[sidebar-size=sm]:min-h-sm">
            <!-- Page-content -->
            @yield('content')
            <!-- End Page-content -->

            <!-- Page-footer -->
            <footer class="ltr:md:left-vertical-menu rtl:md:right-vertical-menu group-data-[sidebar-size=md]:ltr:md:left-vertical-menu-md group-data-[sidebar-size=md]:rtl:md:right-vertical-menu-md group-data-[sidebar-size=sm]:ltr:md:left-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:md:right-vertical-menu-sm absolute right-0 bottom-0 px-4 h-14 group-data-[layout=horizontal]:ltr:left-0  group-data-[layout=horizontal]:rtl:right-0 left-0 border-t py-3 flex items-center dark:border-zink-600">
                <div class="group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl w-full">
                    <div class="grid items-center grid-cols-1 text-center lg:grid-cols-2 text-slate-400 dark:text-zink-200 ltr:lg:text-left rtl:lg:text-right">
                        <div>
                            <script>document.write(new Date().getFullYear())</script> Sinergi Hotel & Vila
                        </div>
                        <div class="hidden lg:block">
                            <div class="ltr:text-right rtl:text-left">
                                Design & Develop by Sinergi Hotel & Vila
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End Page-footer -->
        </div>
    </div>
    <!-- end main content -->
    <div class="fixed items-center hidden bottom-6 right-12 h-header group-data-[navbar=hidden]:flex">
        <button data-drawer-target="customizerButton" type="button" class="inline-flex items-center justify-center w-12 h-12 p-0 transition-all duration-200 ease-linear rounded-md shadow-lg text-sky-50 bg-sky-500">
            <i data-lucide="settings" class="inline-block w-5 h-5"></i>
        </button>
    </div>

    <div id="customizerButton" drawer-end="" class="fixed inset-y-0 flex flex-col w-full transition-transform duration-300 ease-in-out transform bg-white shadow ltr:right-0 rtl:left-0 md:w-96 z-drawer show dark:bg-zink-600">
        <div class="flex justify-between p-4 border-b border-slate-200 dark:border-zink-500">
            <div class="grow">
                <h5 class="mb-1 text-16">Sinergi Hotel & Vila Theme Customizer</h5>
                <p class="font-normal text-slate-500 dark:text-zink-200">Choose your themes & layouts etc.</p>
            </div>
            <div class="shrink-0">
                <button data-drawer-close="customizerButton" class="transition-all duration-150 ease-linear text-slate-500 hover:text-slate-800 dark:text-zink-200 dark:hover:text-zink-50"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        </div>
        <div class="h-full p-6 overflow-y-auto">
            <div>
                <h5 class="mb-3 underline capitalize text-15">Choose Layouts</h5>
                <div class="grid grid-cols-1 mb-5 gap-7 sm:grid-cols-2">
                    <div class="relative">
                        <input id="layout-one" name="dataLayout" class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500" type="radio" value="vertical" checked="">
                        <label class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500" for="layout-one">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block h-3 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Vertical</h5>
                    </div>

                    <div class="relative">
                        <input id="layout-two" name="dataLayout" class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500" type="radio" value="horizontal">
                        <label class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500" for="layout-two">
                            <span class="flex flex-col h-full gap-1">
                                <span class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-zink-500">
                                    <span class="block p-1 ml-1 bg-white rounded dark:bg-zink-500"></span>
                                    <span class="block p-1 px-2 pb-0 bg-white dark:bg-zink-500 ms-auto"></span>
                                    <span class="block p-1 px-2 pb-0 bg-white dark:bg-zink-500"></span>
                                </span>
                                <span class="block p-1 bg-slate-100 dark:bg-zink-500"></span>
                                <span class="block p-1 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Horizontal</h5>
                    </div>
                </div>

                <div id="semi-dark">
                    <div class="flex items-center">
                        <div class="relative inline-block w-10 mr-2 align-middle transition duration-200 ease-in">
                            <input type="checkbox" name="customDefaultSwitch" value="dark" id="customDefaultSwitch" class="absolute block w-5 h-5 transition duration-300 ease-linear border-2 rounded-full appearance-none cursor-pointer border-slate-200 bg-white/80 peer/published checked:bg-white checked:right-0 checked:border-custom-500 arrow-none dark:border-zink-500 dark:bg-zink-500 dark:checked:bg-zink-400 checked:bg-none">
                            <label for="customDefaultSwitch" class="block h-5 overflow-hidden transition duration-300 ease-linear border rounded-full cursor-pointer border-slate-200 bg-slate-200 peer-checked/published:bg-custom-500 peer-checked/published:border-custom-500 dark:border-zink-500 dark:bg-zink-600"></label>
                        </div>
                        <label for="customDefaultSwitch" class="inline-block text-base font-medium">Semi Dark (Sidebar & Header)</label>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-skin="" -->
                <h5 class="mb-3 underline capitalize text-15">Skin Layouts</h5>
                <div class="grid grid-cols-1 mb-5 gap-7 sm:grid-cols-2">
                    <div class="relative">
                        <input id="layoutSkitOne" name="dataLayoutSkin" class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500" type="radio" value="default">
                        <label class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500 bg-slate-50 dark:bg-zink-600" for="layoutSkitOne">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block h-3 mt-auto bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Default</h5>
                    </div>
            
                    <div class="relative">
                        <input id="layoutSkitTwo" name="dataLayoutSkin" class="absolute w-4 h-4 border rounded-full appearance-none cursor-pointer ltr:right-2 rtl:left-2 top-2 vertical-menu-btn bg-slate-100 border-slate-300 checked:bg-custom-500 checked:border-custom-500 dark:bg-zink-400 dark:border-zink-500" type="radio" value="bordered" checked="">
                        <label class="block w-full h-24 p-0 overflow-hidden border rounded-lg cursor-pointer border-slate-200 dark:border-zink-500" for="layoutSkitTwo">
                            <span class="flex h-full gap-0">
                                <span class="shrink-0">
                                    <span class="flex flex-col h-full gap-1 p-1 ltr:border-r rtl:border-l border-slate-200 dark:border-zink-500">
                                        <span class="block p-1 px-2 mb-2 rounded bg-slate-100 dark:bg-zink-400"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                        <span class="block p-1 px-2 pb-0 bg-slate-100 dark:bg-zink-500"></span>
                                    </span>
                                </span>
                                <span class="grow">
                                    <span class="flex flex-col h-full">
                                        <span class="block h-3 border-b border-slate-200 dark:border-zink-500"></span>
                                        <span class="block h-3 mt-auto border-t border-slate-200 dark:border-zink-500"></span>
                                    </span>
                                </span>
                            </span>
                        </label>
                        <h5 class="mt-2 text-center text-15">Bordered</h5>
                    </div>
                </div>
            </div>
            
            <div class="mt-6">
                <!-- data-mode="" -->
                <h5 class="mb-3 underline capitalize text-15">Light & Dark</h5>
                <div class="flex gap-3">
                    <button type="button" id="dataModeOne" name="dataMode" value="light" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Light Mode</button>
                    <button type="button" id="dataModeTwo" name="dataMode" value="dark" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Dark Mode</button>
                </div>
            </div>

            <div class="mt-6">
                <!-- dir="ltr" -->
                <h5 class="mb-3 underline capitalize text-15">LTR & RTL</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="diractionOne" name="dir" value="ltr" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">LTR Mode</button>
                    <button type="button" id="diractionTwo" name="dir" value="rtl" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">RTL Mode</button>
                </div>
            </div>

            <div class="mt-6">
                <!-- data-content -->
                <h5 class="mb-3 underline capitalize text-15">Content Width</h5>
                <div class="flex gap-3">
                    <button type="button" id="datawidthOne" name="datawidth" value="fluid" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Fluid</button>
                    <button type="button" id="datawidthTwo" name="datawidth" value="boxed" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Boxed</button>
                </div>
            </div>

            <div class="mt-6" id="sidebar-size">
                <!-- data-sidebar-size="" -->
                <h5 class="mb-3 underline capitalize text-15">Sidebar Size</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="sidebarSizeOne" name="sidebarSize" value="lg" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Default</button>
                    <button type="button" id="sidebarSizeTwo" name="sidebarSize" value="md" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Compact</button>
                    <button type="button" id="sidebarSizeThree" name="sidebarSize" value="sm" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Small (Icon)</button>
                </div>
            </div>

            <div class="mt-6" id="navigation-type">
                <!-- data-navbar="" -->
                <h5 class="mb-3 underline capitalize text-15">Navigation Type</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="navbarTwo" name="navbar" value="sticky" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500 active">Sticky</button>
                    <button type="button" id="navbarOne" name="navbar" value="scroll" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Scroll</button>
                    <button type="button" id="navbarThree" name="navbar" value="bordered" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Bordered</button>
                    <button type="button" id="navbarFour" name="navbar" value="hidden" class="transition-all duration-200 ease-linear bg-white border-dashed text-slate-500 btn border-slate-200 hover:text-slate-500 hover:bg-slate-50 hover:border-slate-200 [&.active]:text-custom-500 [&.active]:bg-custom-50 [&.active]:border-custom-200 dark:bg-zink-600 dark:text-zink-200 dark:border-zink-400 dark:hover:bg-zink-600 dark:hover:text-zink-100 dark:hover:border-zink-400 dark:[&.active]:bg-custom-500/10 dark:[&.active]:border-custom-500/30 dark:[&.active]:text-custom-500">Hidden</button>
                </div>
            </div>

            <div class="mt-6" id="sidebar-color">
                <!-- data-sidebar="" light, dark, brand, modern-->
                <h5 class="mb-3 underline capitalize text-15">Sizebar Colors</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="sidebarColorOne" name="sidebarColor" value="light" class="flex items-center justify-center w-10 h-10 bg-white border rounded-md border-slate-200 group active"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-slate-600"></i></button>
                    <button type="button" id="sidebarColorTwo" name="sidebarColor" value="dark" class="flex items-center justify-center w-10 h-10 border rounded-md border-zink-900 bg-zink-900 group"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="sidebarColorThree" name="sidebarColor" value="brand" class="flex items-center justify-center w-10 h-10 border rounded-md border-custom-800 bg-custom-800 group"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="sidebarColorFour" name="sidebarColor" value="modern" class="flex items-center justify-center w-10 h-10 border rounded-md border-purple-950 bg-gradient-to-t from-red-400 to-purple-500 group"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                </div>
            </div>
            
            <div class="mt-6">
                <!-- data-topbar="" light, dark, brand, modern-->
                <h5 class="mb-3 underline capitalize text-15">Topbar Colors</h5>
                <div class="flex flex-wrap gap-3">
                    <button type="button" id="topbarColorOne" name="topbarColor" value="light" class="flex items-center justify-center w-10 h-10 bg-white border rounded-md border-slate-200 group active"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-slate-600"></i></button>
                    <button type="button" id="topbarColorTwo" name="topbarColor" value="dark" class="flex items-center justify-center w-10 h-10 border rounded-md border-zink-900 bg-zink-900 group"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                    <button type="button" id="topbarColorThree" name="topbarColor" value="brand" class="flex items-center justify-center w-10 h-10 border rounded-md border-custom-800 bg-custom-800 group"><i data-lucide="check" class="w-5 h-5 hidden group-[.active]:inline-block text-white"></i></button>
                </div>
            </div>
            
        </div>
        <div class="flex items-center justify-between gap-3 p-4 border-t border-slate-200 dark:border-zink-500">
            <button type="button" id="reset-layout" class="w-full transition-all duration-200 ease-linear text-slate-500 btn bg-slate-200 border-slate-200 hover:text-slate-600 hover:bg-slate-300 hover:border-slate-300 focus:text-slate-600 focus:bg-slate-300 focus:border-slate-300 focus:ring focus:ring-slate-100">Reset</button>
            <a href="#!" class="w-full text-white transition-all duration-200 ease-linear bg-red-500 border-red-500 btn hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100">Buy Now</a>
        </div>
    </div>

    <script src="{{ URL::to('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/%40popperjs/core/umd/popper.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/tippy.js/tippy-bundle.umd.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::to('assets/libs/prismjs/prism.js') }}"></script>
    <script src="{{ URL::to('assets/libs/lucide/umd/lucide.js') }}"></script>
    <script src="{{ URL::to('assets/js/starcode.bundle.js') }}"></script>
    <script src="{{ URL::to('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <!--apexchart js-->
    <script src="{{ URL::to('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    
    <script src="{{ URL::to('assets/js/datatables/jquery-3.7.0.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/data-tables.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/data-tables.tailwindcss.min.js') }}"></script>
    <!--buttons dataTables-->
    <script src="{{ URL::to('assets/js/datatables/datatables.buttons.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/jszip.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ URL::to('assets/js/datatables/datatables.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::to('assets/js/app.js') }}"></script>
    @yield('script')

    <script>
    // javascript untuk fitur pencarian topbar
    (function() {
        var input   = document.getElementById('topbar-search');
        var results = document.getElementById('search-results');
        var timer   = null;

        if (!input || !results) return;

        // saat pengguna mengetik, tunggu 400ms sebelum mulai mencari
        input.addEventListener('keyup', function() {
            clearTimeout(timer);
            var kata = input.value.trim();

            if (kata.length < 2) {
                results.style.display = 'none';
                results.innerHTML = '';
                return;
            }

            timer = setTimeout(function() {
                // kirim permintaan ke controller pencarian
                fetch('{{ route("search") }}?q=' + encodeURIComponent(kata), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    // kalau tidak ada hasil
                    if (!data.length) {
                        results.innerHTML = '<div style="padding:12px 16px; color:#888; font-size:13px;">tidak ada hasil untuk "' + kata + '"</div>';
                        results.style.display = 'block';
                        return;
                    }

                    // tampilkan setiap hasil
                    var html = '';
                    data.forEach(function(item) {
                        var ikon = item.tipe === 'karyawan'
                            ? (item.avatar ? '<img src="' + item.avatar + '" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">' : '<div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:500;">' + item.label.charAt(0).toUpperCase() + '</div>')
                            : '<div style="width:32px;height:32px;border-radius:6px;background:#dbeafe;display:flex;align-items:center;justify-content:center;">🏢</div>';

                        html += '<a href="' + item.url + '" style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;color:inherit;border-bottom:1px solid #f1f5f9;" onmouseover="this.style.background=\'#f8fafc\'" onmouseout="this.style.background=\'transparent\'">'
                              + ikon
                              + '<div>'
                              + '<div style="font-size:13px;font-weight:500;">' + item.label + '</div>'
                              + '<div style="font-size:11px;color:#94a3b8;">' + item.sub + '</div>'
                              + '</div>'
                              + '</a>';
                    });

                    results.innerHTML = html;
                    results.style.display = 'block';
                })
                .catch(function() {
                    results.style.display = 'none';
                });
            }, 400);
        });

        // sembunyikan hasil saat klik di luar kotak pencarian
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
