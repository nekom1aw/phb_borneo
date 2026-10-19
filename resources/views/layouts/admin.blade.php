<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') – @endif Panel Admin {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="{{ asset('tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-[#f6f5f1] font-poppins text-stone-800 antialiased" x-data="{ sidebar: false }" @keydown.escape.window="sidebar = false">

    @include('components.cms.sidebar')

    <div class="lg:pl-72">
        {{-- bar atas --}}
        <header class="sticky top-0 z-30 border-b border-stone-200/70 bg-[#f6f5f1]/85 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-6xl items-center gap-3 px-4 md:px-8">
                <button type="button" class="cms-btn-ghost -ml-2 h-11 w-11 px-0 lg:hidden" @click="sidebar = true" :aria-expanded="sidebar" aria-label="Buka menu">
                    <x-cms.icon name="menu" class="h-6 w-6" />
                </button>
                <p class="min-w-0 truncate text-sm text-stone-500">
                    <span class="hidden sm:inline">Panel Admin</span>
                    @hasSection('title')
                        <span class="hidden sm:inline"> / </span><span class="font-semibold text-stone-800">@yield('title')</span>
                    @endif
                </p>
                <a href="{{ url('/id') }}" target="_blank" rel="noopener" class="cms-btn ml-auto h-10">
                    <x-cms.icon name="external" class="h-4 w-4" />
                    <span>Lihat website</span>
                </a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 pb-16 pt-8 md:px-8">
            @yield('content')
        </main>
    </div>

    @include('components.cms.toast')

    @livewireScripts
    @stack('scripts')
</body>

</html>
