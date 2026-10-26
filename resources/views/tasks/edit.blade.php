@extends('layouts.app')

@section('title', 'Edit task')

@section('content')
<a class="back-link" href="{{ route('tasks.show', $task) }}">← Back to task</a>
<div class="page-heading"><div><p class="eyebrow">TASK SETTINGS</p><h1>Edit task</h1><p class="muted">Update task details, assignment, or schedule.</p></div></div>
<section class="surface edit-form-surface">
    <form method="POST" action="{{ route('tasks.update', $task) }}" class="task-form-grid">
        @csrf
        @method('PUT')
        <label>Task title<input name="title" required maxlength="160" value="{{ old('title', $task->title) }}"></label>
        <label>Project<select name="project_id" required>@foreach ($projects as $project)<option value="{{ $project->id }}" @selected(old('project_id', $task->project_id) == $project->id)>{{ $project->name }}</option>@endforeach</select></label>
        <label>Assign to<select name="assigned_to" required>@foreach ($employees as $employee)<option value="{{ $employee->id }}" @selected(old('assigned_to', $task->assigned_to) == $employee->id)>{{ $employee->name }}</option>@endforeach</select></label>
        <label>Priority<select name="priority" required>@foreach (\App\Models\Task::PRIORITIES as $priority)<option value="{{ $priority }}" @selected(old('priority', $task->priority) === $priority)>{{ ucfirst($priority) }}</option>@endforeach</select></label>
        <label>Status<select name="status" required>@foreach (\App\Models\Task::STATUSES as $status)<option value="{{ $status }}" @selected(old('status', $task->status) === $status)>{{ Str::headline($status) }}</option>@endforeach</select></label>
        <label>Due date<input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"></label>
        <label class="wide-field">Description<textarea name="description" rows="4" maxlength="5000">{{ old('description', $task->description) }}</textarea></label>
        <div class="wide-field form-actions"><a class="button secondary" href="{{ route('tasks.show', $task) }}">Cancel</a><button class="button primary" type="submit">Save task</button></div>
    </form>
    @if ($errors->any())<div class="flash error">{{ $errors->first() }}</div>@endif
</section>
@endsection