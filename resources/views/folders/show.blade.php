@extends('layouts.app')
@section('title', $folder->name)
@section('page-title', $folder->name)

@section('content')

{{-- Breadcrumb --}}
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.84rem;">
        <li class="breadcrumb-item"><a href="{{ route('folders.index') }}">Folder</a></li>
        @if($folder->parent)
        <li class="breadcrumb-item"><a href="{{ route('folders.show', $folder->parent) }}">{{ $folder->parent->name }}</a></li>
        @endif
        <li class="breadcrumb-item active">{{ $folder->name }}</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <span class="text-muted" style="font-size:.84rem;">
            {{ $subfolders->count() }} subfolder · {{ $files->count() }} file
        </span>
        @if($folder->is_shared)
        <span class="badge bg-info ms-2" style="font-size:.74rem;">Dibagikan ke rekan</span>
        @endif
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('folders.create', ['parent_id' => $folder->id]) }}"
           class="btn btn-sm btn-outline-primary">
            <i class="bi bi-folder-plus me-1"></i>Subfolder
        </a>
      @if($folder->user_id === Auth::id())
<a href="{{ route('files.create') }}" class="btn btn-sm btn-primary">
    <i class="bi bi-cloud-upload me-1"></i>Upload File
</a>
@endif
    </div>
</div>

{{-- Subfolders --}}
@if($subfolders->count())
<div class="row g-3 mb-4">
    @foreach($subfolders as $sub)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="cursor:pointer;"
             onclick="window.location='{{ route('folders.show', $sub) }}'">
            <div class="card-body p-3">
                <div class="mb-2" style="font-size:1.8rem;">📁</div>
                <div class="fw-semibold text-truncate" style="font-size:.88rem;">{{ $sub->name }}</div>
                <div class="text-muted" style="font-size:.74rem;">{{ $sub->files->count() }} file</div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Files --}}
@if($files->count())
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-2">
        <span class="fw-semibold" style="font-size:.88rem;">
            <i class="bi bi-files me-2 text-primary"></i>File dalam folder ini
        </span>
    </div>
    <div class="card-body p-0">
        @foreach($files as $file)
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
            <a href="{{ route('files.edit', $file) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil"></i>
            </a>
            <form method="POST" action="{{ route('files.destroy', $file) }}"
                  onsubmit="return confirm('Hapus file ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                    <i class="bi bi-trash"></i>
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-4 text-muted" style="font-size:.85rem;">
        <i class="bi bi-folder-x fs-2 d-block mb-2"></i>
        Folder ini kosong. <a href="{{ route('files.create') }}">Upload file</a>
    </div>
</div>
@endif

@endsection
