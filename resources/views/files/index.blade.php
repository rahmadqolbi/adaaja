@extends('layouts.app')
@section('title', 'File Saya')
@section('page-title', 'File Saya')

@section('content')

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('files.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari nama file..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
            </div>
            <div class="col-6 col-md-3">
                <input type="month" name="month" class="form-control form-control-sm" value="{{ request('month') }}">
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-search"></i></button>
                <a href="{{ route('files.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted" style="font-size:.84rem;">{{ $files->total() }} file ditemukan</span>
    <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-cloud-upload me-1"></i>Upload File
    </a>
</div>

@forelse($groupedFiles as $date => $dateFiles)
<div class="mb-4">
    <div class="d-flex align-items-center mb-2 gap-2">
        <span class="badge bg-dark px-3 py-2" style="font-size:.78rem;">
            <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
        </span>
        <div class="flex-grow-1" style="height:1px;background:#e2e8f0;"></div>
        <span class="text-muted" style="font-size:.74rem;">{{ $dateFiles->count() }} file</span>
    </div>
    <div class="row g-2">
        @foreach($dateFiles as $file)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="file-card p-3 bg-white d-flex align-items-center gap-3">
                <div style="font-size:2rem;min-width:40px;text-align:center;">{{ $file->file_icon }}</div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-semibold text-truncate" style="font-size:.85rem;">{{ $file->original_name }}</div>
                    <div class="text-muted" style="font-size:.73rem;">
                        {{ $file->file_size_human }}
                        @if($file->source)· <span class="badge bg-light text-dark" style="font-size:.65rem;">{{ $file->source }}</span>@endif
                    </div>
                    @if($file->description)
                    <div class="text-muted text-truncate mt-1" style="font-size:.73rem;">{{ $file->description }}</div>
                    @endif
                </div>
                <div class="d-flex flex-column gap-1">
                    <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                    <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('files.destroy', $file) }}"
                          onsubmit="return confirm('Hapus file ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-folder-x" style="font-size:3rem;"></i>
        <p class="mt-3 mb-1">Belum ada file tersimpan.</p>
        <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm mt-2">
            <i class="bi bi-cloud-upload me-1"></i>Upload Sekarang
        </a>
    </div>
</div>
@endforelse

@if($files->hasPages())
<div class="mt-3">{{ $files->links() }}</div>
@endif

@endsection
