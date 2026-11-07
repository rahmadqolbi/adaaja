@extends('layouts.app')
@section('title', 'Upload File')
@section('page-title', 'Upload File Baru')

@section('content')
<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom py-3">
        <h6 class="mb-0 fw-semibold"><i class="bi bi-cloud-upload me-2 text-primary"></i>Upload File dari WhatsApp</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('files.store') }}" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size:.85rem;">
                    Pilih File <span class="text-danger">*</span>
                    <span class="text-muted fw-normal">(maks 10 file, 50 MB/file)</span>
                </label>
                <div id="dropZone" class="border rounded-3 text-center p-5"
                     style="border-style:dashed!important;cursor:pointer;background:#f8fafc;transition:all .2s;">
                    <i class="bi bi-cloud-arrow-up fs-1 text-primary d-block mb-2"></i>
                    <div class="fw-semibold">Klik untuk pilih file</div>
                    <div class="text-muted mt-1" style="font-size:.8rem;">
                        atau seret & lepas di sini<br>
                        xlsx, xls, csv, pdf, doc, docx, zip, jpg, png
                    </div>
                    <input type="file" name="files[]" id="fileInput" multiple
                           accept=".xlsx,.xls,.csv,.pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png"
                           class="d-none" onchange="previewFiles(this)">
                </div>
                <div id="filePreview" class="mt-2"></div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Tanggal File <span class="text-danger">*</span></label>
                    <input type="date" name="upload_date" class="form-control"
                           value="{{ old('upload_date', now()->format('Y-m-d')) }}" required>
                    <div class="form-text" style="font-size:.74rem;">Tanggal file dikirim di WhatsApp</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" style="font-size:.85rem;">Sumber</label>
                    <input type="text" name="source" class="form-control"
                           value="{{ old('source', 'WhatsApp') }}">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size:.85rem;">Catatan</label>
                <textarea name="description" class="form-control" rows="2"
                          placeholder="Contoh: Laporan Oktober dari grup WA Marketing">{{ old('description') }}</textarea>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('files.index') }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-cloud-upload me-1"></i>Upload Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
const dropZone  = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');

dropZone.addEventListener('click', () => fileInput.click());
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.background = '#eff6ff'; });
dropZone.addEventListener('dragleave', () => { dropZone.style.background = '#f8fafc'; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.background = '#f8fafc';
    fileInput.files = e.dataTransfer.files;
    previewFiles(fileInput);
});

function previewFiles(input) {
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    const icons = { xlsx:'📊',xls:'📊',csv:'📊',pdf:'📄',doc:'📝',docx:'📝',zip:'📦',rar:'📦',jpg:'🖼️',jpeg:'🖼️',png:'🖼️' };
    Array.from(input.files).forEach(file => {
        const ext  = file.name.split('.').pop().toLowerCase();
        const size = file.size < 1048576 ? (file.size/1024).toFixed(1)+' KB' : (file.size/1048576).toFixed(1)+' MB';
        preview.innerHTML += `
            <div class="d-flex align-items-center gap-2 py-1 border-bottom">
                <span style="font-size:1.2rem">${icons[ext]||'📎'}</span>
                <span style="font-size:.82rem;flex-grow:1" class="text-truncate">${file.name}</span>
                <span class="text-muted" style="font-size:.75rem">${size}</span>
            </div>`;
    });
}

document.getElementById('uploadForm').addEventListener('submit', () => {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Mengupload...';
});
</script>
@endpush
