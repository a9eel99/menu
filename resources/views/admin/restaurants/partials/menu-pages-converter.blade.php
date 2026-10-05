{{--
    تحويل منيو الـ PDF لصور WebP بالمتصفح وقت الرفع، عشان صفحة المنيو عند الزبون تعرض صور
    (أسرع بكثير من رسم الـ PDF على التلفون). إذا التحويل فشل، المنيو بينعرض كـ PDF زي قبل.
--}}
<input type="hidden" name="menu_pages_data" id="menuPagesData">
<div class="menu-pages-status" id="menuPagesStatus">
    @if(isset($restaurant) && $restaurant->menu_pdf)
        @if($restaurant->hasMenuPageImages())
            <i class="fas fa-check-circle text-success"></i>
            المنيو معروض للزباين كصور سريعة ({{ count($restaurant->menu_pages) }} صفحة)
        @else
            <i class="fas fa-info-circle text-warning"></i>
            المنيو الحالي معروض كملف PDF (أبطأ على التلفون).
        @endif
        <button type="button" class="menu-pages-btn" id="convertCurrentMenu"
            data-url="{{ route('admin.restaurants.menu-pages', $restaurant) }}"
            data-pdf="{{ $restaurant->getMenuPdfUrl() }}">
            <i class="fas fa-images"></i>
            {{ $restaurant->hasMenuPageImages() ? 'إعادة تحويل المنيو لصور' : 'تحويل المنيو الحالي لصور' }}
        </button>
    @endif
</div>

@push('styles')
<style>
    .menu-pages-status { margin-top: 12px; font-size: 0.9rem; color: #475569; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .menu-pages-status.converting { color: #2563eb; }
    .menu-pages-status.done { color: #16a34a; }
    .menu-pages-status.failed { color: #b45309; }
    .menu-pages-btn { border: 1px solid #cbd5e1; background: #fff; border-radius: 8px; padding: 6px 12px; font-size: 0.85rem; cursor: pointer; }
    .menu-pages-btn:disabled { opacity: .6; cursor: wait; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const PDFJS = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/';
    const TARGET_WIDTH = 1080;   // عرض صورة الصفحة بالبكسل (كافي لشاشات التلفون)
    const MAX_PAGES = {{ \App\Support\MenuPages::MAX_PAGES }};

    const input = document.getElementById('pdfFileInput');
    const hidden = document.getElementById('menuPagesData');
    const status = document.getElementById('menuPagesStatus');
    const convertBtn = document.getElementById('convertCurrentMenu');
    let converting = false;

    function setStatus(state, text) {
        status.className = 'menu-pages-status ' + state;
        status.textContent = text;
    }

    let pdfjsReady = null;
    function loadPdfJs() {
        if (!pdfjsReady) {
            pdfjsReady = new Promise((resolve, reject) => {
                const s = document.createElement('script');
                s.src = PDFJS + 'pdf.min.js';
                s.onload = () => {
                    pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS + 'pdf.worker.min.js';
                    resolve(pdfjsLib);
                };
                s.onerror = reject;
                document.head.appendChild(s);
            });
        }
        return pdfjsReady;
    }

    // يرجع مصفوفة data URLs، صورة لكل صفحة
    async function convert(source) {
        const lib = await loadPdfJs();
        const pdf = await lib.getDocument(source).promise;
        if (pdf.numPages > MAX_PAGES) {
            throw new Error('too-many-pages');
        }
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const images = [];
        for (let i = 1; i <= pdf.numPages; i++) {
            setStatus('converting', 'جاري تحويل المنيو لصور... صفحة ' + i + ' من ' + pdf.numPages);
            const page = await pdf.getPage(i);
            const viewport = page.getViewport({ scale: TARGET_WIDTH / page.getViewport({ scale: 1 }).width });
            canvas.width = Math.round(viewport.width);
            canvas.height = Math.round(viewport.height);
            ctx.fillStyle = '#fff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            await page.render({ canvasContext: ctx, viewport }).promise;
            let url = canvas.toDataURL('image/webp', 0.8);
            if (!url.startsWith('data:image/webp')) {
                url = canvas.toDataURL('image/jpeg', 0.85); // متصفحات ما بتدعم WebP (Safari القديم)
            }
            images.push(url);
            page.cleanup();
        }
        await pdf.destroy();
        return images;
    }

    if (input) {
        input.addEventListener('change', async function () {
            hidden.value = '';
            if (!this.files.length) return;
            converting = true;
            try {
                const images = await convert({ data: new Uint8Array(await this.files[0].arrayBuffer()) });
                hidden.value = JSON.stringify(images);
                setStatus('done', '✓ تم تجهيز ' + images.length + ' صفحة كصور. اضغط حفظ.');
            } catch (e) {
                setStatus('failed', e.message === 'too-many-pages'
                    ? 'المنيو فيه صفحات كثيرة (أكثر من ' + MAX_PAGES + ')، رح ينعرض كملف PDF.'
                    : 'تعذّر تحويل المنيو لصور، رح ينعرض كملف PDF.');
            } finally {
                converting = false;
            }
        });

        input.form.addEventListener('submit', function (e) {
            if (converting) {
                e.preventDefault();
                alert('استنى لحد ما يخلص تحويل صفحات المنيو، وبعدين اضغط حفظ.');
            }
        });
    }

    if (convertBtn) {
        convertBtn.addEventListener('click', async function () {
            const btn = this;
            btn.disabled = true;
            converting = true;
            try {
                const images = await convert(btn.dataset.pdf);
                setStatus('converting', 'جاري رفع الصور...');
                const res = await fetch(btn.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ menu_pages_data: JSON.stringify(images) }),
                });
                if (!res.ok) throw new Error('upload');
                const data = await res.json();
                setStatus('done', '✓ المنيو صار معروض للزباين كصور سريعة (' + data.pages + ' صفحة)');
            } catch (e) {
                setStatus('failed', 'تعذّر تحويل المنيو لصور. المنيو لسا معروض كملف PDF.');
                btn.disabled = false;
            } finally {
                converting = false;
            }
        });
    }
})();
</script>
@endpush
