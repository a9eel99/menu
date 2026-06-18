<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta name="theme-color" content="{{ $restaurant->secondary_color ?? '#1a1a2e' }}">
    <title>{{ $restaurant->name_ar }} - قائمة الطعام</title>

    @if($restaurant->logo)
    <link rel="icon" href="{{ asset('storage/' . $restaurant->logo) }}" type="image/png">
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            overflow: hidden;
            background: #1a1a2e;
            font-family: 'Cairo', sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .header {
            background: #12121a;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .back-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            object-fit: cover;
        }

        .brand-name {
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .brand-subtitle {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: {{ $restaurant->primary_color ?? '#FF6B35' }};
            border: none;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .pdf-viewer {
            flex: 1;
            overflow: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            background: #2a2a3e;
        }

        .pdf-page {
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 4px;
        }

        .pdf-page canvas {
            display: block;
            max-width: 100%;
            height: auto !important;
        }

        .loading {
            color: white;
            text-align: center;
            padding: 40px;
        }

        .loading i {
            font-size: 2rem;
            margin-bottom: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .bottom-bar {
            background: #12121a;
            padding: 12px 16px;
            display: flex;
            justify-content: center;
            gap: 12px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 12px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .btn-primary {
            background: {{ $restaurant->primary_color ?? '#FF6B35' }};
        }

        .btn-secondary {
            background: #25D366;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="{{ route('menu.landing', $restaurant->slug) }}" class="back-btn">
                <i class="fas fa-arrow-right"></i>
            </a>
            <div class="brand">
                @if($restaurant->getLogoUrl())
                    <img src="{{ $restaurant->getLogoUrl() }}" alt="" class="brand-logo">
                @endif
                <div>
                    <div class="brand-name">{{ $restaurant->name_ar }}</div>
                    <div class="brand-subtitle">قائمة الطعام</div>
                </div>
            </div>
            <a href="{{ $restaurant->getMenuPdfUrl() }}" download class="action-btn">
                <i class="fas fa-download"></i>
            </a>
        </header>

        <div class="pdf-viewer" id="viewer">
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <div>جاري تحميل القائمة...</div>
            </div>
        </div>

        <div class="bottom-bar">
            <a href="{{ $restaurant->getMenuPdfUrl() }}" download class="btn btn-primary">
                <i class="fas fa-download"></i>
                تحميل
            </a>
            <a href="https://wa.me/?text={{ urlencode($restaurant->name_ar . ': ' . route('menu.show', $restaurant->slug)) }}" target="_blank" class="btn btn-secondary">
                <i class="fab fa-whatsapp"></i>
                مشاركة
            </a>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = "{{ $restaurant->getMenuPdfUrl() }}";
        const viewer = document.getElementById('viewer');

        async function loadPDF() {
            try {
                const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
                viewer.innerHTML = '';

                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);

                    // حساب العرض المناسب
                    const containerWidth = viewer.clientWidth - 32;
                    const viewport = page.getViewport({ scale: 1 });
                    const scale = containerWidth / viewport.width;
                    const scaledViewport = page.getViewport({ scale: scale * 2 }); // جودة عالية

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = scaledViewport.width;
                    canvas.height = scaledViewport.height;
                    canvas.style.width = (scaledViewport.width / 2) + 'px';
                    canvas.style.height = (scaledViewport.height / 2) + 'px';

                    const pageDiv = document.createElement('div');
                    pageDiv.className = 'pdf-page';
                    pageDiv.appendChild(canvas);
                    viewer.appendChild(pageDiv);

                    await page.render({
                        canvasContext: context,
                        viewport: scaledViewport
                    }).promise;
                }
            } catch (error) {
                viewer.innerHTML = '<div class="loading"><i class="fas fa-exclamation-triangle" style="animation:none;color:#ef4444;"></i><div>حدث خطأ في تحميل الملف</div><a href="' + pdfUrl + '" target="_blank" class="btn btn-primary" style="margin-top:16px;display:inline-flex;"><i class="fas fa-external-link-alt"></i> فتح الملف</a></div>';
            }
        }

        loadPDF();
    </script>
</body>
</html>
