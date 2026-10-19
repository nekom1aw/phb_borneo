<?php

/*
 * Kategori konten (tabel `konten`).
 * - label, heading, route: dipakai halaman user (label/heading diterjemahkan lewat lang/id.json)
 * - nama, desc, icon: dipakai CMS (bahasa Indonesia, untuk pengelola)
 */
return [
    'kategori' => [
        'photo' => [
            'label' => 'Our Work',
            'heading' => 'Photo',
            'route' => 'ourwork',
            'nama' => 'Karya Kami',
            'desc' => 'Foto kegiatan dan hasil kerja di lapangan. Tampil di menu “Our Work” dan bagian Galeri di Beranda.',
            'icon' => 'camera',
        ],
        'resources' => [
            'label' => 'Resources',
            'heading' => 'Resources',
            'route' => 'resources',
            'nama' => 'Publikasi',
            'desc' => 'Laporan, kajian, dan dokumen PDF. Tampil di menu “Resources” dan di Beranda.',
            'icon' => 'book',
        ],
        'event' => [
            'label' => 'Event',
            'heading' => 'Event',
            'route' => 'event',
            'nama' => 'Kegiatan',
            'desc' => 'Acara yang akan datang atau yang sudah berlangsung. Tampil di menu “Event” dan di Beranda.',
            'icon' => 'calendar',
        ],
        'blog' => [
            'label' => 'Blog',
            'heading' => 'Blog',
            'route' => 'blog',
            'nama' => 'Blog',
            'desc' => 'Tulisan, cerita, dan berita terbaru. Tampil di menu “Blog”.',
            'icon' => 'pen',
        ],
    ],
];
