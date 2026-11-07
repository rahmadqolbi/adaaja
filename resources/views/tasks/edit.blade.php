@extends('layouts.app')
@section('title', 'Edit Tugas')
@section('page-title', 'Edit Tugas')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil me-2 text-warning"></i>Edit Tugas</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('tasks.update', $task) }}">
            @csrf @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Judul Tugas <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $task->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Prioritas <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        <option value="low"    {{ old('priority',$task->priority)=='low'    ?'selected':'' }}>🟢 Rendah</option>
                        <option value="medium" {{ old('priority',$task->priority)=='medium' ?'selected':'' }}>🟡 Sedang</option>
                        <option value="high"   {{ old('priority',$task->priority)=='high'   ?'selected':'' }}>🔴 Tinggi</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        <option value="pending"     {{ old('status',$task->status)=='pending'     ?'selected':'' }}>⏳ Belum</option>
                        <option value="in_progress" {{ old('status',$task->status)=='in_progress' ?'selected':'' }}>🔄 Proses</option>
                        <option value="done"        {{ old('status',$task->status)=='done'        ?'selected':'' }}>✅ Selesai</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Deadline</label>
                    <input type="date" name="deadline" class="form-control"
                           value="{{ old('deadline', $task->deadline?->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Kategori</label>
                    <select name="category_id" class="form-select">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id',$task->category_id)==$cat->id ?'selected':'' }}>{{ $cat->name }}</option>
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
                                   {{ in_array($tag->id, old('tags',$selectedTags)) ? 'checked':'' }}>
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
                <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
