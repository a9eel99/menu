@extends('layouts.auth')

@section('title', __('app.register'))

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <div class="auth-icon success">
            <i class="fas fa-user-plus"></i>
        </div>
        <h4 class="mt-3">{{ __('app.create_account') }}</h4>
        <p class="text-muted">{{ __('app.register_subtitle') }}</p>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle me-2"></i>
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">{{ __('app.name') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required placeholder="{{ __('app.name') }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('app.email') }}</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" required placeholder="example@email.com">
            </div>
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
            <small class="text-muted">{{ __('app.min_password') }}</small>
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
            <i class="fas fa-rocket me-2"></i>
            {{ __('app.create_account') }}
        </button>

        <div class="text-center">
            <span class="text-muted">{{ __('app.already_have_account') }}</span>
            <a href="{{ route('login') }}" class="text-decoration-none me-1">{{ __('app.login') }}</a>
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