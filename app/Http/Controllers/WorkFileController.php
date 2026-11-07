<?php

namespace App\Http\Controllers;

use App\Models\WorkFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WorkFileController extends Controller
{
    const ALLOWED_EXTENSIONS = ['xlsx', 'xls', 'csv', 'pdf', 'doc', 'docx', 'zip', 'rar', 'jpg', 'jpeg', 'png'];

    public function index(Request $request)
    {
        $userId = Auth::id();
        $query  = WorkFile::forUser($userId);

        if ($request->filled('date')) {
            $query->whereDate('upload_date', $request->date);
        }
        if ($request->filled('month')) {
            $query->whereYear('upload_date', substr($request->month, 0, 4))
                  ->whereMonth('upload_date', substr($request->month, 5, 2));
        }
        if ($request->filled('search')) {
            $query->where('original_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $files        = $query->orderBy('upload_date', 'desc')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20)
                              ->withQueryString();

        $groupedFiles = $files->getCollection()
                              ->groupBy(fn($f) => $f->upload_date->format('Y-m-d'));

        return view('files.index', compact('files', 'groupedFiles'));
    }

    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'files'       => 'required|array|min:1|max:10',
            'files.*'     => [
                'required', 'file', 'max:51200',
                function ($attribute, $value, $fail) {
                    $ext = strtolower($value->getClientOriginalExtension());
                    if (!in_array($ext, self::ALLOWED_EXTENSIONS)) {
                        $fail("Format file tidak didukung: .{$ext}");
                    }
                },
            ],
            'description' => 'nullable|string|max:500',
            'upload_date' => 'required|date',
            'source'      => 'nullable|string|max:100',
        ]);

        foreach ($request->file('files') as $file) {
            $ext        = strtolower($file->getClientOriginalExtension());
            $storedName = Carbon::parse($request->upload_date)->format('Ymd') . '_' . Str::uuid() . '.' . $ext;
            $path       = $file->storeAs('work-files', $storedName, 'local');

            WorkFile::create([
                'user_id'       => Auth::id(),
                'original_name' => $file->getClientOriginalName(),
                'stored_name'   => $storedName,
                'path'          => $path,
                'mime_type'     => $file->getMimeType(),
                'size'          => $file->getSize(),
                'description'   => $request->description,
                'source'        => $request->source ?? 'whatsapp',
                'upload_date'   => $request->upload_date,
            ]);
        }

        return redirect()->route('files.index')
            ->with('success', count($request->file('files')) . ' file berhasil disimpan!');
    }

    public function download(WorkFile $workFile)
    {
        abort_if($workFile->user_id !== Auth::id(), 403);
        abort_unless(Storage::disk('local')->exists($workFile->path), 404, 'File tidak ditemukan.');
        return Storage::disk('local')->download($workFile->path, $workFile->original_name);
    }

    public function destroy(WorkFile $workFile)
    {
        abort_if($workFile->user_id !== Auth::id(), 403);
        Storage::disk('local')->delete($workFile->path);
        $workFile->delete();
        return redirect()->route('files.index')
            ->with('success', 'File berhasil dihapus.');
    }

    public function edit(WorkFile $workFile)
    {
        abort_if($workFile->user_id !== Auth::id(), 403);
        return view('files.edit', compact('workFile'));
    }

    public function update(Request $request, WorkFile $workFile)
    {
        abort_if($workFile->user_id !== Auth::id(), 403);
        $request->validate([
            'description' => 'nullable|string|max:500',
            'upload_date' => 'required|date',
            'source'      => 'nullable|string|max:100',
        ]);
        $workFile->update($request->only('description', 'upload_date', 'source'));
        return redirect()->route('files.index')
            ->with('success', 'Info file berhasil diperbarui.');
    }
}
