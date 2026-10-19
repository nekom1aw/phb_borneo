<div>
    <x-user.hero :src="storage_url($setting?->hero_page)" />

    <main class="mx-auto max-w-[1920px] px-5 py-10 md:px-10 md:py-14 xl:p-0 xl:pl-u-[140] xl:mt-u-[100.6]">
        <x-user.heading>{{ __($heading) }}</x-user.heading>

        <div class="mt-5 flex flex-col gap-8 xl:mt-u-[34.7] xl:gap-u-[51.7]">
            @forelse ($data as $item)
                <x-user.card :item="$item" size="row" wire:key="konten-{{ $item->id }}" />
            @empty
                <p class="text-base opacity-70">{{ __('No content yet.') }}</p>
            @endforelse
        </div>

        @if ($data->hasPages())
            <div class="mt-10 xl:mt-u-[60] xl:w-u-[1216]">
                {{ $data->links('components.user.pagination') }}
            </div>
        @endif
    </main>
</div>
