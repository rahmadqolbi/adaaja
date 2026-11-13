<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\WorkFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Root folders milik sendiri + yang dishare
        $folders = Folder::with(['children', 'files'])
            ->rootFolders()
            ->accessibleBy($userId)
            ->orderBy('name')
            ->get();

        // File yang belum masuk folder manapun
        $unsortedFiles = WorkFile::forUser($userId)
            ->whereNull('folder_id')
            ->orderBy('upload_date', 'desc')
            ->get();

        return view('folders.index', compact('folders', 'unsortedFiles'));
    }

    public function shared()
{
    $userId = Auth::id();

    // Folder yang dishare oleh orang LAIN (bukan milik sendiri)
    $sharedFolders = Folder::with(['files', 'user'])
        ->where('is_shared', true)
        ->where('user_id', '!=', $userId)
        ->orderBy('name')
        ->get();

    return view('folders.shared', compact('sharedFolders'));
}

    public function show(Folder $folder)
    {
        $this->authorizeFolder($folder);

        $folder->load(['children.files', 'files', 'parent']);

        $subfolders = Folder::with('files')
            ->where('parent_id', $folder->id)
            ->accessibleBy(Auth::id())
            ->orderBy('name')
            ->get();

        $files = WorkFile::where('folder_id', $folder->id)
            ->orderBy('upload_date', 'desc')
            ->get();

        return view('folders.show', compact('folder', 'subfolders', 'files'));
    }

    public function create(Request $request)
    {
        $parentId = $request->get('parent_id');
        $parent   = $parentId ? Folder::find($parentId) : null;

        $folders = Folder::accessibleBy(Auth::id())
            ->orderBy('name')
            ->get();

        return view('folders.create', compact('parent', 'folders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|exists:folders,id',
            'is_shared'   => 'boolean',
        ]);

        Folder::create([
            ...$validated,
            'user_id'   => Auth::id(),
            'is_shared' => $request->boolean('is_shared'),
        ]);

        $redirectTo = $request->parent_id
            ? route('folders.show', $request->parent_id)
            : route('folders.index');

        return redirect($redirectTo)->with('success', 'Folder berhasil dibuat!');
    }

    public function edit(Folder $folder)
    {
        $this->authorizeFolder($folder);

        $folders = Folder::accessibleBy(Auth::id())
            ->where('id', '!=', $folder->id)
            ->orderBy('name')
            ->get();

        return view('folders.edit', compact('folder', 'folders'));
    }

    public function update(Request $request, Folder $folder)
    {
        $this->authorizeFolder($folder);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'parent_id'   => 'nullable|exists:folders,id',
            'is_shared'   => 'boolean',
        ]);

        $folder->update([
            ...$validated,
            'is_shared' => $request->boolean('is_shared'),
        ]);

        return redirect()->route('folders.index')
            ->with('success', 'Folder berhasil diperbarui!');
    }

    public function destroy(Folder $folder)
    {
        $this->authorizeFolder($folder);

        // Pindahkan file ke unsorted dulu sebelum hapus folder
        WorkFile::where('folder_id', $folder->id)
            ->update(['folder_id' => null]);

        $folder->delete();

        return redirect()->route('folders.index')
            ->with('success', 'Folder dihapus. File dipindahkan ke Tanpa Folder.');
    }

    // Pindahkan file ke folder
    public function moveFile(Request $request, WorkFile $workFile)
    {
        abort_if($workFile->user_id !== Auth::id(), 403);

        $request->validate([
            'folder_id' => 'nullable|exists:folders,id',
        ]);

        $workFile->update(['folder_id' => $request->folder_id]);

        return back()->with('success', 'File berhasil dipindahkan!');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function authorizeFolder(Folder $folder): void
    {
        abort_if(
            $folder->user_id !== Auth::id() && !$folder->is_shared,
            403, 'Akses ditolak.'
        );
    }
}
