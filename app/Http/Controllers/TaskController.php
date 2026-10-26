<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $isAdmin = $request->user()->hasRole('admin');
        $tasks = Task::with(['project', 'assignee'])->latest();

        if (! $isAdmin) {
            $tasks->where('assigned_to', $request->user()->id);
        } else {
            $tasks->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim()->toString();
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhereHas('project', fn ($project) => $project->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('assignee', fn ($assignee) => $assignee->where('name', 'like', "%{$search}%"));
                });
            });
            $tasks->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()));
            $tasks->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->string('priority')->toString()));
        }

        return view('tasks.index', [
            'tasks' => $tasks->paginate(12)->withQueryString(),
            'isAdmin' => $isAdmin,
            'projects' => $isAdmin ? Project::orderBy('name')->get() : collect(),
            'employees' => $isAdmin ? User::role('employee')->orderBy('name')->get() : collect(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $request->user()->createdTasks()->create($request->validated());

        return back()->with('success', 'Task created and assigned.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'projects' => Project::orderBy('name')->get(),
            'employees' => User::role('employee')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function show(Request $request, Task $task): View
    {
        abort_unless($request->user()->hasRole('admin') || $task->assigned_to === $request->user()->id, 404);

        return view('tasks.show', ['task' => $task->load(['project', 'assignee', 'creator'])]);
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse|JsonResponse
    {
        abort_unless($request->user()->hasRole('admin') || $task->assigned_to === $request->user()->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
        ]);

        $task->update(['status' => $validated['status']]);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Task status updated.', 'status' => $task->status]);
        }

        return back()->with('success', 'Task status updated.');
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $tasks = Task::with(['project:id,name', 'assignee:id,name'])->latest();

        if (! $request->user()->hasRole('admin')) {
            $tasks->where('assigned_to', $request->user()->id);
        }

        return response()->json($tasks->paginate(20));
    }

    public function apiShow(Request $request, Task $task): JsonResponse
    {
        abort_unless($request->user()->hasRole('admin') || $task->assigned_to === $request->user()->id, 404);

        return response()->json($task->load(['project:id,name', 'assignee:id,name']));
    }
}