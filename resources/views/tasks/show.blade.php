@extends('layouts.app')

@section('title', $task->title)

@section('content')
<a class="back-link" href="{{ route('tasks.index') }}">← Back to tasks</a>
<section class="surface task-detail">
    <div class="detail-topline"><span class="eyebrow">{{ $task->project->name }}</span><span class="priority priority-{{ $task->priority }}"><i></i>{{ ucfirst($task->priority) }} priority</span></div>
    <h1>{{ $task->title }}</h1>
    <p class="detail-description">{{ $task->description ?: 'No description provided.' }}</p>
    <div class="detail-grid">
        <div><span class="detail-label">Assigned to</span><strong>{{ $task->assignee->name }}</strong></div>
        <div><span class="detail-label">Due date</span><strong>{{ $task->due_date?->format('F j, Y') ?? 'Not set' }}</strong></div>
        <div><span class="detail-label">Created by</span><strong>{{ $task->creator?->name ?? 'Workspace admin' }}</strong></div>
        <div><span class="detail-label">Last updated</span><strong>{{ $task->updated_at->diffForHumans() }}</strong></div>
    </div>
    <div class="detail-status"><div><span class="detail-label">Current status</span><strong class="status-current">{{ Str::headline($task->status) }}</strong></div>
        <form class="status-form detail-status-form" data-status-form action="{{ route('tasks.status', $task) }}"><label for="task-status">Update status</label><select id="task-status" name="status" data-previous-status="{{ $task->status }}" class="status-select status-{{ $task->status }}">@foreach (\App\Models\Task::STATUSES as $status)<option value="{{ $status }}" @selected($task->status === $status)>{{ Str::headline($status) }}</option>@endforeach</select><span class="status-feedback" aria-live="polite"></span></form>
    </div>
</section>
@endsection