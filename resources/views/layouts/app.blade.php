<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Taskflow') · Taskflow</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @yield('head')
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="{{ asset('css/app-fallback.css') }}" rel="stylesheet">
    @endif
</head>
<body>
    @auth
        <div class="shell">
            <aside class="sidebar">
                <a class="brand" href="{{ route('dashboard') }}"><span class="brand-mark">T</span> taskflow</a>
                <div class="nav-label">Workspace</div>
                <nav class="nav-links" aria-label="Main navigation">
                    <a href="{{ route('dashboard') }}" @if (request()->routeIs('dashboard')) aria-current="page" @endif class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><span aria-hidden="true">◫</span> Overview</a>
                    <a href="{{ route('tasks.index') }}" @if (request()->routeIs('tasks.*')) aria-current="page" @endif class="{{ request()->routeIs('tasks.*') ? 'active' : '' }}"><span aria-hidden="true">☷</span> {{ auth()->user()->hasRole('admin') ? 'All tasks' : 'My tasks' }}</a>
                    @role('admin')
                        <a href="{{ route('projects.index') }}" @if (request()->routeIs('projects.*')) aria-current="page" @endif class="{{ request()->routeIs('projects.*') ? 'active' : '' }}"><span aria-hidden="true">▦</span> Projects</a>
                    @endrole
                </nav>
                <div class="sidebar-bottom">
                    <div class="profile"><span class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->getRoleNames()->first() }}</small></div></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout" type="submit">↗ <span>Sign out</span></button></form>
                </div>
            </aside>
            <main class="main-content">
                <header class="topbar"><span class="topbar-date">{{ now()->format('l, F j, Y') }}</span><div class="account-chip"><span class="avatar topbar-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small class="topbar-role">{{ auth()->user()->hasRole('admin') ? 'ADMIN' : 'EMPLOYEE' }}</small></span></div></header>
                @if (session('success')) <div class="flash success">{{ session('success') }}</div> @endif
                @if ($errors->any()) <div class="flash error">{{ $errors->first() }}</div> @endif
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
    @auth
        <script>
            document.addEventListener('change', handleTaskStatusChange);

            async function handleTaskStatusChange(event) {
                const statusSelect = event.target.closest('[data-status-form] select[name="status"]');

                if (!statusSelect) return;

                const form = statusSelect.form;
                const previousStatus = statusSelect.dataset.previousStatus;
                const feedback = form.querySelector('.status-feedback');
                statusSelect.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'PATCH',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ status: statusSelect.value }),
                    });
                    const result = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        throw new Error(result.message || 'Could not update task status.');
                    }

                    statusSelect.classList.remove(`status-${previousStatus}`);
                    statusSelect.classList.add(`status-${result.status}`);
                    statusSelect.dataset.previousStatus = result.status;
                    if (feedback) feedback.textContent = 'Task status saved.';

                    const currentStatus = form.closest('.detail-status')?.querySelector('.status-current');
                    if (currentStatus) {
                        currentStatus.textContent = result.status
                            .split('_')
                            .map((word) => word[0].toUpperCase() + word.slice(1))
                            .join(' ');
                    }
                } catch (error) {
                    statusSelect.value = previousStatus;
                    if (feedback) feedback.textContent = error.message;
                } finally {
                    statusSelect.disabled = false;
                }
            }
        </script>
    @endauth
</body>
</html>