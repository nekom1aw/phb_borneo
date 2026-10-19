<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@hasSection('title')@yield('title') – @endif{{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('favicon-32.png') }}" sizes="32x32" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    {{-- satuan desain dihitung sebelum halaman tampil agar tidak loncat --}}
    <script>document.documentElement.style.setProperty('--u', Math.min(1, document.documentElement.clientWidth / 1920) + 'px')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-poppins text-phb-text bg-white antialiased" x-data="site" @scroll.window.passive="scrolled = window.scrollY > 40" :class="open && 'overflow-hidden'">

    @include('components.user.navbar')

    @yield('content')

    @include('components.user.footer')

    {{-- lightbox gambar --}}
    <div x-cloak x-show="lightbox" x-transition.opacity @click="lightbox = null" @keydown.escape.window="lightbox = null"
         class="fixed inset-0 z-50 flex cursor-zoom-out items-center justify-center bg-black/80 p-4">
        <img :src="lightbox" alt="" class="max-h-full max-w-full rounded-lg shadow-2xl">
    </div>

    @livewireScripts
</body>

</html>
