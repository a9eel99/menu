@extends('layouts.auth')

@section('title', __('app.reset_password'))

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="auth-icon success">
            <i class="fas fa-key"></i>
        </div>
        <h4 class="mt-3">{{ __('app.reset_password') }}</h4>
        <p class="text-muted">{{ app()->getLocale() == 'ar' ? 'أدخل كلمة المرور الجديدة' : 'Enter your new password' }}</p>
    </div>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label">{{ __('app.email') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ $email ?? old('email') }}" required readonly>
            </div>
            @error('email')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('app.password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                       required placeholder="••••••••">
                <button class="btn btn-outline-secondary toggle-password" type="button">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">{{ __('app.confirm_password') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" name="password_confirmation" class="form-control" 
                       required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">
            <i class="fas fa-save me-2"></i>
            {{ __('app.save') }}
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>
                {{ __('app.login') }}
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', function() {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
});
</script>
@endpush
@endsection