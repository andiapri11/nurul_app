<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $appSettings['app_name'] ?? 'Nurul Ilmi Management' }} | @yield('title', 'Dashboard')</title>
    @if(isset($appSettings['app_favicon']))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $appSettings['app_favicon']) }}">
    @endif
    <!--begin::Performance Hint-->
    <link rel="preconnect" href="https://cdn.jsdelivr.net" />
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net" />
    <link rel="preconnect" href="https://code.jquery.com" />
    <!--end::Performance Hint-->

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!-- PWA Setup -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $appSettings['app_short_name'] ?? 'Nurul Ilmi' }}">
    <link rel="apple-touch-icon" href="{{ isset($appSettings['app_logo']) ? asset('storage/' . $appSettings['app_logo']) : asset('template/dist/assets/img/AdminLTELogo.png') }}">
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="{{ $appSettings['app_name'] ?? 'Nurul Ilmi Management' }} | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="Sistem Informasi Manajemen Sekolah Terpadu - {{ $appSettings['app_name'] ?? 'Nurul Ilmi Management' }}"
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('template/dist/css/adminlte.css') }}" as="style" />
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" as="style" />
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Select2)-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!--end::Third Party Plugin(Select2)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('template/dist/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />
    <style>
      /* Modern Premium Sidebar */
      :root {
        --sidebar-bg: #111c43; /* Dark Navy Blue Premium */
        --sidebar-hover: rgba(255, 255, 255, 0.1);
        --sidebar-active-bg: linear-gradient(90deg, #4361ee, #3a0ca3);
        --sidebar-text: #cedef7; /* Brighter for better readability */
        --sidebar-text-active: #ffffff;
        --sidebar-header: #9bb2e5; /* More visible header */
      }

      .app-sidebar {
        background-color: var(--sidebar-bg) !important;
        box-shadow: 5px 0 25px rgba(0,0,0,0.15) !important;
        border-right: none !important;
      }

      .sidebar-brand {
        background-color: rgba(0,0,0,0.2) !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
      }
      
      .brand-text {
        color: #fff;
        font-weight: 700 !important;
        letter-spacing: 1px;
        text-transform: uppercase;
        font-size: 1.1rem;
      }

      /* Scrollbar */
      .sidebar-wrapper::-webkit-scrollbar {
        width: 6px;
      }
      .sidebar-wrapper::-webkit-scrollbar-thumb {
        background-color: rgba(255,255,255,0.1);
        border-radius: 10px;
      }

      /* Nav Items */
      .sidebar-menu {
        padding-top: 15px;
      }
      
      .sidebar-menu .nav-item {
        margin: 2px 8px; /* Mengurangi margin samping agar menu lebih lebar */
      }

      .sidebar-menu .nav-link {
        border-radius: 8px !important;
        color: var(--sidebar-text) !important;
        padding: 9px 8px 9px 10px; /* Sedikit lebih rapat */
        transition: all 0.2s ease-in-out;
        font-weight: 600;
        display: flex;
        font-size: 0.86rem; /* Ukuran font dioptimalkan agar muat satu baris */
      }

      .sidebar-menu .nav-link:hover {
        background-color: var(--sidebar-hover) !important;
        color: #fff !important;
        padding-left: 12px;
      }

      .sidebar-menu .nav-link.active {
        background: var(--sidebar-active-bg) !important;
        color: var(--sidebar-text-active) !important;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
        font-weight: 600;
      }

      /* Icon Styling */
      .sidebar-menu .nav-icon {
        color: inherit !important;
        margin-right: 8px; /* Lebih rapat sedikit */
        font-size: 1.15rem;
        filter: drop-shadow(0 2px 3px rgba(0,0,0,0.2));
        width: 20px; /* Lebar box ikon dioptimalkan */
        text-align: center;
        flex-shrink: 0;
      }
      
      /* Section Headers */
      .sidebar-menu .nav-header {
        color: #8fa6d1 !important; /* Lighter/Brighter header */
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 800;
        padding: 1.8rem 1.2rem 0.6rem;
        margin-top: 10px;
        border-top: 1px solid rgba(255,255,255,0.05); /* Separator line */
      }

      /* Treeview (Submenu Container) */
      .nav-treeview {
        background-color: rgba(0,0,0,0.15);
        border-radius: 8px;
        margin-top: 2px;
        margin-bottom: 5px;
      }

      /* Submenu (Tingkat 2) */
      .nav-treeview .nav-link {
        padding-left: 32px !important; 
        font-size: 0.88rem; /* Larger submenu font */
        font-weight: 400; /* Regular weight for submenus */
        opacity: 0.95;
      }
      
      .nav-treeview .nav-link:hover {
        padding-left: 30px !important;
      }

      .nav-treeview .nav-link.active {
        background: rgba(255,255,255,0.1) !important;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.1) !important;
        color: #fff !important;
        border-left: 4px solid #4cc9f0;
        border-radius: 0 8px 8px 0 !important;
      }

      /* Subtle highlight for open menus */
      .menu-open > .nav-link:not(.active) {
        background-color: rgba(255,255,255,0.03);
        color: #fff !important;
      }

      /* Mengizinkan teks menu turun ke bawah (wrap) jika panjang */
      .sidebar-menu .nav-link p {
        white-space: normal !important;
        margin-bottom: 0;
        line-height: 1.3;
        overflow: visible !important;
        text-overflow: unset !important;
        display: block;
        width: 100%;
        padding-right: 20px; /* Memberi ruang aman untuk panah agar tidak tertabrak teks */
        padding-top: 2px;
      }

      /* Pastikan ikon tetap sejajar di atas jika teks panjang */
      .sidebar-menu .nav-link {
        align-items: flex-start !important;
      }
      
      .sidebar-menu .nav-icon {
        margin-top: 2px;
      }

      /* Arrow Icon */
      .nav-arrow {
          margin-left: auto !important;
          margin-right: -5px; /* Geser mepet ke tepi kanan */
          font-size: 0.75rem;
          transition: transform 0.2s;
          flex-shrink: 0;
          position: relative;
          right: -2px;
      }
      .menu-open > .nav-link > .nav-arrow {
          transform: rotate(90deg);
      }
      
      /* Badge */
      .sidebar-badge {
          margin-left: auto;
          font-size: 0.75rem;
          padding: 3px 8px;
          border-radius: 6px;
      }
        
      /* User Panel Shadow */
      .user-panel {
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
      }
      
      /* Force Date Picker Icon Black */
      /* We enable light color-scheme to force the browser to render the default dark/black icon */
      input[type="date"] {
          color-scheme: light !important; 
          color: black !important;
      }
      input[type="date"]::-webkit-calendar-picker-indicator {
        filter: none !important;
        cursor: pointer;
        opacity: 1;
        background-color: transparent; /* Ensure no white box if not desired, though color-scheme light usually implies white bg for control */
      }

      /* Responsive Footer Text */
      @media (max-width: 576px) {
          .app-footer {
              font-size: 0.65rem !important;
              padding: 0.5rem 1rem !important;
          }
      }
    </style>
    @stack('styles')
  </head>
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->
          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img
                  src="{{ Auth::user()->photo ? asset('photos/' . Auth::user()->photo) : asset('template/dist/assets/img/user2-160x160.jpg') }}"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  <img
                    src="{{ Auth::user()->photo ? asset('photos/' . Auth::user()->photo) : asset('template/dist/assets/img/user2-160x160.jpg') }}"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    {{ Auth::user()->name }} - {{ ucfirst(Auth::user()->role) }}
                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="{{ Auth::user()->role === 'siswa' ? route('siswa.profil') : route('profile.index') }}" class="btn btn-default btn-flat">Profile</a>
                  
                  <a href="#" class="btn btn-default btn-flat float-end"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Sign out
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
                </li>
                <!--end::Menu Footer-->
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        @if(Auth::user()->role !== 'siswa')
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ route('dashboard') }}" class="brand-link">
            <!--begin::Brand Image-->
            @if(isset($appSettings['app_logo']))
              <img
                src="{{ asset('storage/' . $appSettings['app_logo']) }}"
                alt="Logo"
                class="brand-image opacity-75 shadow"
                width="33"
                height="33"
              />
            @else
              <img
                src="{{ asset('template/dist/assets/img/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
              />
            @endif
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">{{ $appSettings['app_name'] ?? 'AdminLTE 4' }}</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        @endif
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            role="menu"
            data-accordion="true"
          >
            
            @if(Auth::user()->role !== 'siswa')
            <!-- Dashboard -->
                <li class="nav-item">
                  <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-speedometer"></i>
                    <p>Dashboard</p>
                  </a>
                </li>
                



                <!-- Data Umum -->
                <!-- Data Umum -->
                @if(Auth::user()->role === 'administrator' || Auth::user()->isManajemenSekolah() || Auth::user()->role === 'guru' || Auth::user()->isSarpras())
                <li class="nav-item {{ request()->routeIs('units.*') || request()->routeIs('classes.*') || request()->routeIs('academic-years.*') || request()->routeIs('academic-calendars.*') || request()->routeIs('students.*') || request()->routeIs('jabatans.*') || (request()->routeIs('gurukaryawans.*') && !request()->routeIs('gurukaryawans.user-index')) ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->routeIs('units.*') || request()->routeIs('classes.*') || request()->routeIs('academic-years.*') || request()->routeIs('academic-calendars.*') || request()->routeIs('students.*') || request()->routeIs('jabatans.*') || (request()->routeIs('gurukaryawans.*') && !request()->routeIs('gurukaryawans.user-index')) ? 'active' : '' }}">
                    <i class="nav-icon bi bi-box-seam-fill"></i>
                    <p>
                        Data Umum
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @if(Auth::user()->isDirektur())
                    <li class="nav-item">
                      <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-building"></i>
                        <p>Unit Sekolah</p>
                      </a>
                    </li>

                    <li class="nav-item">
                      <a href="{{ route('academic-years.index') }}" class="nav-link {{ request()->routeIs('academic-years.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar3"></i>
                        <p>Tahun Pelajaran</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('jabatans.index') }}" class="nav-link {{ request()->routeIs('jabatans.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-briefcase"></i>
                        <p>Data Jabatan</p>
                      </a>
                    </li>
                    @endif
                    <li class="nav-item">
                      <a href="{{ route('academic-calendars.index') }}" class="nav-link {{ request()->routeIs('academic-calendars.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-event"></i>
                        <p>Kalender Akademik</p>
                      </a>
                    </li>
                    
                    {{-- Classes: Admin, Management, Teaching Guru, and Sarpras --}}
                    @if(Auth::user()->role === 'administrator' || Auth::user()->isManajemenSekolah() || Auth::user()->role === 'guru' || Auth::user()->isSarpras())
                    <li class="nav-item">
                      <a href="{{ route('classes.index') }}" class="nav-link {{ request()->routeIs('classes.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-door-open"></i>
                        <p>Kelas</p>
                      </a>
                    </li>
                    @endif

                    {{-- Global Student Data: Admin & Management (Excluding Sarpras) --}}
                    @if(Auth::user()->role === 'administrator' || (Auth::user()->isManajemenSekolah() && !Auth::user()->isSarpras()))
                    <li class="nav-item">
                      <a href="{{ route('students.index') }}" class="nav-link {{ request()->routeIs('students.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-video3"></i>
                        <p>Data Siswa Aktif</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('students.alumni') }}" class="nav-link {{ request()->routeIs('students.alumni') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-mortarboard-fill"></i>
                        <p>Data Siswa Alumni</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('students.withdrawn') }}" class="nav-link {{ request()->routeIs('students.withdrawn') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-x-fill"></i>
                        <p>Data Siswa Keluar</p>
                      </a>
                    </li>
                    @endif
                    @if(Auth::user()->isDirektur())
                    <li class="nav-item">
                      <a href="{{ route('gurukaryawans.index') }}" class="nav-link {{ request()->routeIs('gurukaryawans.*') && !request()->routeIs('gurukaryawans.user-index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>Data Guru & Staff</p>
                      </a>
                    </li>
                    @endif
                  </ul>
                </li>
                @endif


                <!-- Data Pembelajaran -->
                @if(!in_array(Auth::user()->role, ['admin_keuangan', 'kepala_keuangan']))
                <li class="nav-item {{ request()->routeIs('subjects.*') || request()->routeIs('schedules.*') || request()->routeIs('class-checkins.*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->routeIs('subjects.*') || request()->routeIs('schedules.*') || request()->routeIs('class-checkins.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-book-half"></i>
                    <p>
                      Data Pembelajaran
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    {{-- Mata Pelajaran: Admin & Management (Excluding Sarpras) --}}
                    @if(Auth::user()->role === 'administrator' || (Auth::user()->isManajemenSekolah() && !Auth::user()->isSarpras()))
                    <li class="nav-item">
                      <a href="{{ route('subjects.index') }}" class="nav-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-text"></i>
                        <p>Mata Pelajaran</p>
                      </a>
                    </li>
                    @endif

                    {{-- Jadwal Pelajaran: Visible to All --}}
                    <li class="nav-item">
                      <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-calendar-date"></i>
                        <p>Jadwal Pelajaran</p>
                      </a>
                    </li>
                    
                    {{-- Setting Jadwal: Admin & Management (Excluding Sarpras) --}}
                    @if(Auth::user()->role === 'administrator' || (Auth::user()->isManajemenSekolah() && !Auth::user()->isSarpras()))
                    <li class="nav-item">
                      <a href="{{ route('schedules.settings') }}" class="nav-link {{ request()->routeIs('schedules.settings') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear-fill"></i>
                        <p>Setting Jadwal</p>
                      </a>
                    </li>
                    @endif
                    <li class="nav-item">
                      <a href="{{ route('class-checkins.index') }}" class="nav-link {{ request()->routeIs('class-checkins.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-qr-code-scan"></i>
                        <p>Check-in Kelas</p>
                      </a>
                    </li>
                  </ul>
                </li>
                @endif

                <!-- Administrasi Guru (For Teachers & Sarpras) -->
                @if((Auth::user()->role === 'guru' || Auth::user()->hasJabatan('Guru') || Auth::user()->isSarpras()) && !Auth::user()->isDirektur())
                <li class="nav-item">
                    <a href="{{ route('teacher-docs.index') }}" class="nav-link {{ request()->routeIs('teacher-docs.*') && !request()->routeIs('teacher-docs.supervisions.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-text"></i>
                        <p>Administrasi Guru</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('teacher-docs.supervisions.index') }}" class="nav-link {{ request()->routeIs('teacher-docs.supervisions.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clipboard-check"></i>
                        <p>Jadwal Supervisi Saya</p>
                    </a>
                </li>
                @endif

                <!-- Manajemen Sekolah (Wakasek/Direktur/Wali Kelas) -->
                @if(Auth::user()->isManajemenSekolah() || Auth::user()->isWaliKelas())
                <li class="nav-item {{ request()->routeIs('principal.*') || request()->is('school-management*') || request()->routeIs('director.*') || request()->routeIs('wali-kelas.*') || request()->routeIs('curriculum.*') || request()->is('graduation*') || request()->routeIs('graduation.*') || request()->routeIs('student-affairs.*') || request()->routeIs('sarpras.*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->routeIs('principal.*') || request()->is('school-management*') || request()->routeIs('director.*') || request()->routeIs('wali-kelas.*') || request()->routeIs('curriculum.*') || request()->is('graduation*') || request()->routeIs('graduation.*') || request()->routeIs('student-affairs.*') || request()->routeIs('sarpras.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-mortarboard"></i>
                    <p>
                      Manajemen Sekolah
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <!-- Direktur Sub-Menu -->
                    @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur')
                    <li class="nav-item {{ request()->routeIs('director.*') || request()->routeIs('sarpras.director.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('director.*') || request()->routeIs('sarpras.director.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-briefcase-fill"></i>
                            <p>
                                Pimpinan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('director.index') }}" class="nav-link {{ request()->routeIs('director.index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-speedometer2"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('director.employees') }}" class="nav-link {{ request()->routeIs('director.employees') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people-fill"></i>
                                    <p>Direktori Pegawai</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sarpras.director.approvals') }}" class="nav-link {{ request()->routeIs('sarpras.director.approvals') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-shield-fill-check text-success"></i>
                                    <p>Approval Pengajuan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('sarpras.reports.director-index') }}" class="nav-link {{ request()->routeIs('sarpras.reports.director-index') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-trash3-fill text-danger"></i>
                                    <p>Approval Penghapusan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Kepala Sekolah Sub-Menu -->
                    @if(Auth::user()->isKepalaSekolah())
                    <li class="nav-item {{ request()->routeIs('principal.*') || request()->routeIs('sarpras.principal.*') || request()->is('graduation*') || request()->routeIs('graduation.*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ request()->routeIs('principal.*') || request()->routeIs('sarpras.principal.*') || request()->is('graduation*') || request()->routeIs('graduation.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-workspace"></i>
                        <p>
                          Kepala Sekolah
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('principal.index') }}" class="nav-link {{ request()->routeIs('principal.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('principal.documents') }}" class="nav-link {{ request()->routeIs('principal.documents*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-check-fill"></i>
                            <p>Approval Dokumen</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('principal.teacher-attendance') }}" class="nav-link {{ request()->routeIs('principal.teacher-attendance') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-check-fill"></i>
                            <p>Monitoring Guru</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('principal.class-stats') }}" class="nav-link {{ request()->routeIs('principal.class-stats') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-grid-3x3-gap-fill"></i>
                            <p>Monitoring Kelas</p>
                          </a>
                        </li>
                        @if(Auth::user()->role !== 'direktur')
                        <li class="nav-item">
                          <a href="{{ route('principal.supervisions.index') }}" class="nav-link {{ request()->routeIs('principal.supervisions.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clipboard-check-fill"></i>
                            <p>Supervisi Akademik</p>
                          </a>
                        </li>
                        @endif
                        <li class="nav-item">
                          <a href="{{ route('sarpras.principal.approvals') }}" class="nav-link {{ request()->routeIs('sarpras.principal.approvals') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-check-fill text-info"></i>
                            <p>Approval Sarpras</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    @endif
                    @if(Auth::user()->role === 'administrator' || Auth::user()->isKurikulum() || Auth::user()->isKepalaSekolah())
                    <li class="nav-item {{ request()->routeIs('curriculum.*') || request()->is('graduation*') || request()->routeIs('graduation.*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ request()->routeIs('curriculum.*') || request()->is('graduation*') || request()->routeIs('graduation.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-journal-bookmark-fill text-primary"></i>
                        <p>
                          Wakil Kurikulum
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('curriculum.index') }}" class="nav-link {{ request()->routeIs('curriculum.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-text"></i>
                            <p>Dokumen</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('curriculum.teaching-assignments.index') }}" class="nav-link {{ request()->routeIs('curriculum.teaching-assignments.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-person-video3"></i>
                            <p>Tugas Mengajar</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('curriculum.jurnal-kelas') }}" class="nav-link {{ request()->routeIs('curriculum.jurnal-kelas') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-check"></i>
                            <p>Jurnal Kelas</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('graduation.index') }}" class="nav-link {{ request()->is('graduation*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-mortarboard-fill text-success"></i>
                            <p>Kelulusan</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    @endif


                    @if(Auth::user()->isKesiswaan() || Auth::user()->isKepalaSekolah())
                    <li class="nav-item {{ request()->routeIs('student-affairs.*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ request()->routeIs('student-affairs.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-lines-fill"></i>
                        <p>
                          Wakil Kesiswaan
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.violations.index') }}" class="nav-link {{ request()->routeIs('student-affairs.violations.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-exclamation-triangle"></i>
                            <p>Catatan Pelanggaran</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.achievements.index') }}" class="nav-link {{ request()->routeIs('student-affairs.achievements.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-trophy"></i>
                            <p>Prestasi Siswa</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.extracurriculars.index') }}" class="nav-link {{ request()->routeIs('student-affairs.extracurriculars.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-activity"></i>
                            <p>Ekstrakurikuler</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.black-book') }}" class="nav-link {{ request()->routeIs('student-affairs.black-book') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-x text-danger"></i>
                            <p>Buku Hitam</p>
                          </a>
                        </li>
                        <li class="nav-header text-uppercase small opacity-50">Monitoring & Absensi</li>
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.attendance-data') }}" class="nav-link {{ request()->routeIs('student-affairs.attendance-data') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar-check text-success"></i>
                            <p>Laporan Absensi Unit</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('student-affairs.attendance-settings') }}" class="nav-link {{ request()->routeIs('student-affairs.attendance-settings') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-clock-history text-info"></i>
                            <p>Batas Waktu Absen</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    @endif
                    @if(Auth::user()->isSarpras() || Auth::user()->isKepalaSekolah())
                    <li class="nav-item {{ request()->is('sarpras*') && !request()->is('sarpras/director*') && !request()->is('sarpras/principal*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ request()->is('sarpras*') && !request()->is('sarpras/director*') && !request()->is('sarpras/principal*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-tools text-warning"></i>
                        <p>
                          Wakil Sapras
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="{{ route('sarpras.index') }}" class="nav-link {{ request()->routeIs('sarpras.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-speedometer2"></i>
                            <p>Dashboard</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.categories.index') }}" class="nav-link {{ request()->routeIs('sarpras.categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-tags-fill"></i>
                            <p>Kategori Barang</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.rooms.index') }}" class="nav-link {{ request()->routeIs('sarpras.rooms.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-door-open-fill"></i>
                            <p>Manajemen Ruangan</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.inventory.index') }}" class="nav-link {{ request()->routeIs('sarpras.inventory.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-box-fill"></i>
                            <p>Data Inventaris</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.inventory.disposed') }}" class="nav-link {{ request()->routeIs('sarpras.inventory.disposed') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-trash-fill text-danger"></i>
                            <p>Arsip Penghapusan</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.consumables.index') }}" class="nav-link {{ request()->routeIs('sarpras.consumables.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-card-checklist"></i>
                            <p>Barang Habis Pakai</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.reports.index') }}" class="nav-link {{ request()->routeIs('sarpras.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-exclamation-octagon-fill"></i>
                            <p>Laporan Kerusakan</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.procurements.index') }}" class="nav-link {{ request()->routeIs('sarpras.procurements.index') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-cart-plus-fill text-warning"></i>
                            <p>Pengajuan Barang</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('sarpras.scan') }}" class="nav-link {{ request()->routeIs('sarpras.scan') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-qr-code-scan text-info"></i>
                            <p>Scan Barcode</p>
                          </a>
                        </li>
                        
                        @if(Auth::user()->isKepalaSekolah())

                        @endif


                      </ul>
                    </li>
                    @endif

                    @if(Auth::user()->isWaliKelas())
                    <!-- Debug: Wali Kelas Menu Rendered -->
                    <li class="nav-item {{ request()->routeIs('wali-kelas.*') ? 'menu-open' : '' }}">
                      <a href="#" class="nav-link {{ request()->routeIs('wali-kelas.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-workspace"></i>
                        <p>
                          Wali Kelas
                          <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">

                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.students.index') }}" class="nav-link {{ request()->routeIs('wali-kelas.students.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Data Siswa</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.announcements.index') }}" class="nav-link {{ request()->routeIs('wali-kelas.announcements.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-megaphone"></i>
                            <p>Pengumuman Walas</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.attendance') }}" class="nav-link {{ request()->routeIs('wali-kelas.attendance') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-calendar-check"></i>
                            <p>Absensi Siswa</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.report') }}" class="nav-link {{ request()->routeIs('wali-kelas.report') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-text"></i>
                            <p>Laporan Absensi</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.violations') }}" class="nav-link {{ request()->routeIs('wali-kelas.violations') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-exclamation-triangle"></i>
                            <p>Catatan Pelanggaran</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="{{ route('wali-kelas.extracurriculars') }}" class="nav-link {{ request()->routeIs('wali-kelas.extracurriculars') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-trophy"></i>
                            <p>Capaian Ekskul</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    @endif
                    @if(Auth::user()->role === 'administrator' || Auth::user()->hasJabatan('Humas'))
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="nav-icon bi bi-megaphone-fill"></i>
                        <p>Humas</p>
                      </a>
                    </li>
                    @endif
                  </ul>
                </li>
                @endif

                <!-- Keuangan -->
                <!-- Keuangan -->
                @if(Auth::user()->role === 'administrator' || Auth::user()->role === 'direktur' || Auth::user()->role === 'admin_keuangan' || Auth::user()->role === 'kepala_keuangan' || Auth::user()->role === 'staff' || Auth::user()->hasJabatan('Keuangan'))
                <li class="nav-item {{ request()->is('finance*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->is('finance*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-cash-stack text-success"></i>
                    <p>
                        Manajemen Keuangan
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <!-- Dashboard -->
                    <li class="nav-item">
                      <a href="{{ route('finance.dashboard') }}" class="nav-link {{ request()->routeIs('finance.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                      </a>
                    </li>

                    <li class="nav-header" style="font-size: 0.65rem; padding-top: 10px;">OPERASIONAL</li>
                    
                    <li class="nav-item">
                      <a href="{{ route('finance.payments.index') }}" class="nav-link {{ request()->routeIs('finance.payments.index') || request()->routeIs('finance.payments.show') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-wallet2 text-primary"></i>
                        <p>Pembayaran Siswa</p>
                      </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.transactions.index') }}" class="nav-link {{ request()->routeIs('finance.transactions.index') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-clock-history text-info"></i>
                          <p>Riwayat Transaksi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.bills.index') }}" class="nav-link {{ request()->routeIs('finance.bills.index') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-card-checklist text-warning"></i>
                          <p>Rekap Tagihan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.reports.arrears') }}" class="nav-link {{ request()->routeIs('finance.reports.arrears') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-exclamation-triangle-fill text-danger"></i>
                          <p>Rekap Tunggakan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.verifications.index') }}" class="nav-link {{ request()->routeIs('finance.verifications.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-patch-check-fill text-success"></i>
                          <p>Verifikasi Pembayaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.income.index') }}" class="nav-link {{ request()->routeIs('finance.income.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-graph-up-arrow text-success"></i>
                          <p>Pemasukan Umum</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.expense.index') }}" class="nav-link {{ request()->routeIs('finance.expense.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-graph-down-arrow text-danger"></i>
                          <p>Pengeluaran Kas</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('finance.realization.index') }}" class="nav-link {{ request()->routeIs('finance.realization.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-file-earmark-medical-fill text-info"></i>
                          <p>Laporan Realisasi</p>
                        </a>
                    </li>

                    <li class="nav-header" style="font-size: 0.65rem; padding-top: 10px;">AKUNTANSI</li>
                    
                    @if(in_array(Auth::user()->role, ['administrator', 'kepala_keuangan', 'direktur']))
                    <li class="nav-item">
                        <a href="{{ route('finance.bank-accounts.index') }}" class="nav-link {{ request()->routeIs('finance.bank-accounts.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-bank2 text-primary"></i>
                          <p>Data Akun Bank</p>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('finance.reports.student-payments') }}" class="nav-link {{ request()->routeIs('finance.reports.student-payments') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-file-earmark-person-fill text-info"></i>
                          <p>Laporan Pembayaran Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.reports.general-ledger') }}" class="nav-link {{ request()->routeIs('finance.reports.general-ledger') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-journal-text text-primary"></i>
                          <p>Laporan Kas Umum</p>
                        </a>
                    </li>

                    @if(in_array(Auth::user()->role, ['administrator', 'kepala_keuangan', 'direktur']))
                    <li class="nav-header" style="font-size: 0.65rem; padding-top: 10px;">PENGATURAN</li>
                    
                    <li class="nav-item">
                        <a href="{{ route('finance.student-fees.index') }}" class="nav-link {{ request()->routeIs('finance.student-fees.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-person-lines-fill"></i>
                          <p>Atur Tagihan Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('finance.payment-types.index') }}" class="nav-link {{ request()->routeIs('finance.payment-types.*') ? 'active' : '' }}">
                          <i class="nav-icon bi bi-gear-wide-connected"></i>
                          <p>Master Jenis Biaya</p>
                        </a>
                    </li>
                    @endif
                    
                    <li class="nav-item mt-2">
                      <a href="{{ route('finance.reports.index') }}" class="nav-link {{ request()->routeIs('finance.reports.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-file-earmark-spreadsheet-fill text-info"></i>
                        <p>Laporan & Rekap</p>
                      </a>
                    </li>
                  </ul>
                </li>
                @endif

                <!-- Manajemen Mading -->
                @if(Auth::user()->isDirektur())
                <li class="nav-item">
                  <a href="{{ route('mading-admin.index') }}" class="nav-link {{ request()->routeIs('mading-admin.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-newspaper"></i>
                    <p>Manajemen Mading</p>
                  </a>
                </li>
                @endif

                <!-- User Management -->
                @if(Auth::user()->isDirektur())
                <li class="nav-item {{ request()->routeIs('users.*') || request()->routeIs('administrators.*') || request()->routeIs('financial-admins.*') || request()->routeIs('gurukaryawans.user-index') || request()->routeIs('admin-students.*') || request()->routeIs('login-history.*') || request()->routeIs('leadership-users.*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ request()->routeIs('users.*') || request()->routeIs('administrators.*') || request()->routeIs('financial-admins.*') || request()->routeIs('gurukaryawans.user-index') || request()->routeIs('admin-students.*') || request()->routeIs('login-history.*') || request()->routeIs('leadership-users.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-people-fill"></i>
                    <p>
                      User Management
                      <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <!-- Administrator -->
                    <li class="nav-item">
                      <a href="{{ route('administrators.index') }}" class="nav-link {{ request()->routeIs('administrators.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-gear"></i>
                        <p>Administrator</p>
                      </a>
                    </li>
                    
                    <!-- Admin Keuangan -->
                    <li class="nav-item">
                      <a href="{{ route('financial-admins.index') }}" class="nav-link {{ request()->routeIs('financial-admins.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-cash-coin"></i>
                        <p>Admin Keuangan</p>
                      </a>
                    </li>
                    



                    <!-- Admin Mading / User Mading -->
                    <li class="nav-item">
                      <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-display"></i>
                        <p>User Mading</p>
                      </a>
                    </li>

                    <!-- Histori Login -->
                    <li class="nav-item">
                      <a href="{{ route('login-history.index') }}" class="nav-link {{ request()->routeIs('login-history.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-clock-history"></i>
                        <p>Histori Login</p>
                      </a>
                    </li>

                    <!-- Pimpinan (Direktur) -->
                    <li class="nav-item">
                      <a href="{{ route('leadership-users.index') }}" class="nav-link {{ request()->routeIs('leadership-users.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>Pimpinan</p>
                      </a>
                    </li>

                    <!-- Guru Dan Karyawan (User Guru) -->
                    <li class="nav-item">
                      <a href="{{ route('gurukaryawans.user-index') }}" class="nav-link {{ request()->routeIs('gurukaryawans.user-index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-vcard"></i>
                        <p>User Guru</p>
                      </a>
                    </li>

                    <!-- User Siswa (Integrated into Management) -->
                    <li class="nav-item">
                      <a href="{{ route('admin-students.index') }}" class="nav-link {{ request()->routeIs('admin-students.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-mortarboard-fill"></i>
                        <p>User Siswa</p>
                      </a>
                    </li>
                      </ul>
                    </li>
                      
                    <!-- Administrasi Guru (For Teachers) -->




                    <!-- Backup -->
                    <li class="nav-item">
                        <a href="{{ route('backups.index') }}" class="nav-link {{ request()->routeIs('backups.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-database-fill-gear"></i>
                            <p>Backup Database</p>
                        </a>
                    </li>

                    <!-- Settings -->
                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-sliders"></i>
                            <p>Atur Aplikasi</p>
                        </a>
                    </li>
                  @endif
                @endif

            @if(Auth::user()->role === 'siswa')
                <!-- Menu Siswa -->
                <li class="nav-header">MENU SISWA</li>
                
                <li class="nav-item">
                  <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-speedometer"></i>
                    <p>Dashboard</p>
                  </a>
                </li>
                
                <li class="nav-item">
                  <a href="{{ route('siswa.profil') }}" class="nav-link {{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-person-circle text-info"></i>
                    <p>Profil</p>
                  </a>
                </li>

                <li class="nav-header" style="font-size: 0.65rem; padding-top: 10px;">KEUANGAN</li>
                <li class="nav-item">
                  <a href="{{ route('siswa.payments.history') }}" class="nav-link {{ request()->routeIs('siswa.payments.history') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-wallet2 text-success"></i>
                    <p>Riwayat Pembayaran</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('siswa.payments.arrears') }}" class="nav-link {{ request()->routeIs('siswa.payments.arrears') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-receipt text-warning"></i>
                    <p>Tunggakan</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('siswa.payments.requests.index') }}" class="nav-link {{ request()->routeIs('siswa.payments.requests.*') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-credit-card-2-front text-primary"></i>
                    <p>Buat Pembayaran</p>
                  </a>
                </li>

                <li class="nav-header" style="font-size: 0.65rem; padding-top: 10px;">AKADEMIK</li>

                <li class="nav-item">
                  <a href="{{ route('siswa.jadwal') }}" class="nav-link {{ request()->routeIs('siswa.jadwal') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-calendar-week"></i>
                    <p>Jadwal</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{ route('siswa.nilai') }}" class="nav-link {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-award-fill"></i>
                    <p>Poin & Prestasi</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{ route('siswa.absensi') }}" class="nav-link {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-clock-history"></i>
                    <p>Absensi</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{ route('siswa.pengumuman') }}" class="nav-link {{ request()->routeIs('siswa.pengumuman') ? 'active' : '' }}">
                    <i class="nav-icon bi bi-megaphone"></i>
                    <p>
                        Pengumuman
                        @php
                            $newCount = \App\Models\ClassAnnouncement::where('class_id', Auth::user()->student->class_id ?? 0)
                                ->where('is_active', true)
                                ->where('created_at', '>=', now()->subDays(3))
                                ->count();
                        @endphp
                        @if($newCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $newCount }}</span>
                        @endif
                    </p>
                  </a>
                </li>
            @endif
          </ul>    
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        @yield('content')
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
<footer class="app-footer">
        <div class="d-flex flex-row justify-content-between align-items-center w-100">
            <strong>Copyright &copy; ommad {{ date('Y') }}, All rights reserved.</strong>
            <div class="fw-bold ms-2">V.1.1.40</div>
        </div>
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(jQuery)-->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
      defer
    ></script>
    <!--end::Required Plugin(jQuery)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
      defer
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      crossorigin="anonymous"
      defer
    ></script>
    <!--begin::Required Plugin(Select2)-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
    <!--end::Required Plugin(Select2)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('template/dist/js/adminlte.js') }}" defer></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    
    <!-- Loading Overlay -->
    <div id="loading-overlay" class="loading-overlay" style="display: none;">
        <div class="loading-content" style="padding: 20px;">
            <div class="cute-loader" style="margin-bottom: 0;">
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>

    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease-out;
        }

        .loading-content {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: popIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .cute-loader {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .dot {
            width: 15px;
            height: 15px;
            margin: 0 5px;
            border-radius: 50%;
            background-color: #ff6b6b;
            animation: bounce 1.4s infinite ease-in-out both;
        }

        .dot:nth-child(1) { background-color: #ff9ff3; animation-delay: -0.32s; }
        .dot:nth-child(2) { background-color: #feca57; animation-delay: -0.16s; }
        .dot:nth-child(3) { background-color: #48dbfb; }
        .dot:nth-child(4) { background-color: #1dd1a1; animation-delay: 0.16s; }

        .loading-text {
            color: #576574;
            font-family: 'Source Sans 3', sans-serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
            animation: pulse 1.5s infinite;
        }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('loading-overlay');
            
            // Show loader on form submit
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    // Don't show if the form triggers a download or opens in new tab (basic check)
                    if (!this.target) {
                        loader.style.display = 'flex';
                    }
                });
            });

            // Show loader on link click (optional, can be too aggressive)
            // Better to only do it for specific actions or just rely on browser loading
            // But let's add it for internal links to make it feel app-like
            // Improved loader logic
            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (href && href.charAt(0) === '/' && !href.startsWith('#') && !this.getAttribute('data-bs-toggle') && !this.getAttribute('target')) {
                         setTimeout(() => {
                             if (loader) loader.style.display = 'flex';
                         }, 100);
                    }
                });
            });

            // Hide on page load (just in case back button used)
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    loader.style.display = 'none';
                }
            });
        });

        // Global SweetAlert2 Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Auto-show Laravel flash messages with SweetAlert2
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}"
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
            });
        @endif
    </script>

    @stack('scripts')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js')
                    .then(reg => console.log('Service Worker registered', reg))
                    .catch(err => console.error('Service Worker registration failed', err));
            });
        }
    </script>
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
