@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="page-heading"><div><p class="eyebrow">WORKSPACE</p><h1>Projects</h1><p class="muted">Keep related work organized in one place.</p></div></div>
<div class="content-columns">
    <section class="surface"><div class="section-heading"><div><p class="eyebrow">START SOMETHING</p><h2>New project</h2></div></div>
        <form method="POST" action="{{ route('projects.store') }}" class="form-stack">@csrf
            <label>Project name<input name="name" maxlength="120" required value="{{ old('name') }}" placeholder="e.g. Website refresh"></label>
            <label>Description<textarea name="description" rows="4" maxlength="2000" placeholder="What is this project about?">{{ old('description') }}</textarea></label>
            <button class="button primary" type="submit">Create project <span>+</span></button>
        </form>
    </section>
    <section class="surface"><div class="section-heading"><div><p class="eyebrow">PORTFOLIO</p><h2>All projects <span class="count">{{ $projects->count() }}</span></h2></div></div>
        @forelse ($projects as $project)
            <article class="project-row"><span class="project-symbol">▦</span><div class="project-details"><strong>{{ $project->name }}</strong><p>{{ $project->description ?: 'No description provided.' }}</p></div><span class="project-task-count">{{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}</span><div class="project-actions"><a class="table-action" href="{{ route('projects.edit', $project) }}">Edit</a><form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project and all of its tasks?')">@csrf @method('DELETE')<button class="table-action delete-action" type="submit">Delete</button></form></div></article>
        @empty
            <div class="empty-state"><span>▦</span><strong>No projects yet</strong><p>Create a project to start organizing team tasks.</p></div>
        @endforelse
    </section>
</div>
@endsection