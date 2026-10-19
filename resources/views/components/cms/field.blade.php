{{-- Label + isian (slot) + petunjuk + pesan error. counter="title_id" max="120" menampilkan jumlah karakter --}}
@props(['label', 'for' => null, 'error' => null, 'hint' => null, 'required' => false, 'optional' => false, 'counter' => null, 'max' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col gap-2']) }}>
    <div class="flex items-baseline justify-between gap-3">
        <label @if ($for) for="{{ $for }}" @endif class="cms-label">
            {{ $label }}
            @if ($required)
                <span class="ml-1 text-xs font-medium text-red-600">wajib</span>
            @elseif ($optional)
                <span class="ml-1 text-xs font-medium text-stone-400">opsional</span>
            @endif
        </label>
        @if ($counter && $max)
            <span class="text-xs tabular-nums text-stone-400" x-text="($wire.{{ $counter }} || '').length + ' / {{ $max }}'"
                  :class="($wire.{{ $counter }} || '').length > {{ (int) $max }} && 'text-amber-600! font-semibold'"></span>
        @endif
    </div>
    {{ $slot }}
    @if ($error)
        <p data-error class="flex items-center gap-1.5 text-sm font-medium text-red-600"><x-cms.icon name="alert" class="h-4 w-4" /> {{ $error }}</p>
    @elseif ($hint)
        <p class="text-[13px] leading-relaxed text-stone-500">{{ $hint }}</p>
    @endif
</div>
