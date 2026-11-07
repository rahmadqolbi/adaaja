<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AdaAja') — AdaAja</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 240px;
            --sidebar-bg: #1e293b;
            --sidebar-text: #cbd5e1;
            --sidebar-active: #3b82f6;
        }

        body { background: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }

        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1030;
            transition: transform .25s ease;
        }
        #sidebar .sidebar-brand {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: .95rem;
        }
        #sidebar .sidebar-brand small { color: var(--sidebar-text); font-size: .72rem; }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: .55rem 1.25rem;
            border-radius: 6px;
            margin: 2px 8px;
            font-size: .88rem;
            display: flex;
            align-items: center;
            gap: .6rem;
            transition: background .15s, color .15s;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        #sidebar .nav-link.active { background: var(--sidebar-active); color: #fff; }
        #sidebar .nav-section {
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
            padding: .9rem 1.4rem .3rem;
        }
        #main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        #topbar {
            height: 56px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .page-content { padding: 1.5rem; }

        .badge-danger    { background: #ef4444; color: #fff; }
        .badge-warning   { background: #f59e0b; color: #fff; }
        .badge-success   { background: #22c55e; color: #fff; }
        .badge-primary   { background: #3b82f6; color: #fff; }
        .badge-secondary { background: #64748b; color: #fff; }

        .stat-card { border: none; border-radius: 12px; }
        .stat-card .stat-icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .task-row { transition: background .1s; }
        .task-row:hover { background: #f8fafc; }
        .overdue-row { border-left: 3px solid #ef4444 !important; }
        .file-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: box-shadow .15s;
        }
        .file-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }

        .sidebar-user {
            position: absolute;
            bottom: 0; width: 100%;
            border-top: 1px solid rgba(255,255,255,.07);
            padding: .75rem 1rem;
        }

        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>
<body>

<nav id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-archive-fill me-2" style="color:#3b82f6"></i>AdaAja</h5>
        <small>Manajemen Kerja Harian</small>
    </div>

    <div class="pt-2">
        <div class="nav-section">Menu</div>

        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-section">Tugas</div>

        <a href="{{ route('tasks.index') }}"
           class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
            <i class="bi bi-check2-square"></i> Semua Tugas
        </a>

        <a href="{{ route('tasks.create') }}"
           class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Tambah Tugas
        </a>

        <div class="nav-section">File</div>

        <a href="{{ route('files.index') }}"
           class="nav-link {{ request()->routeIs('files.index') ? 'active' : '' }}">
            <i class="bi bi-folder2-open"></i> File Saya
        </a>

        <a href="{{ route('files.create') }}"
           class="nav-link {{ request()->routeIs('files.create') ? 'active' : '' }}">
            <i class="bi bi-cloud-upload"></i> Upload File
        </a>
    </div>

    <div class="sidebar-user">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:30px;height:30px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:.75rem;color:#fff;font-weight:700;">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div>
                <div style="color:#fff;font-size:.8rem;font-weight:600;">{{ Auth::user()->name }}</div>
                <div style="color:#94a3b8;font-size:.7rem;">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-sm w-100"
                    style="background:rgba(255,255,255,.07);color:#cbd5e1;font-size:.78rem;">
                <i class="bi bi-box-arrow-left me-1"></i>Keluar
            </button>
        </form>
    </div>
</nav>

<div id="main-content">
    <div id="topbar" class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
        </div>
        <span class="text-muted" style="font-size:.8rem;">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <div class="page-content pb-0">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <div class="page-content">
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar').classList.toggle('show');
    });
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            bootstrap.Alert.getOrCreateInstance(el)?.close();
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
