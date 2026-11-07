<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $query = Task::forUser($userId)->with('category', 'tags');

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('tag_id')) {
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $request->tag_id));
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks      = $query->orderByRaw("FIELD(priority, 'high','medium','low')")
                            ->orderBy('deadline')
                            ->paginate(15)
                            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'categories', 'tags'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags       = Tag::orderBy('name')->get();
        return view('tasks.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'category_id' => 'nullable|exists:categories,id',
            'deadline'    => 'nullable|date',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]);

        $task = Task::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        if (!empty($validated['tags'])) {
            $task->tags()->sync($validated['tags']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);
        $categories   = Category::orderBy('name')->get();
        $tags         = Tag::orderBy('name')->get();
        $selectedTags = $task->tags->pluck('id')->toArray();
        return view('tasks.edit', compact('task', 'categories', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'category_id' => 'nullable|exists:categories,id',
            'deadline'    => 'nullable|date',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]);

        $task->update($validated);
        $task->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    public function destroy(Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dihapus.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        abort_if($task->user_id !== Auth::id(), 403);
        $request->validate(['status' => 'required|in:pending,in_progress,done']);
        $task->update(['status' => $request->status]);
        return back()->with('success', 'Status tugas diperbarui.');
    }
}
