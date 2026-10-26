@extends('layouts.app')

@section('title', $isAdmin ? 'All tasks' : 'My tasks')

@section('content')
<div class="page-heading"><div><p class="eyebrow">{{ $isAdmin ? 'TEAM WORKLOAD' : 'YOUR WORKLOAD' }}</p><h1>{{ $isAdmin ? 'All tasks' : 'My tasks' }}</h1><p class="muted">{{ $isAdmin ? 'Search, review and assign work across your team.' : 'Your assignments, all in one place.' }}</p></div></div>
@if ($isAdmin)
<details class="surface create-task" {{ $errors->any() ? 'open' : '' }}><summary><span class="summary-plus">+</span> Create and assign a task</summary>
    <form method="POST" action="{{ route('tasks.store') }}" class="task-form-grid">@csrf
        <label>Task title<input name="title" required maxlength="160" value="{{ old('title') }}" placeholder="What needs to be done?"></label>
        <label>Project<select name="project_id" required><option value="">Choose project</option>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>{{ $project->name }}</option>@endforeach</select></label>
        <label>Assign to<select name="assigned_to" required><option value="">Choose employee</option>@foreach ($employees as $employee)<option value="{{ $employee->id }}" @selected(old('assigned_to') == $employee->id)>{{ $employee->name }}</option>@endforeach</select></label>
        <label>Priority<select name="priority" required>@foreach (\App\Models\Task::PRIORITIES as $priority)<option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select></label>
        <label>Status<select name="status" required>@foreach (\App\Models\Task::STATUSES as $status)<option value="{{ $status }}" @selected(old('status', 'pending') === $status)>{{ Str::headline($status) }}</option>@endforeach</select></label>
        <label>Due date<input type="date" name="due_date" value="{{ old('due_date') }}"></label>
        <label class="wide-field">Description<textarea name="description" rows="2" maxlength="5000" placeholder="Add context for the assignee">{{ old('description') }}</textarea></label>
        <div class="wide-field"><button class="button primary" type="submit">Create task <span>→</span></button></div>
    </form>
</details>
@endif
<section class="surface task-list-surface">
    @if ($isAdmin)
        <form method="GET" action="{{ route('tasks.index') }}" class="filter-bar">
            <label class="search-field"><span>⌕</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Search tasks, projects, people"></label>
            <select name="status" aria-label="Filter by status"><option value="">All statuses</option>@foreach (\App\Models\Task::STATUSES as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ Str::headline($status) }}</option>@endforeach</select>
            <select name="priority" aria-label="Filter by priority"><option value="">All priorities</option>@foreach (\App\Models\Task::PRIORITIES as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select>
            <button class="button secondary" type="submit">Filter</button>
        </form>
    @endif
    @include('tasks._table', ['tasks' => $tasks, 'isAdmin' => $isAdmin, 'compact' => false])
    @if ($tasks instanceof \Illuminate\Pagination\LengthAwarePaginator)<div class="pagination">{{ $tasks->links() }}</div>@endif
</section>
@endsection