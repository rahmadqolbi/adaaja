@extends('layouts.app')
@section('title', 'Tambah Tugas')
@section('page-title', 'Tambah Tugas Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-plus-circle me-2 text-primary"></i>Form Tugas Baru</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Judul Tugas <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}" placeholder="Contoh: Rekap laporan bulanan" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3"
                          placeholder="Detail tugas...">{{ old('description') }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Prioritas <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="low"    {{ old('priority','medium')=='low'    ?'selected':'' }}>🟢 Rendah</option>
                        <option value="medium" {{ old('priority','medium')=='medium' ?'selected':'' }}>🟡 Sedang</option>
                        <option value="high"   {{ old('priority','medium')=='high'   ?'selected':'' }}>🔴 Tinggi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="pending"     {{ old('status','pending')=='pending'     ?'selected':'' }}>⏳ Belum</option>
                        <option value="in_progress" {{ old('status','pending')=='in_progress' ?'selected':'' }}>🔄 Proses</option>
                        <option value="done"        {{ old('status','pending')=='done'        ?'selected':'' }}>✅ Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Deadline</label>
                    <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Tag</label>
                    <div class="border rounded p-2" style="max-height:120px;overflow-y:auto;">
                        @forelse($tags as $tag)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" name="tags[]"
                                   value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags',[])) ? 'checked':'' }}>
                            <label class="form-check-label" for="tag_{{ $tag->id }}" style="font-size:.8rem;">
                                <span class="badge" style="background:{{ $tag->color }}">{{ $tag->name }}</span>
                            </label>
                        </div>
                        @empty
                        <span class="text-muted" style="font-size:.8rem;">Belum ada tag.</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('tasks.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
