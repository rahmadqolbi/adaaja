@extends('layouts.app')
@section('title', 'Folder File')
@section('page-title', 'Folder File')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted" style="font-size:.84rem;">
        {{ $folders->count() }} folder
    </span>
    <a href="{{ route('folders.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-folder-plus me-1"></i>Buat Folder
    </a>
</div>

{{-- Grid Folder --}}
@if($folders->count())
<div class="row g-3 mb-4">
    @foreach($folders as $folder)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="cursor:pointer;"
             onclick="window.location='{{ route('folders.show', $folder) }}'">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="font-size:1.8rem;">📁</span>
                    @if($folder->is_shared)
                    <span class="badge bg-info" style="font-size:.65rem;">Dibagikan</span>
                    @endif
                    @if($folder->user_id !== Auth::id())
                    <span class="badge bg-secondary" style="font-size:.65rem;">Milik Orang Lain</span>
                    @endif
                </div>
                <div class="fw-semibold text-truncate" style="font-size:.88rem;">{{ $folder->name }}</div>
                @if($folder->description)
                <div class="text-muted text-truncate" style="font-size:.74rem;">{{ $folder->description }}</div>
                @endif
                <div class="text-muted mt-1" style="font-size:.74rem;">
                    {{ $folder->children->count() }} subfolder ·
                    {{ $folder->files->count() }} file
                </div>
            </div>
            @if($folder->user_id === Auth::id())
            <div class="card-footer bg-white border-top py-2 d-flex gap-1 justify-content-end">
                <a href="{{ route('folders.edit', $folder) }}"
                   class="btn btn-sm btn-outline-primary"
                   onclick="event.stopPropagation()">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('folders.destroy', $folder) }}"
                      onsubmit="return confirm('Hapus folder ini? File akan dipindah ke Tanpa Folder.')"
                      onclick="event.stopPropagation()">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- File Tanpa Folder --}}
@if($unsortedFiles->count())
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-2">
        <span class="fw-semibold" style="font-size:.88rem;">
            <i class="bi bi-files me-2 text-secondary"></i>Tanpa Folder
            <span class="badge bg-secondary ms-1">{{ $unsortedFiles->count() }}</span>
        </span>
    </div>
    <div class="card-body p-0">
        @foreach($unsortedFiles as $file)
        <div class="d-flex align-items-center gap-3 p-3 border-bottom">
            <div style="font-size:1.5rem;width:36px;text-align:center;">{{ $file->file_icon }}</div>
            <div class="flex-grow-1">
                <div class="fw-semibold" style="font-size:.85rem;">{{ $file->original_name }}</div>
                <div class="text-muted" style="font-size:.74rem;">
                    {{ $file->file_size_human }} · {{ $file->upload_date->translatedFormat('d M Y') }}
                </div>
            </div>
            {{-- Pindah ke folder --}}
            <form method="POST" action="{{ route('files.move', $file) }}" class="d-flex gap-1">
                @csrf @method('PATCH')
                <select name="folder_id" class="form-select form-select-sm" style="width:150px;font-size:.78rem;">
                    <option value="">— Pilih Folder —</option>
                    @foreach($folders as $folder)
                    <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Pindah</button>
            </form>
            <a href="{{ route('files.download', $file) }}" class="btn btn-sm btn-outline-success">
                <i class="bi bi-download"></i>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

@if($folders->isEmpty() && $unsortedFiles->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-folder-x" style="font-size:3rem;"></i>
        <p class="mt-3 mb-1">Belum ada folder.</p>
        <a href="{{ route('folders.create') }}" class="btn btn-primary btn-sm mt-2">
            <i class="bi bi-folder-plus me-1"></i>Buat Folder Pertama
        </a>
    </div>
</div>
@endif

@endsection
