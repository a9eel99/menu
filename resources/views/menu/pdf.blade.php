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

    {{-- نبلش ننزل مكتبة PDF.js فوراً بدل ما تستنى آخر الصفحة --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" as="script">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js" as="script">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        svg.icon { display: inline-block; height: 1em; vertical-align: -0.125em; overflow: visible; }

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
            width: 100%;
            flex-shrink: 0;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            border-radius: 4px;
            overflow: hidden;
        }

        .pdf-page canvas {
            display: block;
            width: 100%;
            height: auto;
        }

        .loading {
            color: white;
            text-align: center;
            padding: 40px;
        }

        .loading i, .loading svg {
            font-size: 2rem;
            margin-bottom: 16px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

            </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <a href="{{ route('menu.landing', $restaurant->slug) }}" class="back-btn">
                <x-icon name="arrow-right" />
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
            <button onclick="shareMenu()" class="action-btn">
                <x-icon name="share-alt" />
            </button>
        </header>

        <div class="pdf-viewer" id="viewer">
            <div class="loading">
                <x-icon name="spinner" />
                <div>جاري تحميل القائمة...</div>
            </div>
        </div>

            </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

        const pdfUrl = "{{ $restaurant->getMenuPdfUrl() }}";
        const viewer = document.getElementById('viewer');

        // أقصى دقة للرسم: أكثر من هيك بياكل ذاكرة التلفون بدون فرق بيبيّن
        const MAX_PIXEL_RATIO = 2;

        async function loadPDF() {
            try {
                // بدون autoFetch: المتصفح بينزل بس أجزاء الملف للصفحات اللي بتنعرض
                const pdf = await pdfjsLib.getDocument({
                    url: pdfUrl,
                    disableAutoFetch: true,
                    disableStream: true,
                    rangeChunkSize: 65536,
                }).promise;

                const firstPage = await pdf.getPage(1);
                const baseViewport = firstPage.getViewport({ scale: 1 });
                const ratio = baseViewport.width / baseViewport.height;

                // نحجز مكان لكل صفحة بنفس المقاس عشان السكرول يكون ثابت
                viewer.innerHTML = '';
                const slots = [];
                for (let i = 1; i <= pdf.numPages; i++) {
                    const slot = document.createElement('div');
                    slot.className = 'pdf-page';
                    slot.style.aspectRatio = ratio;
                    slot.dataset.page = i;
                    viewer.appendChild(slot);
                    slots.push(slot);
                }

                // نرسم صفحة وحدة بكل مرة، حسب الترتيب
                const queued = new Set();
                let chain = Promise.resolve();
                const renderPage = (num) => {
                    if (queued.has(num)) return;
                    queued.add(num);
                    chain = chain.then(async () => {
                        const page = num === 1 ? firstPage : await pdf.getPage(num);
                        const slot = slots[num - 1];
                        const pixelRatio = Math.min(window.devicePixelRatio || 1, MAX_PIXEL_RATIO);
                        const viewport = page.getViewport({ scale: (slot.clientWidth / page.getViewport({ scale: 1 }).width) * pixelRatio });

                        const canvas = document.createElement('canvas');
                        canvas.width = Math.floor(viewport.width);
                        canvas.height = Math.floor(viewport.height);
                        await page.render({ canvasContext: canvas.getContext('2d'), viewport }).promise;

                        slot.style.aspectRatio = '';
                        slot.appendChild(canvas);
                    }).catch(() => {});
                };

                renderPage(1);

                // باقي الصفحات بتنرسم لما الزبون يقرب يوصلها بالسكرول
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            renderPage(Number(entry.target.dataset.page));
                            observer.unobserve(entry.target);
                        }
                    });
                }, { root: viewer, rootMargin: '100% 0px' });
                slots.slice(1).forEach((slot) => observer.observe(slot));
            } catch (error) {
                viewer.innerHTML = '<div class="loading"><span style="color:#ef4444;font-size:2rem;">{!! \App\Support\Icons::svg('exclamation-triangle') !!}</span><div>حدث خطأ في تحميل الملف</div><a href="' + pdfUrl + '" target="_blank" class="btn btn-primary" style="margin-top:16px;display:inline-flex;">{!! \App\Support\Icons::svg('external-link-alt') !!} فتح الملف</a></div>';
            }
        }

        loadPDF();

        function shareMenu() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $restaurant->name_ar }} - قائمة الطعام',
                    url: '{{ route('menu.show', $restaurant->slug) }}'
                });
            } else {
                navigator.clipboard.writeText('{{ route('menu.show', $restaurant->slug) }}');
                alert('تم نسخ الرابط');
            }
        }
    </script>
</body>
</html>
