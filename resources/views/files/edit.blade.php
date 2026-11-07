@extends('layouts.app')
@section('title', 'Edit Info File')
@section('page-title', 'Edit Info File')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Info File</h6>
    </div>
    <div class="card-body">
        <div class="bg-light rounded p-3 mb-4 d-flex align-items-center gap-3">
            <span style="font-size:2rem;">{{ $workFile->file_icon }}</span>
            <div>
                <div class="fw-semibold" style="font-size:.88rem;">{{ $workFile->original_name }}</div>
                <div class="text-muted" style="font-size:.76rem;">{{ $workFile->file_size_human }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('files.update', $workFile) }}">
            @csrf @method('PUT')

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal File <span class="text-danger">*</span></label>
                    <input type="date" name="upload_date" class="form-control"
                           value="{{ old('upload_date', $workFile->upload_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Sumber</label>
                    <input type="text" name="source" class="form-control"
                           value="{{ old('source', $workFile->source) }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Catatan</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $workFile->description) }}</textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('files.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
