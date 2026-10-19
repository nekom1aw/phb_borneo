{{-- Notifikasi singkat: dari session()->flash('success' | 'error') atau $this->dispatch('toast', message: '...') --}}
<div x-data="{
        items: [],
        push(message, type = 'success') {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });
            setTimeout(() => this.items = this.items.filter(i => i.id !== id), 4000);
        },
     }"
     x-init="
        @if (session()->has('success')) push(@js(session('success'))); @endif
        @if (session()->has('error')) push(@js(session('error')), 'error'); @endif
     "
     @toast.window="push($event.detail.message, $event.detail.type || 'success')"
     class="pointer-events-none fixed inset-x-4 bottom-4 z-[60] flex flex-col items-center gap-2 sm:inset-x-auto sm:right-6 sm:bottom-6 sm:items-end" aria-live="polite">
    <template x-for="item in items" :key="item.id">
        <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             role="status" class="pointer-events-auto flex w-full max-w-sm items-center gap-3 rounded-2xl bg-stone-900 px-4 py-3.5 text-sm text-white shadow-xl">
            <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full" :class="item.type === 'error' ? 'bg-red-500' : 'bg-emerald-500'">
                <svg x-show="item.type !== 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true"><path d="M5 12l5 5L20 7"/></svg>
                <svg x-show="item.type === 'error'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" class="h-4 w-4" aria-hidden="true"><path d="M12 7v6M12 17h.01"/></svg>
            </span>
            <span x-text="item.message"></span>
        </div>
    </template>
</div>
