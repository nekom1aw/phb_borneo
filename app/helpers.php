<?php

/*
 * Helper kecil untuk view (tanpa model): ambil kolom sesuai bahasa aktif,
 * misal tr($row, 'title') -> title_id / title_en, kosong -> pakai bahasa lainnya.
 */
if (! function_exists('tr')) {
    function tr(?object $row, string $field): ?string
    {
        if (! $row) {
            return null;
        }

        $locale = app()->getLocale() === 'id' ? 'id' : 'en';
        $other = $locale === 'id' ? 'en' : 'id';

        return $row->{$field . '_' . $locale} ?: ($row->{$field . '_' . $other} ?? null);
    }
}

/*
 * URL file di disk public (storage/app/public), null bila kosong.
 */
if (! function_exists('storage_url')) {
    function storage_url(?string $path): ?string
    {
        return $path ? asset('storage/' . $path) : null;
    }
}

/*
 * URL detail konten: route sesuai kategori (resources, event, ourwork, blog).
 */
if (! function_exists('konten_url')) {
    function konten_url(object $row): string
    {
        $route = config('phb.kategori.' . $row->kategori . '.route');

        return route($route . '.detail', [
            'locale' => app()->getLocale(),
            'id' => $row->id,
            'slug' => $row->slug ?: null,
        ]);
    }
}

/*
 * Membersihkan HTML dari editor (TinyMCE) sebelum disimpan: buang <script>, atribut on*,
 * URL javascript:, dll. Elemen tulisan biasa, gambar, tabel, dan video embed (iframe) tetap boleh.
 */
if (! function_exists('bersihkan_html')) {
    function bersihkan_html(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        static $sanitizer = null;

        $sanitizer ??= new \Symfony\Component\HtmlSanitizer\HtmlSanitizer(
            (new \Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig())
                ->allowSafeElements()
                ->allowElement('iframe', ['src', 'width', 'height', 'title', 'allow', 'allowfullscreen', 'frameborder', 'loading', 'referrerpolicy'])
                ->allowAttribute('style', '*')
                ->allowAttribute('class', '*')
                ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
                ->allowMediaSchemes(['http', 'https'])
                ->allowRelativeLinks()
                ->allowRelativeMedias()
                ->forceAttribute('a', 'rel', 'noopener noreferrer')
                ->withMaxInputLength(500000)
        );

        return $sanitizer->sanitize($html);
    }
}
