@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label'=>'Total Tugas',          'value'=>$stats['total'],       'icon'=>'bi-list-task',          'bg'=>'#eff6ff','ic'=>'#3b82f6'],
            ['label'=>'Belum Dikerjakan',      'value'=>$stats['pending'],     'icon'=>'bi-hourglass-split',    'bg'=>'#fefce8','ic'=>'#eab308'],
            ['label'=>'Sedang Proses',         'value'=>$stats['in_progress'], 'icon'=>'bi-arrow-repeat',       'bg'=>'#eff6ff','ic'=>'#3b82f6'],
            ['label'=>'Selesai',               'value'=>$stats['done'],        'icon'=>'bi-check-circle-fill',  'bg'=>'#f0fdf4','ic'=>'#16a34a'],
            ['label'=>'Terlambat',             'value'=>$stats['overdue'],     'icon'=>'bi-exclamation-circle', 'bg'=>'#fef2f2','ic'=>'#ef4444'],
            ['label'=>'Jatuh Tempo Hari Ini',  'value'=>$stats['due_today'],   'icon'=>'bi-calendar-event',     'bg'=>'#faf5ff','ic'=>'#8b5cf6'],
        ];
    @endphp

    @foreach($cards as $card)
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 p-3">
                <div class="stat-icon" style="background:{{ $card['bg'] }}">
                    <i class="bi {{ $card['icon'] }}" style="color:{{ $card['ic'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4 lh-1">{{ $card['value'] }}</div>
                    <div class="text-muted" style="font-size:.74rem;">{{ $card['label'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold" style="font-size:.88rem;">
                    <i class="bi bi-calendar-week text-primary me-2"></i>Mendekati Deadline (7 Hari)
                </span>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light" style="font-size:.75rem;">Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingTasks as $task)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom task-row">
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">{{ $task->title }}</div>
                        <div class="text-muted" style="font-size:.74rem;">
                            <i class="bi bi-calendar3 me-1"></i>{{ $task->deadline->translatedFormat('d M Y') }}
                            @if($task->category)
                                · <span class="badge" style="background:{{ $task->category->color }};font-size:.65rem;">{{ $task->category->name }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="badge {{ $task->status_badge_class }}" style="font-size:.7rem;">{{ $task->status_label }}</span>
                </div>
                @empty
                <div class="p-4 text-center text-muted" style="font-size:.85rem;">
                    <i class="bi bi-check-all fs-3 d-block mb-2 text-success"></i>
                    Tidak ada tugas mendekati deadline
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom py-2">
                <span class="fw-semibold" style="font-size:.88rem;">
                    <i class="bi bi-exclamation-triangle text-danger me-2"></i>Tugas Terlambat
                </span>
            </div>
            <div class="card-body p-0">
                @forelse($overdueTasks as $task)
                <div class="d-flex align-items-start gap-2 p-3 border-bottom task-row overdue-row">
                    <div class="flex-grow-1">
                        <div class="fw-semibold" style="font-size:.85rem;">{{ $task->title }}</div>
                        <div class="text-danger" style="font-size:.74rem;">
                            <i class="bi bi-clock me-1"></i>{{ $task->deadline->translatedFormat('d M Y') }}
                        </div>
                    </div>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary" style="font-size:.72rem;">Update</a>
                </div>
                @empty
                <div class="p-4 text-center text-muted" style="font-size:.85rem;">
                    <i class="bi bi-emoji-smile fs-3 d-block mb-2 text-success"></i>
                    Tidak ada tugas terlambat 🎉
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-2">
        <span class="fw-semibold" style="font-size:.88rem;">
            <i class="bi bi-clock-history text-primary me-2"></i>File Terbaru
        </span>
        <a href="{{ route('files.index') }}" class="btn btn-sm btn-light" style="font-size:.75rem;">Semua</a>
    </div>
    <div class="card-body p-0">
        @forelse($recentFiles as $file)
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
            <div style="font-size:1.5rem;width:36px;text-align:center;">{{ $file->file_icon }}</div>
            <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:.85rem;">{{ $file->original_name }}</div>
                <div class="text-muted" style="font-size:.74rem;">
                    {{ $file->file_size_human }} · {{ $file->upload_date->translatedFormat('d M Y') }}
                </div>
            </div>
            <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download"></i>
            </a>
        </div>
        @empty
        <div class="text-center text-muted py-4" style="font-size:.85rem;">
            <i class="bi bi-folder-x fs-2 d-block mb-2"></i>
            Belum ada file. <a href="{{ route('files.create') }}">Upload sekarang</a>
        </div>
        @endforelse
    </div>
</div>

@endsection
