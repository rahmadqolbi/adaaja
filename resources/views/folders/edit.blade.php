@extends('layouts.app')
@section('title', 'Edit Folder')
@section('page-title', 'Edit Folder')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Folder</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('folders.update', $folder) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Di dalam folder (opsional)</label>
                <select name="parent_id" class="form-select">
                    <option value="">— Root —</option>
                    @foreach($folders as $f)
                    <option value="{{ $f->id }}" {{ $folder->parent_id == $f->id ? 'selected':'' }}>
                        {{ $f->full_path }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Nama Folder <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $folder->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $folder->description) }}</textarea>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input type="checkbox" class="form-check-input" name="is_shared"
                           id="is_shared" value="1" {{ $folder->is_shared ? 'checked':'' }}>
                    <label class="form-check-label" for="is_shared" style="font-size:.85rem;">
                        <i class="bi bi-people me-1"></i>Bagikan ke rekan kantor
                    </label>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('folders.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-warning text-white">
                    <i class="bi bi-save me-1"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
