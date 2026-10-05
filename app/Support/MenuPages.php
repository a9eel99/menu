<?php

namespace App\Support;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * صفحات منيو الـ PDF كصور. المتصفح بيحوّل الـ PDF لصور وقت الرفع
 * وبيبعثها كـ data URLs (نص) عشان ما نتقيّد بحد عدد الملفات بـ PHP.
 */
class MenuPages
{
    public const MAX_PAGES = 60;
    private const MAX_BYTES = 3 * 1024 * 1024;
    private const TYPES = ['image/webp' => 'webp', 'image/jpeg' => 'jpg'];

    /**
     * يفك ويتحقق من الصور، ويحفظها بمجلد جديد للمطعم، ويرجع بيانات الصفحات.
     * كل رفع بمجلد جديد عشان الكاش الطويل عند الزباين ما يعرض منيو قديم.
     */
    public static function store(Restaurant $restaurant, string $json): array
    {
        $images = json_decode($json, true);
        if (!is_array($images) || count($images) === 0 || count($images) > self::MAX_PAGES) {
            throw ValidationException::withMessages(['menu_pdf' => 'تعذّر تحويل صفحات المنيو لصور.']);
        }

        $decoded = [];
        foreach (array_values($images) as $dataUrl) {
            if (!is_string($dataUrl) || !preg_match('#^data:(image/(?:webp|jpeg));base64,([A-Za-z0-9+/=]+)$#', $dataUrl, $m)) {
                throw ValidationException::withMessages(['menu_pdf' => 'صورة صفحة غير صالحة.']);
            }
            $bytes = base64_decode($m[2], true);
            $info = $bytes !== false && strlen($bytes) <= self::MAX_BYTES ? @getimagesizefromstring($bytes) : false;
            if (!$info || !isset(self::TYPES[$info['mime']]) || $info[0] > 4000 || $info[1] > 8000) {
                throw ValidationException::withMessages(['menu_pdf' => 'صورة صفحة غير صالحة.']);
            }
            $decoded[] = [$bytes, self::TYPES[$info['mime']], $info[0], $info[1]];
        }

        $dir = 'restaurants/menu-pages/' . $restaurant->id . '/' . uniqid();
        $pages = [];
        foreach ($decoded as $i => [$bytes, $ext, $width, $height]) {
            $path = sprintf('%s/p-%02d.%s', $dir, $i + 1, $ext);
            Storage::disk('public')->put($path, $bytes);
            $pages[] = ['path' => $path, 'width' => $width, 'height' => $height];
        }

        return $pages;
    }

    /** يحذف مجلد صور الصفحات */
    public static function delete(?array $pages): void
    {
        if (!empty($pages[0]['path'])) {
            Storage::disk('public')->deleteDirectory(dirname($pages[0]['path']));
        }
    }
}
