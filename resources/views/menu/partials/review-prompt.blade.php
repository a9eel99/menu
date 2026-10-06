{{--
    بطاقة "قيّمنا على Google" بصفحة المنيو. بتطلع مرة وحدة لكل زبون (كل 30 يوم)
    لما يوصل آخر المنيو أو بعد دقيقتين، أيهم أول. ما بتسكّر المنيو، بتطلع من تحت الشاشة.
    طلب عادي بيودّي لصفحة التقييم مباشرة (بدون فلترة حسب رأي الزبون، حسب قوانين Google).

    المتغيرات: $restaurant، $locale ('ar' أو 'en')، $scroller (عنصر السكرول، أو null للصفحة كلها)
--}}
@if($restaurant->showsReviewPrompt())
@php
    $reviewAr = ($locale ?? 'ar') === 'ar';
    $reviewColor = $restaurant->primary_color ?: '#cc2129';
@endphp
<div class="review-prompt" id="reviewPrompt" role="complementary" aria-live="polite" dir="{{ $reviewAr ? 'rtl' : 'ltr' }}" hidden>
    <button type="button" class="review-prompt-close" data-review-dismiss aria-label="{{ $reviewAr ? 'إغلاق' : 'Close' }}">&times;</button>
    <div class="review-prompt-stars" aria-hidden="true">★★★★★</div>
    <div class="review-prompt-title">
        {{ $reviewAr ? 'عجبك ' . $restaurant->getName('ar') . '؟' : 'Enjoying ' . $restaurant->getName('en') . '?' }}
    </div>
    <div class="review-prompt-text">
        {{ $reviewAr ? 'رأيك بيفرق معنا، قيّمنا على Google' : 'Your opinion matters to us. Rate us on Google' }}
    </div>
    <div class="review-prompt-actions">
        <a href="{{ $restaurant->google_reviews_url }}" target="_blank" rel="noopener" class="review-prompt-rate" data-review-dismiss>
            {{ $reviewAr ? 'قيّمنا' : 'Rate us' }}
        </a>
        <button type="button" class="review-prompt-later" data-review-dismiss>{{ $reviewAr ? 'لاحقاً' : 'Later' }}</button>
    </div>
</div>

<style>
    .review-prompt {
        position: fixed; left: 16px; right: 16px; bottom: calc(16px + env(safe-area-inset-bottom, 0px));
        max-width: 420px; margin: 0 auto; z-index: 1000;
        background: #fff; color: #1f2937; border-radius: 18px; padding: 18px 18px 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35); text-align: center;
        font-family: 'Tajawal', sans-serif;
        transform: translateY(calc(100% + 40px)); transition: transform .35s cubic-bezier(.2, .8, .2, 1);
    }
    .review-prompt.show { transform: translateY(0); }
    .review-prompt[hidden] { display: none; }
    .review-prompt-close {
        position: absolute; top: 8px; inset-inline-end: 10px; border: none; background: none;
        font-size: 1.5rem; line-height: 1; color: #9ca3af; cursor: pointer; padding: 4px;
    }
    .review-prompt-stars { color: #fbbc04; font-size: 1.3rem; letter-spacing: 2px; margin-bottom: 6px; }
    .review-prompt-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 4px; }
    .review-prompt-text { color: #6b7280; font-size: 0.9rem; margin-bottom: 14px; }
    .review-prompt-actions { display: flex; gap: 10px; }
    .review-prompt-actions > * {
        flex: 1; border-radius: 12px; padding: 11px; font-size: 0.95rem; font-weight: 700;
        font-family: inherit; text-decoration: none; cursor: pointer; border: none;
    }
    .review-prompt-rate { background: {{ $reviewColor }}; color: #fff; }
    .review-prompt-later { background: #f3f4f6; color: #4b5563; }
    @media (prefers-reduced-motion: reduce) { .review-prompt { transition: none; } }
</style>

<script>
(function () {
    var KEY = 'reviewPrompt:{{ $restaurant->id }}';
    var AGAIN_AFTER = 30 * 24 * 60 * 60 * 1000; // ما بترجع تطلع لنفس الزبون قبل 30 يوم
    var DELAY = 2 * 60 * 1000;                   // أو بعد دقيقتين على صفحة المنيو

    try {
        var seen = Number(localStorage.getItem(KEY));
        if (seen && Date.now() - seen < AGAIN_AFTER) return;
    } catch (e) {}

    var card = document.getElementById('reviewPrompt');
    var scroller = {!! $scroller ? 'document.querySelector(' . json_encode($scroller) . ')' : 'null' !!};
    var target = scroller || window;
    var shown = false, timer;

    function atEnd() {
        if (scroller) return scroller.scrollTop > 0 && scroller.scrollTop + scroller.clientHeight >= scroller.scrollHeight - 150;
        var doc = document.documentElement;
        return window.scrollY > 0 && window.scrollY + window.innerHeight >= doc.scrollHeight - 150;
    }

    function onScroll() { if (atEnd()) show(); }

    function show() {
        if (shown) return;
        shown = true;
        clearTimeout(timer);
        target.removeEventListener('scroll', onScroll);
        try { localStorage.setItem(KEY, String(Date.now())); } catch (e) {}
        card.hidden = false;
        requestAnimationFrame(function () { requestAnimationFrame(function () { card.classList.add('show'); }); });
    }

    function hide() {
        card.classList.remove('show');
        setTimeout(function () { card.hidden = true; }, 400);
    }

    card.querySelectorAll('[data-review-dismiss]').forEach(function (el) { el.addEventListener('click', hide); });
    target.addEventListener('scroll', onScroll, { passive: true });
    timer = setTimeout(show, DELAY);
})();
</script>
@endif
