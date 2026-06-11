@extends('layouts.admin')

@section('title', __('app.system_settings'))
@section('page-title', __('app.system_settings'))

@push('styles')
<style>
    .settings-page { max-width: 1100px; margin: 0 auto; }
    
    /* Page Header */
    .page-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
    }
    .page-header-info h4 {
        font-weight: 700; color: #0f172a; margin-bottom: 4px;
        display: flex; align-items: center; gap: 10px;
    }
    .page-header-info h4 i { color: var(--primary); }
    .page-header-info p { color: #64748b; margin: 0; font-size: 0.9rem; }
    
    /* Settings Layout */
    .settings-layout { display: grid; grid-template-columns: 240px 1fr; gap: 24px; }
    @media (max-width: 768px) { .settings-layout { grid-template-columns: 1fr; } }
    
    /* Sidebar Navigation */
    .settings-sidebar {
        background: white; border-radius: 16px; padding: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        position: sticky; top: 24px; height: fit-content;
    }
    
    .settings-nav { display: flex; flex-direction: column; gap: 4px; }
    
    .settings-nav-item {
        display: flex; align-items: center; gap: 12px; padding: 14px 16px;
        border-radius: 10px; color: #64748b; text-decoration: none;
        transition: all 0.2s ease; cursor: pointer; border: none; background: none;
        width: 100%; text-align: start;
    }
    .settings-nav-item:hover { background: #f8fafc; color: var(--primary); }
    .settings-nav-item.active { background: var(--primary); color: white; }
    .settings-nav-item i { width: 20px; text-align: center; font-size: 1rem; }
    .settings-nav-item span { font-weight: 500; font-size: 0.9rem; }
    
    /* Settings Content */
    .settings-content {
        background: white; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    
    .settings-section { display: none; }
    .settings-section.active { display: block; }
    
    .section-header {
        padding: 20px 24px; background: linear-gradient(135deg, var(--primary), var(--primary));
        color: white;
    }
    .section-header h5 {
        margin: 0; font-weight: 600; display: flex; align-items: center; gap: 10px;
    }
    .section-header p { margin: 6px 0 0; opacity: 0.85; font-size: 0.85rem; }
    
    .section-body { padding: 24px; }
    
    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 500; color: #374151; margin-bottom: 8px; font-size: 0.9rem; display: block; }
    .form-hint { font-size: 0.8rem; color: #94a3b8; margin-top: 6px; }
    
    .form-control, .form-select {
        border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 16px;
        font-size: 0.95rem; transition: all 0.2s ease; width: 100%;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-light); outline: none;
    }
    
    /* Color Picker */
    .color-picker-group { display: flex; align-items: center; gap: 12px; }
    .color-picker {
        width: 60px; height: 50px; border-radius: 10px; border: 2px solid #e2e8f0;
        cursor: pointer; padding: 4px;
    }
    .color-text { max-width: 100px; font-family: monospace; }
    
    /* Color Preview */
    .color-preview-box {
        padding: 24px; border-radius: 12px; margin-top: 16px;
        transition: all 0.3s ease;
    }
    .preview-content { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .preview-btn {
        padding: 10px 24px; border-radius: 8px; font-weight: 600;
        color: white; transition: all 0.3s ease;
    }
    .preview-text { color: white; font-size: 0.9rem; }
    
    /* Logo Upload */
    .upload-area {
        border: 2px dashed #e2e8f0; border-radius: 12px; padding: 24px;
        text-align: center; transition: all 0.2s ease; cursor: pointer;
        position: relative;
    }
    .upload-area:hover { border-color: var(--primary); background: var(--primary-light); }
    .upload-area input { position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer; }
    .upload-area i { font-size: 2rem; color: #94a3b8; margin-bottom: 8px; }
    .upload-area p { color: #64748b; margin: 0 0 4px; font-size: 0.9rem; }
    .upload-area small { color: #94a3b8; font-size: 0.8rem; }
    
    .current-logo {
        padding: 16px; background: #f8fafc; border-radius: 12px; margin-bottom: 16px;
        display: flex; align-items: center; gap: 16px;
    }
    .current-logo img { max-height: 60px; max-width: 150px; object-fit: contain; }
    .current-favicon img { width: 32px; height: 32px; }
    .remove-btn {
        display: flex; align-items: center; gap: 6px; color: #ef4444;
        font-size: 0.85rem; cursor: pointer;
    }
    .remove-btn input { cursor: pointer; }
    
    /* Switch */
    .switch-item {
        display: flex; align-items: center; gap: 12px; padding: 16px 20px;
        background: #f8fafc; border-radius: 12px;
    }
    .form-switch .form-check-input { width: 48px; height: 26px; cursor: pointer; margin: 0; }
    .form-switch .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
    .switch-info label { font-weight: 500; color: #374151; margin: 0; display: block; cursor: pointer; }
    .switch-info small { color: #94a3b8; font-size: 0.8rem; }
    
    /* Section Divider */
    .section-divider {
        display: flex; align-items: center; gap: 12px; margin: 28px 0 20px;
        color: #64748b; font-weight: 600; font-size: 0.9rem;
    }
    .section-divider::after {
        content: ''; flex: 1; height: 1px; background: #e2e8f0;
    }
    .section-divider i { color: var(--primary); }
    
    /* Save Button */
    .btn-save {
        display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px;
        background: var(--primary); border: none; border-radius: 10px;
        color: white; font-weight: 600; cursor: pointer; transition: all 0.2s ease;
    }
    .btn-save:hover { filter: brightness(1.1); transform: translateY(-2px); }
    
    /* Toast */
    .toast-notification {
        position: fixed; bottom: 24px; right: 24px; z-index: 9999;
        padding: 16px 24px; border-radius: 12px; color: white;
        font-weight: 500; display: none; animation: slideIn 0.3s ease;
    }
    .toast-notification.success { background: #10b981; }
    .toast-notification.error { background: #ef4444; }
    .toast-notification.show { display: flex; align-items: center; gap: 10px; }
    @keyframes slideIn { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
</style>
@endpush

@section('content')
<div class="settings-page">
    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-info">
            <h4><i class="fas fa-cogs"></i> {{ __('app.system_settings') }}</h4>
            <p>{{ __('app.settings_desc') }}</p>
        </div>
    </div>
    
    <div class="settings-layout">
        {{-- Sidebar --}}
        <div class="settings-sidebar">
            <nav class="settings-nav">
                <button type="button" class="settings-nav-item active" data-section="general">
                    <i class="fas fa-sliders-h"></i>
                    <span>{{ __('app.general_settings') }}</span>
                </button>
                <button type="button" class="settings-nav-item" data-section="appearance">
                    <i class="fas fa-palette"></i>
                    <span>{{ __('app.appearance') }}</span>
                </button>
                <button type="button" class="settings-nav-item" data-section="branding">
                    <i class="fas fa-image"></i>
                    <span>{{ __('app.branding') }}</span>
                </button>
            </nav>
        </div>
        
        {{-- Content --}}
        <div class="settings-content">
            {{-- General Settings --}}
            <div class="settings-section active" id="section-general">
                <div class="section-header">
                    <h5><i class="fas fa-sliders-h"></i> {{ __('app.general_settings') }}</h5>
                    <p>{{ __('app.general_settings_desc') }}</p>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.settings.general') }}" method="POST" id="general-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.site_name_ar') }}</label>
                                    <input type="text" name="site_name_ar" class="form-control" 
                                           value="{{ $general['site_name_ar'] ?? 'المنيو الرقمي' }}" 
                                           placeholder="{{ __('app.site_name_ar') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.site_name_en') }}</label>
                                    <input type="text" name="site_name" class="form-control" 
                                           value="{{ $general['site_name'] ?? 'QR Menu' }}" 
                                           placeholder="{{ __('app.site_name_en') }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.site_description_ar') }}</label>
                                    <textarea name="site_description_ar" class="form-control" rows="3" 
                                              placeholder="{{ __('app.site_description_placeholder') }}">{{ $general['site_description_ar'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.site_description_en') }}</label>
                                    <textarea name="site_description" class="form-control" rows="3" 
                                              placeholder="{{ __('app.site_description_placeholder_en') }}">{{ $general['site_description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="section-divider"><i class="fas fa-globe"></i> {{ __('app.language_settings') }}</div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.default_language') }}</label>
                                    <select name="default_language" class="form-select">
                                        <option value="ar" {{ ($general['default_language'] ?? 'ar') == 'ar' ? 'selected' : '' }}>
                                            🇸🇦 {{ __('app.arabic') }}
                                        </option>
                                        <option value="en" {{ ($general['default_language'] ?? '') == 'en' ? 'selected' : '' }}>
                                            🇺🇸 {{ __('app.english') }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.registration_settings') }}</label>
                                    <div class="switch-item">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="allow_registration" 
                                                   id="allow_registration" value="1" 
                                                   {{ ($general['allow_registration'] ?? '1') == '1' ? 'checked' : '' }}>
                                        </div>
                                        <div class="switch-info">
                                            <label for="allow_registration">{{ __('app.allow_registration') }}</label>
                                            <small>{{ __('app.allow_registration_hint') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> {{ __('app.save_settings') }}
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- Appearance Settings --}}
            <div class="settings-section" id="section-appearance">
                <div class="section-header">
                    <h5><i class="fas fa-palette"></i> {{ __('app.appearance') }}</h5>
                    <p>{{ __('app.appearance_desc') }}</p>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.settings.appearance') }}" method="POST" enctype="multipart/form-data" id="appearance-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="section-divider"><i class="fas fa-fill-drip"></i> {{ __('app.colors') }}</div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.primary_color') }}</label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 10px;">{{ __('app.primary_color_hint') }}</div>
                                    <div class="color-picker-group">
                                        <input type="color" class="color-picker" name="primary_color" id="primary_color"
                                               value="{{ $appearance['primary_color'] ?? '#c9a227' }}">
                                        <input type="text" class="form-control color-text" id="primary_color_text"
                                               value="{{ $appearance['primary_color'] ?? '#c9a227' }}" maxlength="7">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.secondary_color') }}</label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 10px;">{{ __('app.secondary_color_hint') }}</div>
                                    <div class="color-picker-group">
                                        <input type="color" class="color-picker" name="secondary_color" id="secondary_color"
                                               value="{{ $appearance['secondary_color'] ?? '#1a1a2e' }}">
                                        <input type="text" class="form-control color-text" id="secondary_color_text"
                                               value="{{ $appearance['secondary_color'] ?? '#1a1a2e' }}" maxlength="7">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">{{ __('app.preview') }}</label>
                            <div class="color-preview-box" id="color-preview-box" 
                                 style="background: {{ $appearance['secondary_color'] ?? '#1a1a2e' }};">
                                <div class="preview-content">
                                    <span class="preview-btn" id="preview-btn" 
                                          style="background: {{ $appearance['primary_color'] ?? '#c9a227' }};">
                                        {{ __('app.primary_button') }}
                                    </span>
                                    <span class="preview-text">{{ __('app.text_on_dark_bg') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> {{ __('app.save_appearance') }}
                        </button>
                    </form>
                </div>
            </div>
            
            {{-- Branding Settings --}}
            <div class="settings-section" id="section-branding">
                <div class="section-header">
                    <h5><i class="fas fa-image"></i> {{ __('app.branding') }}</h5>
                    <p>{{ __('app.branding_desc') }}</p>
                </div>
                <div class="section-body">
                    <form action="{{ route('admin.settings.appearance') }}" method="POST" enctype="multipart/form-data" id="branding-form">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.site_logo') }}</label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 12px;">{{ __('app.logo_hint') }}</div>
                                    
                                    @if($appearance['site_logo'] ?? null)
                                    <div class="current-logo">
                                        <img src="{{ asset('storage/' . $appearance['site_logo']) }}" alt="Logo">
                                        <label class="remove-btn">
                                            <input type="checkbox" name="remove_logo" value="1">
                                            <i class="fas fa-trash"></i> {{ __('app.remove') }}
                                        </label>
                                    </div>
                                    @endif
                                    
                                    <div class="upload-area">
                                        <input type="file" name="site_logo" id="logo-input" accept="image/*">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>{{ __('app.upload_logo') }}</p>
                                        <small>PNG, JPG, SVG - {{ __('app.max_size') }}: 2MB</small>
                                    </div>
                                    
                                    <div id="logo-preview" class="current-logo" style="display: none; margin-top: 12px;">
                                        <img src="" alt="Preview">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('app.favicon') }}</label>
                                    <div class="form-hint" style="margin-top: 0; margin-bottom: 12px;">{{ __('app.favicon_hint') }}</div>
                                    
                                    @if($appearance['site_favicon'] ?? null)
                                    <div class="current-logo current-favicon">
                                        <img src="{{ asset('storage/' . $appearance['site_favicon']) }}" alt="Favicon">
                                        <label class="remove-btn">
                                            <input type="checkbox" name="remove_favicon" value="1">
                                            <i class="fas fa-trash"></i> {{ __('app.remove') }}
                                        </label>
                                    </div>
                                    @endif
                                    
                                    <div class="upload-area">
                                        <input type="file" name="site_favicon" id="favicon-input" accept=".png,.ico,.jpg,.jpeg">
                                        <i class="fas fa-image"></i>
                                        <p>{{ __('app.upload_favicon') }}</p>
                                        <small>PNG, ICO - {{ __('app.favicon_size') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i> {{ __('app.save_branding') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="toast-notification" id="toast"></div>
@endsection

@push('scripts')
<script>
    // Tab Navigation
    document.querySelectorAll('.settings-nav-item').forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active nav
            document.querySelectorAll('.settings-nav-item').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show section
            const section = this.dataset.section;
            document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
            document.getElementById('section-' + section).classList.add('active');
        });
    });
    
    // Color picker sync
    ['primary', 'secondary'].forEach(type => {
        const colorInput = document.getElementById(`${type}_color`);
        const textInput = document.getElementById(`${type}_color_text`);
        
        if (colorInput && textInput) {
            colorInput.addEventListener('input', function() {
                textInput.value = this.value;
                updatePreview();
            });
            
            textInput.addEventListener('input', function() {
                if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                    colorInput.value = this.value;
                    updatePreview();
                }
            });
        }
    });
    
    function updatePreview() {
        const primary = document.getElementById('primary_color').value;
        const secondary = document.getElementById('secondary_color').value;
        const previewBox = document.getElementById('color-preview-box');
        const previewBtn = document.getElementById('preview-btn');
        
        if (previewBox) previewBox.style.background = secondary;
        if (previewBtn) previewBtn.style.background = primary;
    }
    
    // Logo preview
    document.getElementById('logo-input')?.addEventListener('change', function() {
        const preview = document.getElementById('logo-preview');
        const img = preview.querySelector('img');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'flex';
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            preview.style.display = 'none';
        }
    });
    
    // Toast notification
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
        toast.className = `toast-notification ${type} show`;
        setTimeout(() => toast.classList.remove('show'), 3000);
    }
    
    // Check for success message
    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif
</script>
@endpush