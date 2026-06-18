@extends('layouts.admin')

@section('title', __('app.qr_code') . ' - ' . $restaurant->getName())
@section('page-title', __('app.qr_code_for_restaurant'))

@push('styles')
<style>
    .qr-page {
        max-width: 600px;
        margin: 0 auto;
    }

    .qr-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f1f5f9;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }
    .card-header i { color: var(--primary); font-size: 1.2rem; }

    .qr-preview {
        padding: 40px;
        text-align: center;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    .qr-frame {
        display: inline-block;
        padding: 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    #qrCanvas {
        display: block;
        border-radius: 8px;
    }

    .url-section {
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
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
        padding: 12px 16px;
        border: none;
        background: transparent;
        font-size: 0.85rem;
        color: #334155;
    }
    .url-box input:focus { outline: none; }
    .url-box .copy-btn {
        padding: 12px 16px;
        background: var(--primary);
        border: none;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
    }
    .url-box .copy-btn:hover { filter: brightness(1.1); }

    .download-section {
        padding: 20px 24px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .download-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.9rem;
    }
    .download-btn.primary { background: var(--primary); color: white; }
    .download-btn.secondary { background: #1e293b; color: white; }
    .download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .back-section {
        padding: 20px 24px;
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
        transition: all 0.2s;
    }
    .back-btn:hover { border-color: var(--primary); color: var(--primary); }

    .toast-notification {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0f172a;
        color: white;
        padding: 14px 24px;
        border-radius: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
        opacity: 0;
        transition: all 0.3s;
        z-index: 9999;
    }
    .toast-notification.show {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }
    .toast-notification i { color: #10b981; }
</style>
@endpush

@section('content')
<div class="qr-page">
    <div class="qr-card">
        <div class="card-header">
            <i class="fas fa-qrcode"></i>
            <h3>{{ $restaurant->getName() }}</h3>
        </div>

        <div class="qr-preview">
            <div class="qr-frame">
                <canvas id="qrCanvas"></canvas>
            </div>
        </div>

        <div class="url-section">
            <div class="url-box">
                <input type="text" id="menuUrl" value="{{ route('menu.landing', $restaurant->slug) }}" readonly>
                <button type="button" class="copy-btn" onclick="copyUrl()">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <div class="download-section" style="grid-template-columns: repeat(3, 1fr);">
            <button type="button" class="download-btn primary" onclick="downloadQR('png')">
                <i class="fas fa-image"></i>
                PNG
            </button>
            <button type="button" class="download-btn secondary" onclick="downloadQR('svg')">
                <i class="fas fa-file-code"></i>
                SVG
            </button>
            <button type="button" class="download-btn" style="background: #dc2626;" onclick="downloadQR('pdf')">
                <i class="fas fa-file-pdf"></i>
                PDF
            </button>
        </div>

        <div class="back-section">
            <a href="{{ route('admin.restaurants.show', $restaurant) }}" class="back-btn">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                {{ __('app.back') }}
            </a>
        </div>
    </div>
</div>

<div class="toast-notification" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const menuUrl = '{{ route('menu.landing', $restaurant->slug) }}';
const restaurantName = @json($restaurant->getName());
const qrSize = 250;
const qrColor = '#000000';

function generateQR() {
    const canvas = document.getElementById('qrCanvas');
    const ctx = canvas.getContext('2d');

    const qr = qrcode(0, 'M');
    qr.addData(menuUrl);
    qr.make();

    const moduleCount = qr.getModuleCount();
    const cellSize = qrSize / moduleCount;

    canvas.width = qrSize;
    canvas.height = qrSize;

    // Background
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, qrSize, qrSize);

    // Draw QR modules
    ctx.fillStyle = qrColor;
    for (let row = 0; row < moduleCount; row++) {
        for (let col = 0; col < moduleCount; col++) {
            if (qr.isDark(row, col)) {
                ctx.fillRect(col * cellSize, row * cellSize, cellSize, cellSize);
            }
        }
    }
}

function copyUrl() {
    navigator.clipboard.writeText(menuUrl).then(() => {
        showToast('{{ __("app.link_copied") }}');
    });
}

function downloadQR(format) {
    const canvas = document.getElementById('qrCanvas');

    if (format === 'png') {
        const link = document.createElement('a');
        link.download = '{{ $restaurant->slug }}-qr.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
        showToast('{{ __("app.file_downloaded") }}');
    } else if (format === 'svg') {
        const svgNS = "http://www.w3.org/2000/svg";
        const svg = document.createElementNS(svgNS, "svg");
        svg.setAttribute("width", qrSize);
        svg.setAttribute("height", qrSize);
        svg.setAttribute("viewBox", `0 0 ${qrSize} ${qrSize}`);

        const bg = document.createElementNS(svgNS, "rect");
        bg.setAttribute("width", qrSize);
        bg.setAttribute("height", qrSize);
        bg.setAttribute("fill", "#ffffff");
        svg.appendChild(bg);

        const img = document.createElementNS(svgNS, "image");
        img.setAttribute("href", canvas.toDataURL());
        img.setAttribute("width", qrSize);
        img.setAttribute("height", qrSize);
        svg.appendChild(img);

        const svgData = new XMLSerializer().serializeToString(svg);
        const blob = new Blob([svgData], {type: 'image/svg+xml'});
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.download = '{{ $restaurant->slug }}-qr.svg';
        link.href = url;
        link.click();
        URL.revokeObjectURL(url);
        showToast('{{ __("app.file_downloaded") }}');
    } else if (format === 'pdf') {
        // Create PDF with QR code only
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        const imgData = canvas.toDataURL('image/png');
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        const qrSizeMM = 100;
        const x = (pageWidth - qrSizeMM) / 2;
        const y = (pageHeight - qrSizeMM) / 2;

        // Add QR code centered
        pdf.addImage(imgData, 'PNG', x, y, qrSizeMM, qrSizeMM);

        pdf.save('{{ $restaurant->slug }}-qr.pdf');
        showToast('{{ __("app.file_downloaded") }}');
    }
}

function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 2500);
}

// Initialize
generateQR();
</script>
@endpush
