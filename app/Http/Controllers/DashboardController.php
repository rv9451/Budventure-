<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        $tasks = Task::with(['project', 'assignee'])
            ->when(! $isAdmin, fn ($query) => $query->where('assigned_to', $user->id));

        return view('dashboard', [
            'isAdmin' => $isAdmin,
            'projectCount' => $isAdmin ? Project::count() : null,
            'taskCount' => (clone $tasks)->count(),
            'openCount' => (clone $tasks)->whereIn('status', ['pending', 'in_progress'])->count(),
            'completedCount' => (clone $tasks)->where('status', 'completed')->count(),
            'recentTasks' => $tasks->latest()->limit(6)->get(),
        ]);
    }
}