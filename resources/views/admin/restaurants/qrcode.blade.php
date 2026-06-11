@extends('layouts.admin')

@section('title', __('app.qr_code') . ' - ' . $restaurant->getName())
@section('page-title', __('app.qr_code_for_restaurant'))

@push('styles')
<style>
    .qr-page {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
        align-items: start;
    }
    
    @media (max-width: 992px) {
        .qr-page {
            grid-template-columns: 1fr;
        }
    }
    
    .qr-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9;
    }
    
    .qr-header {
        padding: 24px;
        text-align: center;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .qr-header h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    
    .qr-header h3 i {
        color: var(--primary);
    }
    
    .qr-body {
        padding: 40px;
        text-align: center;
    }
    
    .qr-container {
        display: inline-block;
        padding: 24px;
        background: white;
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        margin-bottom: 32px;
        transition: all 0.3s ease;
    }
    
    .qr-container:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 30px rgba(0,0,0,0.1);
    }
    
    .qr-container svg {
        display: block;
    }
    
    .url-section {
        margin-bottom: 32px;
    }
    
    .url-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 12px;
        font-weight: 500;
    }
    
    .url-box {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .url-box input {
        flex: 1;
        padding: 14px 16px;
        border: none;
        background: transparent;
        font-size: 0.9rem;
        color: #334155;
        text-align: center;
    }
    
    .url-box input:focus {
        outline: none;
    }
    
    .url-box .copy-btn {
        padding: 14px 18px;
        background: transparent;
        border: none;
        border-right: 1.5px solid #e2e8f0;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    [dir="ltr"] .url-box .copy-btn {
        border-right: none;
        border-left: 1.5px solid #e2e8f0;
    }
    
    .url-box .copy-btn:hover {
        background: var(--primary);
        color: white;
    }
    
    .url-box .copy-btn.copied {
        background: #10b981;
        color: white;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .qr-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .qr-btn.primary {
        background: var(--primary);
        color: white;
        border: none;
    }
    
    .qr-btn.primary:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        color: white;
    }
    
    .qr-btn.secondary {
        background: #1e293b;
        color: white;
        border: none;
    }
    
    .qr-btn.secondary:hover {
        background: #334155;
        transform: translateY(-2px);
        color: white;
    }
    
    .qr-btn.outline {
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
    }
    
    .qr-btn.outline:hover {
        background: var(--primary-light);
        transform: translateY(-2px);
    }
    
    .qr-footer {
        padding: 20px 24px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: white;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .back-btn:hover {
        border-color: var(--primary);
        color: var(--primary);
    }
    
    /* Tips Card - Sidebar */
    .tips-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        position: sticky;
        top: 100px;
    }
    
    .tips-header {
        padding: 20px;
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: #92400e;
    }
    
    .tips-header i {
        font-size: 1.1rem;
    }
    
    .tips-body {
        padding: 20px;
    }
    
    .tips-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tips-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        color: #475569;
        font-size: 0.85rem;
        line-height: 1.5;
    }
    
    .tips-list li:not(:last-child) {
        border-bottom: 1px solid #f1f5f9;
    }
    
    .tips-list li i {
        color: var(--primary);
        margin-top: 3px;
        flex-shrink: 0;
    }
    
    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: white;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 9999;
    }
    
    .toast-notification.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    
    .toast-notification i {
        color: #10b981;
    }
</style>
@endpush

@section('content')
<div class="qr-page">
    <!-- QR Card - Main -->
    <div class="qr-card">
        <div class="qr-header">
            <h3>
                <i class="fas fa-qrcode"></i>
                {{ $restaurant->getName() }}
            </h3>
        </div>
        
        <div class="qr-body">
            <!-- QR Code -->
            <div class="qr-container" id="qrContainer">
                {!! QrCode::size(250)->generate(route('menu.landing', $restaurant->slug)) !!}
            </div>
            
            <!-- URL Section -->
            <div class="url-section">
                <div class="url-label">{{ __('app.menu_link') }}</div>
                <div class="url-box">
                    <button type="button" class="copy-btn" onclick="copyUrl()" id="copyBtn">
                        <i class="fas fa-copy"></i>
                    </button>
                    <input type="text" id="menuUrl" value="{{ route('menu.landing', $restaurant->slug) }}" readonly>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('menu.landing', $restaurant->slug) }}" target="_blank" class="qr-btn primary">
                    <i class="fas fa-external-link-alt"></i>
                    {{ __('app.open_menu') }}
                </a>
                
                <button type="button" class="qr-btn secondary" onclick="downloadQR('png')">
                    <i class="fas fa-download"></i>
                    {{ __('app.download_png') }}
                </button>
                
                <button type="button" class="qr-btn outline" onclick="downloadQR('svg')">
                    <i class="fas fa-download"></i>
                    {{ __('app.download_svg') }}
                </button>
            </div>
        </div>
        
        <div class="qr-footer">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                {{ __('app.back_to_list') }}
            </a>
        </div>
    </div>
    
    <!-- Tips Card - Sidebar -->
    <div class="tips-card">
        <div class="tips-header">
            <i class="fas fa-lightbulb"></i>
            {{ __('app.qr_tips_title') }}
        </div>
        <div class="tips-body">
            <ul class="tips-list">
                <li>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('app.qr_tip_1') }}</span>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('app.qr_tip_2') }}</span>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('app.qr_tip_3') }}</span>
                </li>
                <li>
                    <i class="fas fa-check-circle"></i>
                    <span>{{ __('app.qr_tip_4') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-notification" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage">{{ __('app.link_copied') }}</span>
</div>

@push('scripts')
<script>
function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    toastMessage.textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 2500);
}

function copyUrl() {
    const url = document.getElementById('menuUrl');
    const copyBtn = document.getElementById('copyBtn');
    
    navigator.clipboard.writeText(url.value).then(() => {
        copyBtn.classList.add('copied');
        copyBtn.innerHTML = '<i class="fas fa-check"></i>';
        showToast('{{ __("app.link_copied") }}');
        
        setTimeout(() => {
            copyBtn.classList.remove('copied');
            copyBtn.innerHTML = '<i class="fas fa-copy"></i>';
        }, 2000);
    });
}

function downloadQR(format) {
    const qrContainer = document.getElementById('qrContainer');
    const svg = qrContainer.querySelector('svg');
    
    if (format === 'svg') {
        const svgData = new XMLSerializer().serializeToString(svg);
        const blob = new Blob([svgData], {type: 'image/svg+xml'});
        downloadBlob(blob, '{{ $restaurant->slug }}-qr.svg');
        showToast('{{ __("app.file_downloaded") }}');
    } else {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const svgData = new XMLSerializer().serializeToString(svg);
        const img = new Image();
        
        img.onload = function() {
            canvas.width = 500;
            canvas.height = 500;
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0, 500, 500);
            
            canvas.toBlob(function(blob) {
                downloadBlob(blob, '{{ $restaurant->slug }}-qr.png');
                showToast('{{ __("app.file_downloaded") }}');
            });
        };
        
        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    }
}

function downloadBlob(blob, filename) {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>
@endpush
@endsection