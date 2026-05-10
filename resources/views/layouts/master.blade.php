<!DOCTYPE html>
<html lang="en" class="light scroll-smooth group" data-layout="vertical" data-sidebar="dark" data-sidebar-size="lg" data-mode="light" data-topbar="light" data-skin="default" data-navbar="sticky" data-content="fluid" dir="ltr">
<head>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <meta charset="utf-8">
    <title>HR | Sinergi Hotel & Vila - HR Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Minimal Admin & Dashboard Template" name="description">
    <meta content="Sinergi Hotel & Vila" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- app favicon -->
    <link rel="shortcut icon" href="{{ URL::to('assets/images/favicon.ico') }}">
    <!-- layout config js -->
    <script src="{{ URL::to('assets/js/layout.js') }}"></script>
    <!-- sinergi hotel & vila css -->
    <link rel="stylesheet" href="{{ URL::to('assets/css/starcode2.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          playfair: ['"Playfair Display"', 'serif'],
          poppins: ['Poppins', 'sans-serif'],
        }
      }
    }
  }
</script>
    
    <!-- hivi design system fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    
<!-- ============================================
     hivi design system styles
     ============================================ -->
<style>
  /* =============================================
     RESET & BASE
  ============================================= */
  * {
    box-sizing: border-box;
  }
  
  html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
  }

  /* =============================================
     FONT & BACKGROUND
  ============================================= */
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #F6F6F6 0%, #E3EFE8 40%, #80BB9B 100%);
    background-attachment: fixed;
    min-height: 100vh;
    color: #1A2B24;
    overflow-x: hidden;
  }

  h1, h2, h3, h4, h5, h6, .serif { 
    font-family: 'Playfair Display', serif; 
  }

  /* =============================================
     HIDE OLD TEMPLATE SIDEBAR
  ============================================= */
  .app-menu,
  .app-menu-overlay,
  #sidebar-overlay,
  footer {
    display: none !important;
  }

  /* =============================================
     TOPBAR
  ============================================= */
  #page-topbar {
    background-color: transparent !important;
    border-bottom: none !important;
    box-shadow: none !important;
    height: 56px !important;
    padding: 0 !important;
    left: 0 !important;
    right: 0 !important;
    z-index: 1001 !important;
    top: 0 !important;
    position: fixed !important;
    width: 100% !important;
  }
  
  #page-topbar .layout-width {
    width: 100% !important;
    padding-left: 20px !important;
  }

  #page-topbar .layout-width > div {
    background-color: transparent !important;
    border-bottom: none !important;
    box-shadow: none !important;
    height: 56px !important;
    padding: 0 !important;
  }

  /* Search Bar - Pill Shape */
  #topbar-search {
    background: rgba(255, 255, 255, 0.4) !important;
    backdrop-filter: blur(6px) !important;
    border: 1px solid rgba(232, 237, 237, 0.4) !important;
    border-radius: 999px !important;
    padding: 8px 16px 8px 36px !important;
    box-shadow: none !important;
    transition: all 0.2s ease;
    color: #1A2B24 !important;
    font-family: 'Poppins', sans-serif;
    font-size: 13px;
    width: 280px;
  }
  #topbar-search:focus {
    border-color: #80BB9B !important;
    outline: none !important;
    background: rgba(255, 255, 255, 0.6) !important;
  }
  #topbar-search::placeholder {
    color: #6B7280 !important;
    font-weight: 300;
  }

  /* =============================================
     PAGE CONTAINER (CLEAN LAYOUT)
  ============================================= */
  .page {
    margin-left: 110px;
    padding: 20px;
    padding-top: 64px;
    min-height: 100vh;
    display: block;
  }

  .page > * {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
  }

  /* Responsive: Tablet */
  @media (max-width: 1024px) {
    .page {
      margin-left: 100px;
      padding: 24px;
      padding-top: 80px;
    }
  }

  /* Responsive: Mobile */
  @media (max-width: 768px) {
    .page {
      margin-left: 0;
      padding: 20px;
      padding-top: 76px;
    }
  }

  /* =============================================
     HIVI DESIGN SYSTEM UTILITIES
  ============================================= */
  .hivi-card {
      background: rgba(246, 246, 246, 0.7);
      backdrop-filter: blur(6px);
      border-radius: 28px;
      padding: 28px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.04);
      border: none;
  }

  .hivi-btn-primary {
      background: #4F6560;
      color: white !important;
      border-radius: 9999px;
      padding: 10px 24px;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      border: none;
      cursor: pointer;
      transition: background 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
  }
  .hivi-btn-primary:hover { background: #3d504c; }
  
  .hivi-btn-secondary {
      background: transparent;
      color: #4F6560 !important;
      border-radius: 9999px;
      padding: 10px 24px;
      border: 1px solid #4F6560;
      font-family: 'Poppins', sans-serif;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
  }
  .hivi-btn-secondary:hover { background: #F6F6F6; }
  
  .hivi-btn-outline {
      background: transparent;
      color: #6B7280 !important;
      border-radius: 9999px;
      padding: 8px 16px;
      border: 1px solid #E5E7EB;
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
      font-size: 13px;
      cursor: pointer;
      transition: all 0.2s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
  }
  .hivi-btn-outline:hover { background: #F6F6F6; }

  .hivi-badge {
      border-radius: 9999px;
      padding: 4px 12px;
      font-size: 12px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
  }
  .hivi-badge-green  { background: #E8F5EE; color: #2E7D5E; }
  .hivi-badge-amber  { background: #fef3c7; color: #92400e; }
  .hivi-badge-red    { background: #fee2e2; color: #991b1b; }
  .hivi-badge-blue   { background: #dbeafe; color: #1e40af; }
  .hivi-badge-gray   { background: #f3f4f6; color: #374151; }
  
  .hivi-input {
      background: rgba(255, 255, 255, 0.6);
      backdrop-filter: blur(6px);
      border-radius: 9999px;
      padding: 10px 20px;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      font-weight: 300;
      width: 100%;
      outline: none;
      border: 1px solid rgba(229, 231, 235, 0.5);
      transition: border-color 0.2s;
      color: #1A2B24;
  }
  .hivi-input:focus { 
      border-color: #80BB9B; 
      outline: none;
      background: rgba(255, 255, 255, 0.7);
  }
  .hivi-input::placeholder { color: #6B7280; }
  
  .hivi-table { width: 100%; border-collapse: separate; border-spacing: 0; }
  .hivi-table thead th {
      font-family: 'Poppins', sans-serif;
      font-size: 13px;
      font-weight: 500;
      color: #6B7280;
      padding: 10px 16px;
      background: transparent;
      border-bottom: 1px solid #E8EDED;
      text-align: left;
  }
  .hivi-table tbody tr {
      transition: background 0.15s;
      height: 52px;
  }
  .hivi-table tbody tr:hover { 
      background: rgba(240, 247, 243, 0.6); 
  }
  .hivi-table tbody td {
      padding: 0 16px;
      font-size: 14px;
      color: #1A2B24;
      font-family: 'Poppins', sans-serif;
      font-weight: 400;
      border-bottom: 1px solid #F3F4F6;
  }
  .hivi-table tbody tr:last-child td {
      border-bottom: none;
  }

  .hivi-section-title {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 600;
      color: #1A2B24;
      margin-bottom: 16px;
  }

  /* =============================================
     STAT CARDS (FLEX LAYOUT)
  ============================================= */
  .hv-stat {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 200px;
  }

  .hv-stat-bottom {
      display: flex;
      align-items: flex-end;
      justify-content: flex-start;
      margin-top: auto;
      gap: 12px;
  }

  .hv-stat-icon {
      width: 32px;
      height: 32px;
      color: #9CA3AF;
      flex-shrink: 0;
  }

/* =============================================
     FLOATING PILL SIDEBAR
  ============================================= */
.hv-sidebar {
    position: fixed;
    top: 50%;
    left: 24px;
    transform: translateY(-50%);
    width: 72px;
    background: rgba(255, 255, 255, 0.70);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 999px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(255, 255, 255, 0.5) inset;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    z-index: 999999;
    overflow: visible;
}

/* Nav icon group (scrollable if many items) */
.hv-sidebar-nav {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 0 12px;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: calc(100vh - 200px);
    scrollbar-width: none;
    -ms-overflow-style: none;
}
.hv-sidebar-nav::-webkit-scrollbar { display: none; }

/* Bottom section (logout) */
.hv-sidebar-bottom {
    padding: 0 12px;
    margin-top: 12px;
    position: relative;
}
.hv-sidebar-bottom::before {
    content: '';
    display: block;
    width: 32px;
    height: 1px;
    background: rgba(0, 0, 0, 0.08);
    margin: 0 auto 12px;
}

/* ── Icon buttons ── */
.hv-sidebar a {
    position: relative;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4F6560;
    background: transparent;
    text-decoration: none;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
}
.hv-sidebar a i,
.hv-sidebar a svg {
    width: 20px;
    height: 20px;
    stroke-width: 1.6;
    transition: inherit;
}

/* Hover */
.hv-sidebar a:hover {
    background: rgba(79, 101, 96, 0.10);
    color: #3d504c;
    transform: scale(1.08);
}

/* Active */
.hv-sidebar a.active {
    background: #4F6560;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(79, 101, 96, 0.35);
}
.hv-sidebar a.active:hover {
    background: #3d504c;
    transform: scale(1.05);
}

/* Logout icon */
.hv-sidebar-logout {
    color: #9CA3AF !important;
}
.hv-sidebar-logout:hover {
    background: rgba(229, 115, 115, 0.12) !important;
    color: #E57373 !important;
}

/* Tooltip on hover */
.hv-sidebar a[title]:hover::after {
    content: attr(title);
    position: absolute;
    left: calc(100% + 14px);
    top: 50%;
    transform: translateY(-50%);
    background: #1A2B24;
    color: #fff;
    padding: 6px 14px;
    border-radius: 8px;
    font-family: 'Poppins', sans-serif;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
    pointer-events: none;
    z-index: 1000000;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    animation: hv-tooltip-in 0.2s ease;
}
.hv-sidebar a[title]:hover::before {
    content: '';
    position: absolute;
    left: calc(100% + 8px);
    top: 50%;
    transform: translateY(-50%);
    border: 5px solid transparent;
    border-right-color: #1A2B24;
    pointer-events: none;
    z-index: 1000000;
    animation: hv-tooltip-in 0.2s ease;
}

@keyframes hv-tooltip-in {
    from { opacity: 0; transform: translateY(-50%) translateX(-4px); }
    to   { opacity: 1; transform: translateY(-50%) translateX(0); }
}

/* ── Content offset ── */
.hv-main {
    margin-left: 120px;
    padding: 20px;
}

/* ── Responsive: Mobile & Tablet (Offcanvas) ── */
@media (max-width: 1024px) {
    .hv-sidebar {
        top: 0;
        bottom: 0;
        left: 0;
        transform: translateY(0) translateX(-100%);
        height: 100vh;
        width: 80px;
        border-radius: 0;
        padding: 80px 0 20px 0;
        background: #ffffff;
        box-shadow: 4px 0 24px rgba(0,0,0,0.1);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hv-sidebar.open {
        transform: translateY(0) translateX(0);
    }
    .hv-sidebar-nav {
        max-height: calc(100vh - 180px);
    }
    .hv-sidebar-bottom {
        margin-top: auto;
    }
    .hv-main, .page {
        margin-left: 0;
        padding-bottom: 32px;
    }
    .page {
      padding-top: 76px;
      padding-left: 16px;
      padding-right: 16px;
    }
}

/* =============================================
     FORCE OVERRIDE LEGACY LAYOUT WRAPPERS
  ============================================= */
#layout-wrapper,
.layout-wrapper,
.main-content,
.page-content,
.vertical-menu,
[data-simplebar] {
    margin-left: 0 !important;
    padding-left: 0 !important;
    width: 100% !important;
    max-width: 100% !important;
}
.vertical-menu {
    display: none !important;
}
.hv-sidebar a {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    cursor: pointer;
    z-index: 10;
    position: relative;
}

.hv-sidebar i {
    pointer-events: none; 
}
</style>

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
  
  /* ── Skeleton Loading ── */
  .skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 37%, #f0f0f0 63%);
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
    border-radius: 8px;
  }
  @keyframes shimmer {
    0% { background-position: 100% 0; }
    100% { background-position: -100% 0; }
  }
  .real-content {
    opacity: 0;
    transition: opacity 200ms ease;
  }
  .real-content.loaded {
    opacity: 1;
  }
  .skeleton-wrapper {
    transition: opacity 200ms ease;
  }
</style>

</head>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@stack('scripts')
<body class="text-base bg-body-bg text-body font-poppins dark:text-zink-100 dark:bg-zink-800 group-data-[skin=bordered]:bg-body-bordered group-data-[skin=bordered]:dark:bg-zink-700">

  <!-- floating sidebar (outside all containers) -->
  @include('sidebar.sidebar')

  <!-- page wrapper -->
  <div class="page">
    
    <!-- topbar header -->
    <header id="page-topbar">
      <div class="layout-width">
        <div class="flex items-center px-4 mx-auto bg-topbar border-b-2 border-topbar group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-brand shadow-md h-header shadow-slate-200/50 group-data-[navbar=bordered]:rounded-md group-data-[navbar=bordered]:group-[.is-sticky]/topbar:rounded-t-none group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:border-zink-700 dark:shadow-none group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-zink-500 group-data-[topbar=dark]:group-[.is-sticky]/topbar:dark:shadow-md group-data-[navbar=bordered]:shadow-none group-data-[layout=horizontal]:group-data-[navbar=bordered]:rounded-b-none group-data-[layout=horizontal]:shadow-none group-data-[layout=horizontal]:dark:group-[.is-sticky]/topbar:shadow-none">
          
          <div class="flex items-center w-full group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl navbar-header group-data-[layout=horizontal]:ltr:xl:pr-3 group-data-[layout=horizontal]:rtl:xl:pl-3">
            
            <!-- hamburger (mobile only) -->
            <button type="button" id="mobile-menu-btn" class="flex items-center justify-center p-2 mr-3 text-slate-500 rounded-lg lg:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-200">
              <i data-lucide="menu" class="w-6 h-6"></i>
            </button>

            <!-- logo (horizontal only) -->
            <div class="items-center justify-center hidden px-5 text-center h-header group-data-[layout=horizontal]:md:flex group-data-[layout=horizontal]:ltr::pl-0 group-data-[layout=horizontal]:rtl:pr-0">
              <a href="{{ route('home') }}">
                <img src="{{ URL::to('assets/images/logo-sinergi.png') }}" alt="" class="h-10 mx-auto">
              </a>
            </div>

            <!-- search bar -->
            <div class="relative hidden ltr:ml-3 rtl:mr-3 lg:block group-data-[layout=horizontal]:hidden group-data-[layout=horizontal]:lg:block">
              <input type="text" id="topbar-search" class="py-2 pr-4 text-sm text-topbar-item bg-topbar border border-topbar-border rounded pl-8 placeholder:text-slate-400 form-control focus-visible:outline-0 min-w-[300px] focus:border-blue-400 group-data-[topbar=dark]:bg-topbar-dark group-data-[topbar=dark]:border-topbar-border-dark group-data-[topbar=dark]:placeholder:text-slate-500 group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=brand]:bg-topbar-brand group-data-[topbar=brand]:border-topbar-border-brand group-data-[topbar=brand]:placeholder:text-blue-300 group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:bg-zink-700 group-data-[topbar=dark]:dark:border-zink-500 group-data-[topbar=dark]:dark:text-zink-100" placeholder="Search employees, menus, or files" autocomplete="off">
              <i data-lucide="search" class="inline-block size-4 absolute left-2.5 top-2.5 text-topbar-item fill-slate-100 group-data-[topbar=dark]:fill-topbar-item-bg-hover-dark group-data-[topbar=dark]:text-topbar-item-dark group-data-[topbar=brand]:fill-topbar-item-bg-hover-brand group-data-[topbar=brand]:text-topbar-item-brand group-data-[topbar=dark]:dark:text-zink-200 group-data-[topbar=dark]:dark:fill-zink-600"></i>
              <div id="search-results" style="display:none; position:absolute; top:42px; left:0; width:340px; background:#fff; border-radius:6px; box-shadow:0 4px 16px rgba(0,0,0,0.12); z-index:9999; max-height:320px; overflow-y:auto;" class="dark:bg-zink-700"></div>
            </div>

            <!-- right side: icons + notifications + profile -->
            <div class="flex gap-3 ms-auto">
            </div>

            </div>
          </div>
        </div>
    </header>

    <!-- page content -->
    <div class="max-w-7xl mx-auto px-6">
        @yield('content')
      </div>
    </div>

  </div>
  <!-- end page wrapper -->

  <!-- scripts -->
  <script src="{{ URL::to('assets/libs/lucide/umd/lucide.js') }}"></script>
  <script src="{{ URL::to('assets/js/layout.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <!-- mobile sidebar overlay -->
  <div id="sidebar-overlay" class="fixed inset-0 z-[999998] hidden bg-slate-900/50 backdrop-blur-sm transition-opacity opacity-0"></div>

  <!-- initialize icons & scripts -->
  <script>
    lucide.createIcons();
    document.addEventListener('DOMContentLoaded', function() {
      // Mobile Menu
      const btn = document.getElementById('mobile-menu-btn');
      const sidebar = document.getElementById('hv-sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      
      if(btn && sidebar && overlay) {
        function toggleMenu() {
          sidebar.classList.toggle('open');
          if (sidebar.classList.contains('open')) {
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
          } else {
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
          }
        }
        btn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);
      }

      // Skeleton Loading Toggle
      setTimeout(() => {
        document.querySelectorAll('.skeleton-wrapper').forEach(el => {
          el.style.opacity = '0';
          setTimeout(() => el.style.display = 'none', 200);
        });
        document.querySelectorAll('.real-content').forEach(el => {
          el.classList.remove('hidden');
          // trigger reflow
          void el.offsetWidth;
          el.classList.add('loaded');
        });
      }, 600); // 600ms artificial delay for perceived performance
    });
  </script>

</body>
</html>
