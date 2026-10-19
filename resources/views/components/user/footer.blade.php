@php
    $locale = app()->getLocale();
    $footerNav = [
        ['label' => __('Who We Are'), 'url' => route('whoweare')],
        ['label' => __('Our Work'), 'url' => route('ourwork.index')],
        ['label' => __('Resources'), 'url' => route('resources.index')],
        ['label' => __('Event'), 'url' => route('event.index')],
        ['label' => __('Blog'), 'url' => route('blog.index')],
    ];
@endphp

<footer class="relative z-10 bg-phb-green text-white mt-10 xl:mt-u-[79]">
    <div class="relative mx-auto max-w-[1920px] flex flex-col gap-8 px-5 py-10 xl:block xl:p-0 xl:h-u-[279]">
        <a href="{{ route('index') }}" class="block w-16 xl:absolute xl:left-u-[140] xl:top-u-[68] xl:w-u-[129] xl:h-u-[143]" aria-label="{{ config('app.name') }}">
            @include('components.user.logo')
        </a>
        <div class="xl:contents">
            <p class="font-bold text-2xl leading-none xl:absolute xl:left-u-[312] xl:top-u-[74.5] xl:text-[20px]">{{ config('app.name') }}</p>
            @if ($setting)
                <p class="mt-3 text-base leading-relaxed xl:mt-0 xl:absolute xl:left-u-[312] xl:top-u-[125.5] xl:w-u-[545] xl:text-[14px] xl:leading-relaxed">{{ $locale === 'id' ? $setting->footer_id : $setting->footer_en }}</p>
            @endif
        </div>
        <nav class="flex flex-wrap gap-x-5 gap-y-2 uppercase text-sm xl:absolute xl:right-u-[141] xl:top-u-[73.5] xl:gap-u-[33] xl:text-[14px] xl:leading-none" aria-label="{{ __('Footer menu') }}">
            @foreach ($footerNav as $item)
                <a href="{{ $item['url'] }}" class="hover:opacity-80">{{ $item['label'] }}</a>
            @endforeach
        </nav>
        <p class="text-xs xl:absolute xl:right-u-[140] xl:top-u-[192] xl:text-[14px] xl:leading-none">Copyright © {{ date('Y') }} {{ config('app.name') }}. {{ __('All Rights Reserved.') }}</p>
    </div>
</footer>
