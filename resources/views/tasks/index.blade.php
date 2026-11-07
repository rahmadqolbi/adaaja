@extends('layouts.app')
@section('title', 'Tugas Saya')
@section('page-title', 'Tugas Saya')

@section('content')

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Cari tugas..." value="{{ request('search') }}">
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="pending"     {{ request('status')=='pending'     ?'selected':'' }}>Belum</option>
                    <option value="in_progress" {{ request('status')=='in_progress' ?'selected':'' }}>Proses</option>
                    <option value="done"        {{ request('status')=='done'        ?'selected':'' }}>Selesai</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">Semua Prioritas</option>
                    <option value="high"   {{ request('priority')=='high'   ?'selected':'' }}>Tinggi</option>
                    <option value="medium" {{ request('priority')=='medium' ?'selected':'' }}>Sedang</option>
                    <option value="low"    {{ request('priority')=='low'    ?'selected':'' }}>Rendah</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="tag_id" class="form-select form-select-sm">
                    <option value="">Semua Tag</option>
                    @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ request('tag_id')==$tag->id ?'selected':'' }}>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-primary flex-grow-1"><i class="bi bi-search"></i></button>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-light"><i class="bi bi-x-lg"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <span class="text-muted" style="font-size:.84rem;">{{ $tasks->total() }} tugas ditemukan</span>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>Tambah Tugas
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead style="background:#f8fafc;font-size:.8rem;">
                <tr>
                    <th class="ps-3">Judul</th>
                    <th>Kategori</th>
                    <th>Tag</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr class="task-row {{ $task->is_overdue ? 'table-danger' : '' }}">
                    <td class="ps-3">
                        <div class="fw-semibold" style="font-size:.88rem;">{{ $task->title }}</div>
                        @if($task->description)
                        <div class="text-muted text-truncate" style="font-size:.74rem;max-width:220px;">{{ $task->description }}</div>
                        @endif
                    </td>
                    <td>
                        @if($task->category)
                        <span class="badge" style="background:{{ $task->category->color }};font-size:.7rem;">{{ $task->category->name }}</span>
                        @else <span class="text-muted" style="font-size:.78rem;">—</span>
                        @endif
                    </td>
                    <td>
                        @foreach($task->tags->take(2) as $tag)
                        <span class="badge me-1" style="background:{{ $tag->color }};font-size:.65rem;">{{ $tag->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="badge {{ $task->priority_badge_class }}" style="font-size:.72rem;">{{ $task->priority_label }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('tasks.update-status', $task) }}">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select form-select-sm"
                                    style="font-size:.75rem;width:auto;" onchange="this.form.submit()">
                                <option value="pending"     {{ $task->status=='pending'     ?'selected':'' }}>Belum</option>
                                <option value="in_progress" {{ $task->status=='in_progress' ?'selected':'' }}>Proses</option>
                                <option value="done"        {{ $task->status=='done'        ?'selected':'' }}>Selesai</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        @if($task->deadline)
                        <span style="font-size:.8rem;" class="{{ $task->is_overdue ? 'text-danger fw-semibold':'' }}">
                            {{ $task->deadline->translatedFormat('d M Y') }}
                        </span>
                        @else <span class="text-muted" style="font-size:.78rem;">—</span>
                        @endif
                    </td>
                    <td class="text-end pe-3">
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                  onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                        Belum ada tugas. <a href="{{ route('tasks.create') }}">Tambah sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tasks->hasPages())
    <div class="card-footer bg-white py-2">{{ $tasks->links() }}</div>
    @endif
</div>

@endsection
