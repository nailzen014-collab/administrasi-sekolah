<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'NOVARA') }}</title>
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
</head>
<body>

<div class="app-shell">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">

        <!-- Brand -->
        <div class="sidebar-brand">
            <span class="sidebar-brand-dot"></span>
            <span class="sidebar-brand-name">{{ config('app.name', 'NOVARA') }}</span>
        </div>

        <!-- Pill role -->
        <div>
            <span class="pill-role">
                <span class="pill-role-dot"></span>
                {{ auth()->user()->role }}
            </span>
        </div>

        <!-- Navigasi sesuai role -->
        <nav class="sidebar-nav">

            @php $role = auth()->user()->role; @endphp

            {{-- ADMIN --}}
            @if ($role === 'admin')
                @if (Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                @endif
                @if (Route::has('admin.students.index'))
                    <a href="{{ route('admin.students.index') }}"
                       class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                        Data siswa
                    </a>
                @endif
                @if (Route::has('admin.guru.index'))
                    <a href="{{ route('admin.guru.index') }}"
                       class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
                        Data guru
                    </a>
                @endif
                @if (Route::has('admin.pengajaran.index'))
                    <a href="{{ route('admin.pengajaran.index') }}"
                       class="nav-item {{ request()->routeIs('admin.pengajaran.*') ? 'active' : '' }}">
                        Data pengajaran
                    </a>
                @endif
                @if (Route::has('admin.pemeriksaan.index'))
                    <a href="{{ route('admin.pemeriksaan.index') }}"
                       class="nav-item {{ request()->routeIs('admin.pemeriksaan.*') ? 'active' : '' }}">
                        Pemeriksaan
                        @isset($badgePemeriksaan)
                            <span class="nav-badge">{{ $badgePemeriksaan }}</span>
                        @endisset
                    </a>
                @endif
            @endif

            {{-- GURU --}}
            @if ($role === 'guru')
                @if (Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                @endif
                @if (Route::has('guru.persetujuan.index'))
                    <a href="{{ route('guru.persetujuan.index') }}"
                       class="nav-item {{ request()->routeIs('guru.persetujuan.*') ? 'active' : '' }}">
                        Pengajuan masuk
                        @isset($badgePersetujuan)
                            <span class="nav-badge">{{ $badgePersetujuan }}</span>
                        @endisset
                    </a>
                @endif
            @endif

            {{-- SISWA --}}
            @if ($role === 'siswa')
                @if (Route::has('dashboard'))
                    <a href="{{ route('dashboard') }}"
                       class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                @endif
                @if (Route::has('siswa.pengajuan.index'))
                    <a href="{{ route('siswa.pengajuan.index') }}"
                       class="nav-item {{ request()->routeIs('siswa.pengajuan.*') ? 'active' : '' }}">
                        Pengajuan saya
                    </a>
                @endif
            @endif

        </nav>

        <!-- Footer: nama user + keluar -->
        <div class="sidebar-footer">
            <div>
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost btn-sm" style="width:100%; justify-content:center;">
                    Keluar
                </button>
            </form>
        </div>

    </aside>

    <!-- ===== KONTEN UTAMA ===== -->
    <div class="main-content">

        <!-- Pita judul halftone -->
        @isset($header)
            <header class="halftone page-header">
                <div>
                    {{ $header }}
                </div>
                @isset($headerAction)
                    <div>{{ $headerAction }}</div>
                @endisset
            </header>
        @endisset

        <!-- Pesan flash (ditampilkan lewat SweetAlert toast) -->
        <div id="flash-data"
             class="hidden"
             data-success="{{ session('success') }}"
             data-error="{{ session('error') }}"
             aria-hidden="true"></div>

        <!-- Konten halaman -->
        <main class="page-body">
            {{ $slot }}
        </main>

    </div>

</div>

<script src="{{ asset('vendor/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/alerts.js') }}"></script>
</body>
</html>
