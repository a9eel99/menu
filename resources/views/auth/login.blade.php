@extends('layouts.auth')

@section('title', __('app.login'))

@section('content')
{{-- Logo --}}
@php
    $siteLogo = \App\Models\Setting::get('site_logo');
@endphp

<div class="auth-logo">
    @if($siteLogo)
        <img src="{{ asset('storage/' . $siteLogo) }}" alt="Logo">
    @else
        <svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="50" height="50" rx="12" fill="#0d9488"/>
            <path d="M15 15h8v8h-8zM27 15h8v8h-8zM15 27h8v8h-8zM27 27h5v5h-5z" fill="white"/>
        </svg>
    @endif
</div>

<h1 class="auth-title">{{ __('app.welcome_back') }}</h1>
<p class="auth-subtitle">{{ __('app.login_subtitle') }}</p>

@if (session('status'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        @foreach($errors->all() as $error)
            {{ $error }}
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="form-group">
        <label class="form-label">{{ __('app.email') }}</label>
        <div class="input-icon-wrapper">
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   value="{{ old('email') }}" 
                   placeholder="{{ __('app.email_placeholder') }}"
                   required 
                   autofocus>
            <span class="input-icon">
                <i class="fas fa-envelope"></i>
            </span>
        </div>
    </div>
    
    <div class="form-group">
        <label class="form-label">{{ __('app.password') }}</label>
        <div class="input-icon-wrapper">
            <input type="password" 
                   name="password" 
                   id="password"
                   class="form-control" 
                   placeholder="••••••••"
                   required>
            <button type="button" class="password-toggle" onclick="togglePassword()">
                <i class="fas fa-eye" id="toggleIcon"></i>
            </button>
        </div>
    </div>
    
    <div class="form-row">
        <div class="form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">{{ __('app.remember_me') }}</label>
        </div>
    </div>
    
    <button type="submit" class="btn-primary">
        {{ __('app.login') }}
    </button>
</form>

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection