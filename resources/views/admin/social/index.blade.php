@extends('layouts.admin')

@section('title', __('app.social_media'))
@section('page-title', __('app.social_media'))

@section('content')
<div class="row">
    <!-- Add New Link -->
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus me-2"></i>
                {{ __('app.add_social_link') }}
            </div>
            <div class="card-body">
                <form action="{{ route('admin.social.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.platform') }}</label>
                        <select name="platform" class="form-select" required>
                            @foreach($platforms as $platform)
                            <option value="{{ $platform }}">
                                {{ ucfirst($platform) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('app.url_or_username') }}</label>
                        <input type="text" name="url" class="form-control" placeholder="{{ __('app.url_placeholder') }}" required>
                        <small class="text-muted">{{ __('app.social_hint') }}</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-plus me-2"></i>
                        {{ __('app.add') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Existing Links -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-share-alt me-2"></i>
                {{ __('app.your_social_links') }}
            </div>
            <div class="card-body">
                @if($socialLinks->count() > 0)
                <div class="list-group">
                    @foreach($socialLinks as $link)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="social-icon rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background-color: {{ $link->getColor() }}20;">
                                <i class="{{ $link->getIconClass() }}" style="color: {{ $link->getColor() }}; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <strong>{{ ucfirst($link->platform) }}</strong>
                                <br>
                                <small class="text-muted text-break" style="max-width: 200px; display: inline-block;">{{ Str::limit($link->url, 40) }}</small>
                            </div>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.social.toggle', $link) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $link->is_active ? 'success' : 'secondary' }}" title="{{ $link->is_active ? __('app.active') : __('app.inactive') }}">
                                    <i class="fas fa-{{ $link->is_active ? 'eye' : 'eye-slash' }}"></i>
                                </button>
                            </form>
                            
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $link->id }}" title="{{ __('app.edit') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            
                            <form action="{{ route('admin.social.destroy', $link) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="{{ __('app.delete') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $link->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.social.update', $link) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">{{ __('app.edit') }} {{ ucfirst($link->platform) }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('app.url_or_username') }}</label>
                                            <input type="text" name="url" class="form-control" value="{{ $link->url }}" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                                        <button type="submit" class="btn btn-primary">{{ __('app.save') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-share-alt fa-3x text-muted mb-3"></i>
                    <h6>{{ __('app.no_social_links') }}</h6>
                    <p class="text-muted small">{{ __('app.no_social_links_hint') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection