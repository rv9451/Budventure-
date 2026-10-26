@php($columnCount = $isAdmin ? (request()->routeIs('tasks.index') ? 7 : 6) : 5)
<div class="table-wrap"><table class="task-table"><thead><tr><th>Task</th><th>Project</th>@if ($isAdmin)<th>Assignee</th>@endif<th>Priority</th><th>Status</th><th>Due date</th>@if ($isAdmin && request()->routeIs('tasks.index'))<th>Actions</th>@endif</tr></thead><tbody>
@forelse ($tasks as $task)
    <tr data-task-row="{{ $task->id }}"><td><a class="task-title" href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a>@unless ($compact)<span class="task-description">{{ Str::limit($task->description, 65) }}</span>@endunless</td>
        <td><span class="table-project">{{ $task->project->name }}</span></td>
        @if ($isAdmin)<td><span class="assignee-cell"><span class="avatar small-avatar">{{ strtoupper(substr($task->assignee->name, 0, 1)) }}</span>{{ $task->assignee->name }}</span></td>@endif
        <td><span class="priority priority-{{ $task->priority }}"><i></i>{{ ucfirst($task->priority) }}</span></td>
        <td><form class="status-form" data-status-form action="{{ route('tasks.status', $task) }}"><select name="status" data-previous-status="{{ $task->status }}" aria-label="Update status for {{ $task->title }}" class="status-select status-{{ $task->status }}">@foreach (\App\Models\Task::STATUSES as $status)<option value="{{ $status }}" @selected($task->status === $status)>{{ Str::headline($status) }}</option>@endforeach</select><span class="status-feedback visually-hidden" aria-live="polite"></span></form></td>
        <td class="due-date {{ $task->due_date && $task->due_date->isPast() && $task->status !== 'completed' ? 'overdue' : '' }}">{{ $task->due_date?->format('M j, Y') ?? 'No date' }}</td>
        @if ($isAdmin && request()->routeIs('tasks.index'))<td><div class="row-actions"><a class="table-action" href="{{ route('tasks.edit', $task) }}">Edit</a><form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?')">@csrf @method('DELETE')<button class="table-action delete-action" type="submit">Delete</button></form></div></td>@endif
    </tr>
@empty
    <tr><td colspan="{{ $columnCount }}"><div class="empty-state"><span>☷</span><strong>No tasks found</strong><p>{{ $isAdmin ? 'Create a task or adjust your filters.' : 'You do not have any tasks assigned yet.' }}</p></div></td></tr>
@endforelse
</tbody></table></div>