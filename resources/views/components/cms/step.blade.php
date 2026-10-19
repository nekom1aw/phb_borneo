{{-- Kartu langkah bernomor --}}
@props(['number', 'title', 'desc' => null])

<section {{ $attributes->merge(['class' => 'cms-card p-5 md:p-6']) }}>
    <div class="mb-5 flex items-start gap-3">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-phb-green text-sm font-bold text-white">{{ $number }}</span>
        <div>
            <h2 class="text-lg font-bold text-stone-900">{{ $title }}</h2>
            @if ($desc)
                <p class="text-sm text-stone-500">{{ $desc }}</p>
            @endif
        </div>
    </div>
    {{ $slot }}
</section>
