@extends('layouts.app')

@section('title', 'Sign in')

@section('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')
<style>
    .auth-experience {
        --auth-ink: #1d2d23;
        --auth-muted: #728076;
        --auth-green: #1d563a;
        display: grid;
        grid-template-columns: minmax(0, 1.02fr) minmax(0, .98fr);
        min-height: 100vh;
        min-height: 100svh;
        background: #fff;
        color: var(--auth-ink);
        font-family: 'DM Sans', system-ui, sans-serif;
    }
    .auth-experience > .login-panel,
    .auth-experience > .auth-visual { width: auto; min-width: 0; }
    .auth-experience > .login-panel {
        display: flex;
        justify-content: center;
        min-height: 100%;
        margin: 0;
        padding: 56px clamp(26px, 7vw, 104px);
    }
    .auth-content { width: min(100%, 410px); margin: auto; }
    .auth-brand {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 68px;
        color: #233b2b;
        font-family: 'Space Grotesk', 'DM Sans', sans-serif;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none;
    }
    .auth-brand-mark {
        display: grid;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 9px;
        background: #e9c75e;
        color: #183d2c;
        font-size: 16px;
    }
    .auth-eyebrow {
        margin: 0 0 12px;
        color: #6f8875;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.5px;
    }
    .auth-title {
        max-width: 390px;
        margin: 0 0 12px;
        color: var(--auth-ink);
        font-family: 'Space Grotesk', 'DM Sans', sans-serif;
        font-size: clamp(34px, 4vw, 48px);
        font-weight: 600;
        letter-spacing: -1.5px;
        line-height: 1.08;
    }
    .auth-title span { color: #a14d36; }
    .auth-intro { margin: 0 0 30px; color: var(--auth-muted); font-size: 14px; line-height: 1.65; }
    .auth-form { display: grid; gap: 18px; }
    .auth-field { display: grid; gap: 7px; color: #3c4e41; font-size: 12px; font-weight: 600; }
    .auth-experience .form-control {
        min-height: 47px;
        padding: 11px 13px;
        border: 1px solid #dfe5de;
        border-radius: 6px;
        color: #29372d;
        font-size: 13px;
        box-shadow: none;
    }
    .auth-experience .form-control::placeholder { color: #a4aca5; }
    .auth-experience .form-control:focus { border-color: #689177; box-shadow: 0 0 0 3px rgb(45 105 67 / 12%); }
    .auth-error { margin-top: -13px; color: #ad5038; font-size: 11px; }
    .auth-remember { display: flex; align-items: center; gap: 9px; color: #65736a; font-size: 12px; }
    .auth-remember .form-check-input { width: 16px; height: 16px; margin: 0; border-color: #c9d2ca; box-shadow: none; }
    .auth-remember .form-check-input:checked { border-color: var(--auth-green); background-color: var(--auth-green); }
    .auth-submit {
        display: flex;
        min-height: 48px;
        align-items: center;
        justify-content: center;
        gap: 12px;
        border: 1px solid var(--auth-green);
        border-radius: 6px;
        background: var(--auth-green);
        color: white;
        font-size: 13px;
        font-weight: 600;
        transition: background .18s ease, transform .18s ease;
    }
    .auth-submit:hover { transform: translateY(-1px); border-color: #16472f; background: #16472f; color: white; }
    .auth-submit:focus-visible { outline: 3px solid #e7c85f; outline-offset: 3px; }
    .auth-footnote { margin: 23px 0 0; color: #929d94; font-size: 11px; text-align: center; }
    .auth-visual {
        position: relative;
        display: flex;
        min-height: 100%;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
        padding: clamp(34px, 5vw, 66px);
        background-color: #173d2d;
        background-image: linear-gradient(90deg, rgb(255 255 255 / 3%) 1px, transparent 1px), linear-gradient(rgb(255 255 255 / 3%) 1px, transparent 1px);
        background-size: 48px 48px;
        color: #f4f4e9;
    }
    .auth-visual::after { position: absolute; right: -140px; bottom: -210px; width: 470px; height: 470px; border: 1px solid rgb(232 199 94 / 25%); border-radius: 50%; content: ''; }
    .auth-visual-top, .auth-visual-bottom, .auth-visual-copy, .task-preview { position: relative; z-index: 1; }
    .auth-visual-top { display: flex; align-items: center; justify-content: space-between; gap: 14px; color: #c2d0c4; font-size: 10px; font-weight: 700; letter-spacing: 1.4px; }
    .auth-live { display: inline-flex; align-items: center; gap: 7px; color: #d8e3d6; font-size: 10px; letter-spacing: .3px; }
    .auth-live::before { width: 7px; height: 7px; border-radius: 50%; background: #e8c65b; content: ''; }
    .auth-visual-copy { max-width: 500px; margin: auto 0 28px; padding-top: 70px; }
    .auth-visual-copy h2 { margin: 0 0 13px; color: #f6f3e8; font-family: 'Space Grotesk', 'DM Sans', sans-serif; font-size: clamp(33px, 4vw, 54px); font-weight: 500; letter-spacing: -1.3px; line-height: 1.08; }
    .auth-visual-copy h2 span { color: #e9c85d; }
    .auth-visual-copy p { max-width: 360px; margin: 0; color: #bdcbbf; font-size: 13px; line-height: 1.7; }
    .task-preview { width: min(100%, 460px); margin: 0 0 33px; padding: 17px 19px 10px; border: 1px solid rgb(233 242 232 / 16%); border-radius: 8px; background: rgb(9 34 22 / 32%); backdrop-filter: blur(4px); }
    .task-preview-head { display: flex; justify-content: space-between; padding-bottom: 11px; border-bottom: 1px solid rgb(233 242 232 / 13%); color: #c0cec1; font-size: 9px; font-weight: 700; letter-spacing: 1px; }
    .preview-task { display: flex; align-items: center; gap: 11px; padding: 12px 0; border-bottom: 1px solid rgb(233 242 232 / 10%); color: #ecf1e8; font-size: 11px; }
    .preview-task:last-child { border-bottom: 0; }
    .preview-check { display: grid; width: 17px; height: 17px; flex: none; place-items: center; border: 1px solid #76917d; border-radius: 50%; color: #e9c85d; font-size: 10px; }
    .preview-task.is-done { color: #a8b9ab; text-decoration: line-through; }
    .auth-visual-bottom { color: #aebfb1; font-size: 9px; font-weight: 600; letter-spacing: 1.1px; }
    @media (max-width: 991.98px) {
        .auth-experience { grid-template-columns: minmax(0, 1fr) minmax(0, .9fr); }
        .auth-experience > .login-panel { padding: 42px 34px; }
        .auth-brand { margin-bottom: 48px; }
        .auth-visual { padding: 34px; }
    }
    @media (max-width: 767.98px) {
        .auth-experience { grid-template-columns: 1fr; }
        .auth-experience > .login-panel { min-height: auto; padding: 35px 24px 42px; }
        .auth-content { max-width: 440px; }
        .auth-brand { margin-bottom: 42px; }
        .auth-title { font-size: 38px; }
        .auth-visual { min-height: 430px; padding: 30px 24px; }
        .auth-visual-copy { padding-top: 56px; }
        .auth-visual-copy h2 { font-size: 39px; }
        .task-preview { margin-bottom: 25px; }
    }
    @media (max-width: 420px) {
        .auth-experience > .login-panel { padding-right: 20px; padding-left: 20px; }
        .auth-brand { margin-bottom: 34px; }
        .auth-title { font-size: 34px; }
        .auth-visual { min-height: 400px; }
    }
</style>
<main class="login-page auth-experience container-fluid px-0">
    <section class="login-panel col-lg-6 d-flex align-items-center justify-content-center">
        <div class="auth-content">
            <a class="auth-brand" href="{{ route('login') }}"><span class="auth-brand-mark">T</span> taskflow</a>
            <p class="auth-eyebrow">YOUR TEAM, IN SYNC</p>
            <h1 class="auth-title">Good work starts with <span>clear direction.</span></h1>
            <p class="auth-intro">Sign in to your workspace and pick up where you left off.</p>
            <form method="POST" action="{{ route('login.store') }}" class="auth-form">
                @csrf
                <div>
                    <label class="auth-field" for="email">Email address
                        <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@company.com" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </label>
                    @error('email') <div class="auth-error" id="email-error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="auth-field" for="password">Password
                        <input class="form-control" id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                    </label>
                    @error('password') <div class="auth-error" id="password-error">{{ $message }}</div> @enderror
                </div>
                <label class="auth-remember form-check">
                    <input class="form-check-input" type="checkbox" name="remember" value="1" id="remember">
                    <span>Keep me signed in</span>
                </label>
                <button class="auth-submit btn btn-primary w-100" type="submit">Sign in <span aria-hidden="true">→</span></button>
            </form>
            <p class="auth-footnote">Access is managed by your workspace administrator.</p>
        </div>
    </section>
    <aside class="login-art auth-visual col-lg-6" aria-label="Taskflow workspace preview">
        <div class="auth-visual-top"><span>TASKFLOW / WORKSPACE</span><span class="auth-live">TEAM IN SYNC</span></div>
        <div class="auth-visual-copy"><h2>Make the next step <span>obvious.</span></h2><p>Bring projects, priorities, and people into one clear view.</p></div>
        <div class="task-preview" aria-label="Sample task list">
            <div class="task-preview-head"><span>THIS WEEK</span><span>03 TASKS</span></div>
            <div class="preview-task is-done"><span class="preview-check">✓</span><span>Map the project milestones</span></div>
            <div class="preview-task"><span class="preview-check"></span><span>Review the homepage direction</span></div>
            <div class="preview-task"><span class="preview-check"></span><span>Share the next update</span></div>
        </div>
        <div class="auth-visual-bottom">PROJECTS <span aria-hidden="true">·</span> TASKS <span aria-hidden="true">·</span> PEOPLE</div>
    </aside>
</main>
@endsection