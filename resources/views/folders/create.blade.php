@extends('layouts.app')
@section('title', 'Buat Folder')
@section('page-title', 'Buat Folder Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold">
            <i class="bi bi-folder-plus me-2 text-primary"></i>
            {{ $parent ? 'Buat Subfolder di '.$parent->name : 'Buat Folder Baru' }}
        </h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('folders.store') }}">
            @csrf

            @if($parent)
            <input type="hidden" name="parent_id" value="{{ $parent->id }}">
            @else
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Di dalam folder (opsional)</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Root (tidak ada parent) —</option>
                    @foreach($folders as $folder)
                    <option value="{{ $folder->id }}">{{ $folder->full_path }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Folder <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" placeholder="Contoh: Laporan 2026" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2"
                          placeholder="Keterangan folder...">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="is_shared"
                           id="is_shared" value="1" {{ old('is_shared') ? 'checked':'' }}>
                    <label class="form-check-label" for="is_shared" style="font-size:.85rem;">
                        <i class="bi bi-people me-1"></i>Bagikan ke rekan kantor
                    </label>
                </div>
                <div class="form-text" style="font-size:.74rem;">Jika aktif, rekan kantor bisa melihat folder dan file di dalamnya</div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('folders.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-folder-plus me-1"></i>Buat Folder
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
