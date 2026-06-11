@extends('layouts.auth')

@section('title', __('app.forgot_password'))

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="auth-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h4 class="mt-3">{{ __('app.forgot_password') }}</h4>
        <p class="text-muted">{{ app()->getLocale() == 'ar' ? 'أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين' : 'Enter your email and we will send you a reset link' }}</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">{{ __('app.email') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required autofocus placeholder="example@email.com">
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-paper-plane me-2"></i>
            {{ __('app.send_reset_link') }}
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                {{ __('app.login') }}
            </a>
        </div>
    </form>
</div>
@endsection