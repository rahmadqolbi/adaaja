<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\WorkFile;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $stats = [
            'total'       => Task::forUser($userId)->count(),
            'pending'     => Task::forUser($userId)->byStatus('pending')->count(),
            'in_progress' => Task::forUser($userId)->byStatus('in_progress')->count(),
            'done'        => Task::forUser($userId)->byStatus('done')->count(),
            'overdue'     => Task::forUser($userId)->overdue()->count(),
            'due_today'   => Task::forUser($userId)->dueToday()->count(),
        ];

        $upcomingTasks = Task::forUser($userId)
            ->with('category', 'tags')
            ->whereBetween('deadline', [Carbon::today(), Carbon::today()->addDays(7)])
            ->where('status', '!=', 'done')
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $overdueTasks = Task::forUser($userId)
            ->with('category')
            ->overdue()
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        $recentFiles = WorkFile::forUser($userId)
            ->orderBy('upload_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('dashboard', compact(
            'stats', 'upcomingTasks', 'overdueTasks', 'recentFiles'
        ));
    }
}
