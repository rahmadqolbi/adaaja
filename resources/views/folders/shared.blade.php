@extends('layouts.app')
@section('title', 'Dibagikan ke Saya')
@section('page-title', 'Dibagikan ke Saya')

@section('content')

@if($sharedFolders->isEmpty())
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-people" style="font-size:3rem;"></i>
        <p class="mt-3 mb-1">Belum ada folder yang dibagikan ke kamu.</p>
        <small>Folder akan muncul di sini jika rekan kantor membagikannya.</small>
    </div>
</div>
@else

<div class="row g-3">
    @foreach($sharedFolders as $folder)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="cursor:pointer;"
             onclick="window.location='{{ route('folders.show', $folder) }}'">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="font-size:1.8rem;">📁</span>
                    <span class="badge bg-info" style="font-size:.65rem;">Dibagikan</span>
                </div>
                <div class="fw-semibold text-truncate" style="font-size:.88rem;">
                    {{ $folder->name }}
                </div>
                @if($folder->description)
                <div class="text-muted text-truncate" style="font-size:.74rem;">
                    {{ $folder->description }}
                </div>
                @endif
                <div class="text-muted mt-2" style="font-size:.74rem;">
                    <i class="bi bi-person me-1"></i>{{ $folder->user->name }}
                </div>
                <div class="text-muted" style="font-size:.74rem;">
                    <i class="bi bi-file-earmark me-1"></i>{{ $folder->files->count() }} file
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endif

@endsection
