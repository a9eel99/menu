<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta name="theme-color" content="{{ $restaurant->secondary_color ?? '#1a1a2e' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $restaurant->name_ar }} - قائمة الطعام</title>

    @if($restaurant->logo)
    <link rel="icon" href="{{ asset('storage/' . $restaurant->logo) }}" type="image/png">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @php
        $primaryColor = $restaurant->primary_color ?? '#FF6B35';
        $secondaryColor = $restaurant->secondary_color ?? '#1a1a2e';
    @endphp

    <style>
        :root {
            --primary: {{ $primaryColor }};
            --secondary: {{ $secondaryColor }};
            --bg-dark: #0a0a0f;
            --bg-card: #12121a;
            --text: #ffffff;
            --text-muted: #94a3b8;
            --border: rgba(255,255,255,0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: var(--bg-dark);
            color: var(--text);
            display: flex;
            flex-direction: column;
        }

        /* ==================== HEADER ==================== */
        .header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
            z-index: 100;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.1);
            color: var(--text);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex: 1;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: cover;
            flex-shrink: 0;
            background: rgba(255,255,255,0.05);
        }

        .brand-info {
            min-width: 0;
        }

        .brand-name {
            font-size: 0.95rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            gap: 8px;
            flex-shrink: 0;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--text);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
        }

        .action-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .action-btn.primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .action-btn.primary:hover {
            filter: brightness(1.1);
        }

        /* ==================== PDF VIEWER ==================== */
        .pdf-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            background: #1a1a2e;
        }

        /* Loading Screen */
        .loading-screen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-dark);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            position: relative;
            margin-bottom: 24px;
        }

        .loading-spinner::before,
        .loading-spinner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
        }

        .loading-spinner::before {
            width: 100%;
            height: 100%;
            border: 3px solid var(--border);
        }

        .loading-spinner::after {
            width: 100%;
            height: 100%;
            border: 3px solid transparent;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .loading-progress {
            width: 200px;
            height: 4px;
            background: var(--border);
            border-radius: 2px;
            overflow: hidden;
        }

        .loading-progress-bar {
            height: 100%;
            background: var(--primary);
            width: 0%;
            transition: width 0.3s;
        }

        /* PDF Viewport */
        .pdf-viewport {
            flex: 1;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
            gap: 16px;
            scroll-behavior: smooth;
        }

        .pdf-page {
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 4px;
            max-width: 100%;
            display: block;
        }

        .pdf-page canvas {
            display: block;
            max-width: 100%;
            height: auto !important;
        }


        /* ==================== MOBILE OPTIMIZATIONS ==================== */
        @media (max-width: 640px) {
            .header {
                padding: 10px 12px;
            }

            .brand-name {
                font-size: 0.85rem;
            }

            .action-btn,
            .back-btn {
                width: 36px;
                height: 36px;
                border-radius: 10px;
            }

            .pdf-viewport {
                padding: 12px;
                gap: 12px;
            }
        }

        /* ==================== PINCH ZOOM SUPPORT ==================== */
        .pdf-viewport.pinch-zoom {
            touch-action: pan-x pan-y;
        }

        /* ==================== DOWNLOAD MODAL ==================== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            padding: 20px;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: 24px;
            padding: 32px;
            max-width: 360px;
            width: 100%;
            text-align: center;
            transform: scale(0.9);
            transition: transform 0.3s;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1);
        }

        .modal-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), rgba(255,107,53,0.6));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 2rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .modal-text {
            color: var(--text-muted);
            margin-bottom: 24px;
            line-height: 1.6;
        }

        .modal-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .modal-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .modal-btn.primary {
            background: var(--primary);
            color: white;
        }

        .modal-btn.primary:hover {
            filter: brightness(1.1);
        }

        .modal-btn.ghost {
            background: rgba(255,255,255,0.05);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .modal-btn.ghost:hover {
            background: rgba(255,255,255,0.1);
        }

        /* Error State */
        .error-state {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            text-align: center;
        }

        .error-state.show {
            display: flex;
        }

        .error-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            font-size: 2rem;
            color: #ef4444;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-right">
            <a href="{{ route('menu.landing', $restaurant->slug) }}" class="back-btn">
                <i class="fas fa-arrow-right"></i>
            </a>
            <div class="brand">
                @if($restaurant->getLogoUrl())
                    <img src="{{ $restaurant->getLogoUrl() }}" alt="{{ $restaurant->name_ar }}" class="brand-logo">
                @endif
                <div class="brand-info">
                    <div class="brand-name">{{ $restaurant->name_ar }}</div>
                    <div class="brand-subtitle">قائمة الطعام</div>
                </div>
            </div>
        </div>
        <div class="header-actions">
            <button class="action-btn primary" onclick="shareMenu()">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>
    </header>

    <!-- PDF Container -->
    <div class="pdf-container">
        <!-- Loading Screen -->
        <div class="loading-screen" id="loadingScreen">
            <div class="loading-spinner"></div>
            <div class="loading-text">جاري تحميل القائمة...</div>
            <div class="loading-progress">
                <div class="loading-progress-bar" id="progressBar"></div>
            </div>
        </div>

        <!-- Error State -->
        <div class="error-state" id="errorState">
            <div class="error-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 style="margin-bottom: 12px;">حدث خطأ</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">تعذر تحميل الملف</p>
            <a href="{{ $restaurant->getMenuPdfUrl() }}" target="_blank" class="modal-btn primary">
                <i class="fas fa-external-link-alt"></i>
                فتح في نافذة جديدة
            </a>
        </div>

        <!-- PDF Viewport -->
        <div class="pdf-viewport" id="pdfViewport"></div>
    </div>


    <!-- Share Modal -->
    <div class="modal-overlay" id="shareModal" onclick="hideShareModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-icon">
                <i class="fas fa-share-alt"></i>
            </div>
            <h3 class="modal-title">مشاركة القائمة</h3>
            <p class="modal-text">شارك قائمة الطعام مع أصدقائك</p>
            <div class="modal-buttons">
                <a href="https://wa.me/?text={{ urlencode($restaurant->name_ar . ' - قائمة الطعام: ' . route('menu.show', $restaurant->slug)) }}" target="_blank" class="modal-btn primary" style="background: #25D366;">
                    <i class="fab fa-whatsapp"></i>
                    واتساب
                </a>
                <button class="modal-btn ghost" onclick="copyLink()">
                    <i class="fas fa-link"></i>
                    <span id="copyText">نسخ الرابط</span>
                </button>
                <button class="modal-btn ghost" onclick="hideShareModal()">
                    إلغاء
                </button>
            </div>
        </div>
    </div>

    <!-- PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        // Configure PDF.js worker
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = "{{ $restaurant->getMenuPdfUrl() }}";
        const viewport = document.getElementById('pdfViewport');
        const loadingScreen = document.getElementById('loadingScreen');
        const errorState = document.getElementById('errorState');
        const progressBar = document.getElementById('progressBar');

        let pdfDoc = null;
        let totalPages = 0;
        let scale = 1;
        let baseScale = 1;
        let rendering = false;
        let pageCanvases = [];

        // Load PDF
        async function loadPDF() {
            try {
                const loadingTask = pdfjsLib.getDocument({
                    url: pdfUrl,
                    cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
                    cMapPacked: true
                });

                loadingTask.onProgress = function(progress) {
                    if (progress.total > 0) {
                        const percent = Math.round((progress.loaded / progress.total) * 100);
                        progressBar.style.width = percent + '%';
                    }
                };

                pdfDoc = await loadingTask.promise;
                totalPages = pdfDoc.numPages;

                // Calculate optimal scale for mobile
                const firstPage = await pdfDoc.getPage(1);
                const unscaledViewport = firstPage.getViewport({ scale: 1 });
                const containerWidth = viewport.clientWidth - 32;
                baseScale = containerWidth / unscaledViewport.width;
                scale = baseScale;

                // Render all pages
                await renderAllPages();

                // Hide loading
                loadingScreen.classList.add('hidden');

            } catch (error) {
                console.error('Error loading PDF:', error);
                loadingScreen.classList.add('hidden');
                errorState.classList.add('show');
            }
        }

        // Render visible pages only (lazy loading)
        async function renderAllPages() {
            viewport.innerHTML = '';
            pageCanvases = [];

            // Create placeholders for all pages first
            for (let i = 1; i <= totalPages; i++) {
                const pageDiv = document.createElement('div');
                pageDiv.className = 'pdf-page';
                pageDiv.id = 'page-' + i;
                pageDiv.dataset.pageNum = i;
                pageDiv.dataset.rendered = 'false';

                // Set placeholder size based on first page
                const page = await pdfDoc.getPage(1);
                const pdfViewport = page.getViewport({ scale: scale });
                pageDiv.style.width = Math.floor(pdfViewport.width) + 'px';
                pageDiv.style.height = Math.floor(pdfViewport.height) + 'px';
                pageDiv.style.background = '#f0f0f0';

                viewport.appendChild(pageDiv);
            }

            // Render first 2 pages immediately
            await renderPage(1);
            if (totalPages > 1) await renderPage(2);

            // Setup intersection observer for lazy loading
            setupLazyLoading();
        }

        // Render single page
        async function renderPage(pageNum) {
            const pageDiv = document.getElementById('page-' + pageNum);
            if (!pageDiv || pageDiv.dataset.rendered === 'true') return;

            const page = await pdfDoc.getPage(pageNum);
            const pdfViewport = page.getViewport({ scale: scale });

            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');

            // Use lower DPI on mobile for speed
            const outputScale = Math.min(window.devicePixelRatio || 1, 2);
            canvas.width = Math.floor(pdfViewport.width * outputScale);
            canvas.height = Math.floor(pdfViewport.height * outputScale);
            canvas.style.width = Math.floor(pdfViewport.width) + 'px';
            canvas.style.height = Math.floor(pdfViewport.height) + 'px';

            context.scale(outputScale, outputScale);

            pageDiv.innerHTML = '';
            pageDiv.style.background = 'white';
            pageDiv.appendChild(canvas);
            pageDiv.dataset.rendered = 'true';
            pageCanvases.push({ canvas, page, pageNum });

            await page.render({
                canvasContext: context,
                viewport: pdfViewport
            }).promise;
        }

        // Lazy loading with Intersection Observer
        function setupLazyLoading() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const pageNum = parseInt(entry.target.dataset.pageNum);
                        renderPage(pageNum);
                        // Pre-render next page
                        if (pageNum < totalPages) renderPage(pageNum + 1);
                    }
                });
            }, { rootMargin: '200px' });

            document.querySelectorAll('.pdf-page').forEach(page => {
                observer.observe(page);
            });
        }

        // Re-render at new scale
        async function rerender() {
            if (rendering) return;
            rendering = true;

            for (const item of pageCanvases) {
                const pdfViewport = item.page.getViewport({ scale: scale });
                const context = item.canvas.getContext('2d');

                const outputScale = window.devicePixelRatio || 1;
                item.canvas.width = Math.floor(pdfViewport.width * outputScale);
                item.canvas.height = Math.floor(pdfViewport.height * outputScale);
                item.canvas.style.width = Math.floor(pdfViewport.width) + 'px';
                item.canvas.style.height = Math.floor(pdfViewport.height) + 'px';

                context.scale(outputScale, outputScale);

                await item.page.render({
                    canvasContext: context,
                    viewport: pdfViewport
                }).promise;
            }

            rendering = false;
        }

        // Share functions
        const shareUrl = "{{ route('menu.show', $restaurant->slug) }}";
        const shareTitle = "{{ $restaurant->name_ar }} - قائمة الطعام";

        async function shareMenu() {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: shareTitle,
                        text: 'شاهد قائمة الطعام',
                        url: shareUrl
                    });
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        showShareModal();
                    }
                }
            } else {
                showShareModal();
            }
        }

        function showShareModal() {
            document.getElementById('shareModal').classList.add('show');
        }

        function hideShareModal(e) {
            if (!e || e.target === document.getElementById('shareModal')) {
                document.getElementById('shareModal').classList.remove('show');
            }
        }

        function copyLink() {
            navigator.clipboard.writeText(shareUrl).then(() => {
                const copyText = document.getElementById('copyText');
                copyText.textContent = 'تم النسخ ✓';
                setTimeout(() => {
                    copyText.textContent = 'نسخ الرابط';
                }, 2000);
            });
        }

        // Handle window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                const containerWidth = viewport.clientWidth - 32;
                if (pdfDoc) {
                    pdfDoc.getPage(1).then(page => {
                        const unscaledViewport = page.getViewport({ scale: 1 });
                        const newBaseScale = containerWidth / unscaledViewport.width;
                        const ratio = scale / baseScale;
                        baseScale = newBaseScale;
                        scale = baseScale * ratio;
                        rerender();
                    });
                }
            }, 250);
        });

        // Initialize
        loadPDF();
    </script>
</body>
</html>
