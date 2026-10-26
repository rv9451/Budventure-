@extends('layouts.app')

@section('title', 'Edit project')

@section('content')
<a class="back-link" href="{{ route('projects.index') }}">← Back to projects</a>
<div class="page-heading"><div><p class="eyebrow">PROJECT SETTINGS</p><h1>Edit project</h1><p class="muted">Update the project name or description.</p></div></div>
<section class="surface edit-form-surface">
    <form method="POST" action="{{ route('projects.update', $project) }}" class="form-stack">
        @csrf
        @method('PUT')
        <label>Project name<input name="name" maxlength="120" required value="{{ old('name', $project->name) }}"></label>
        @error('name')<span class="field-error">{{ $message }}</span>@enderror
        <label>Description<textarea name="description" rows="5" maxlength="2000">{{ old('description', $project->description) }}</textarea></label>
        @error('description')<span class="field-error">{{ $message }}</span>@enderror
        <div class="form-actions"><a class="button secondary" href="{{ route('projects.index') }}">Cancel</a><button class="button primary" type="submit">Save project</button></div>
    </form>
</section>
@endsection