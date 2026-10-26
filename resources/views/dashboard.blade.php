@extends('layouts.app')

@section('title', 'Overview')

@section('content')
<div class="page-heading">
    <div><p class="eyebrow">{{ $isAdmin ? 'WORKSPACE OVERVIEW' : 'YOUR WORKSPACE' }}</p><h1>{{ $isAdmin ? 'Good to see you' : 'Your day at a glance' }}, {{ strtok(auth()->user()->name, ' ') }}.</h1><p class="muted">{{ $isAdmin ? 'Here is what is happening across your team.' : 'Keep your momentum going, one task at a time.' }}</p></div>
    <a class="button primary" href="{{ route('tasks.index') }}">{{ $isAdmin ? 'Manage tasks' : 'View my tasks' }} <span>→</span></a>
</div>
<section class="stats-grid">
    @if ($isAdmin)<article class="stat"><span class="stat-icon green">▦</span><span class="stat-label">Active projects</span><strong>{{ $projectCount }}</strong><small>Across your workspace</small></article>@endif
    <article class="stat"><span class="stat-icon yellow">☷</span><span class="stat-label">{{ $isAdmin ? 'Total tasks' : 'Assigned to you' }}</span><strong>{{ $taskCount }}</strong><small>{{ $isAdmin ? 'All project tasks' : 'Your task list' }}</small></article>
    <article class="stat"><span class="stat-icon coral">◷</span><span class="stat-label">Open tasks</span><strong>{{ $openCount }}</strong><small>Pending or underway</small></article>
    <article class="stat"><span class="stat-icon blue">✓</span><span class="stat-label">Completed</span><strong>{{ $completedCount }}</strong><small>Tasks finished</small></article>
</section>
<section class="section-block">
    <div class="section-heading"><div><p class="eyebrow">LATEST ACTIVITY</p><h2>{{ $isAdmin ? 'Recently updated tasks' : 'Your recent tasks' }}</h2></div><a class="text-link" href="{{ route('tasks.index') }}">See all tasks <span>→</span></a></div>
    @include('tasks._table', ['tasks' => $recentTasks, 'isAdmin' => $isAdmin, 'compact' => true])
</section>
@endsection