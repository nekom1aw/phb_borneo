/*
 * Alpine.js sudah dibawa Livewire (termasuk plugin intersect).
 * Data di bawah didaftarkan sebelum Livewire menyalakan Alpine (DOMContentLoaded).
 */

/* --u dihitung dari lebar viewport tanpa scrollbar agar tidak ada scroll horizontal */
function setUnit() {
    const w = document.documentElement.clientWidth || window.innerWidth;
    document.documentElement.style.setProperty('--u', Math.min(1, w / 1920) + 'px');
}
setUnit();
window.addEventListener('resize', setUnit);

document.addEventListener('alpine:init', () => {
    const Alpine = window.Alpine;

    /* Parallax: elemen ikut naik mengikuti scroll (speed 1 = secepat scroll, 0.6 = 60%-nya)
       dan memudar. Tanpa `fade`, jarak pudar dihitung otomatis: elemen jadi transparan tepat
       saat lapisan konten di bawah hero mencapainya, di layar berapa pun.
       Dipakai dengan @scroll.window.passive="onScroll()" dan :style="style". */
    Alpine.data('parallax', (speed, fade) => {
        const sp = speed == null ? 0.6 : speed;
        return {
            y: window.scrollY,
            cover: 0, // jarak scroll sampai konten mencapai elemen
            init() {
                const measure = () => {
                    const el = this.$el;
                    const box = el.offsetParent || el.parentElement;
                    const gap = box.offsetHeight - (el.offsetTop + el.offsetHeight); // sisa hero di bawah elemen
                    this.cover = sp < 1 ? gap / (1 - sp) : 0;
                };
                measure();
                window.addEventListener('resize', measure);
            },
            onScroll() {
                this.y = window.scrollY;
            },
            get style() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return '';
                const limit = fade || this.cover;
                const opacity = limit ? Math.max(0, 1 - this.y / limit) : 1;
                return `transform: translateY(${-this.y * sp}px); opacity: ${opacity}`;
            },
        };
    });

    /* Efek muncul dari bawah saat elemen masuk layar (dipakai bersama x-intersect) */
    Alpine.data('reveal', (delay) => ({
        shown: false,
        init() {
            // Elemen yang sudah terlihat saat halaman dibuka langsung dimunculkan
            setTimeout(() => {
                const r = this.$el.getBoundingClientRect();
                if (r.top < window.innerHeight && r.bottom > 0) this.show();
            }, 80);
        },
        show() {
            setTimeout(() => (this.shown = true), delay || 0);
        },
    }));

    /* State halaman user: menu mobile, navbar saat di-scroll, lightbox gambar */
    Alpine.data('site', () => ({
        open: false,
        scrolled: window.scrollY > 40,
        lightbox: null,
    }));

    /* ================= CMS ================= */

    /*
     * Form CMS dua bahasa.
     * models: nama kolom tanpa akhiran bahasa, misal ['title', 'deskripsi', 'content']
     * Editor TinyMCE memakai id "editor-<kolom>", misal editor-content_id.
     */
    Alpine.data('cmsForm', (models = []) => ({
        tab: 'id',
        dirty: false,
        models,
        init() {
            const markDirty = () => (this.dirty = true);
            this.$el.addEventListener('input', markDirty);
            this.$el.addEventListener('change', markDirty);
            window.addEventListener('cms-dirty', markDirty);
            window.addEventListener('cms-saved', () => (this.dirty = false));
            window.addEventListener('beforeunload', (e) => {
                if (!this.dirty) return;
                e.preventDefault();
                e.returnValue = '';
            });
        },
        filled(value) {
            return !!value && String(value).replace(/<[^>]*>/g, '').replace(/&nbsp;/g, ' ').trim().length > 0;
        },
        // 'done' (semua terisi), 'partial', atau 'empty'
        langState(lang) {
            const count = this.models.filter((m) => this.filled(this.$wire[m + '_' + lang])).length;
            if (count === 0) return 'empty';
            return count === this.models.length ? 'done' : 'partial';
        },
        copyFromId(message) {
            const hasEn = this.models.some((m) => this.filled(this.$wire[m + '_en']));
            if (hasEn && !window.confirm(message)) return;

            this.models.forEach((m) => {
                const source = window.tinymce && tinymce.get('editor-' + m + '_id');
                const target = window.tinymce && tinymce.get('editor-' + m + '_en');
                if (source && target) {
                    target.setContent(source.getContent());
                    target.fire('change');
                } else {
                    this.$wire.$set(m + '_en', this.$wire[m + '_id'] ?? '', false);
                }
            });

            this.dirty = true;
            this.tab = 'en';
        },
        // Dipanggil saat validasi gagal: pindah ke tab yang bermasalah lalu gulir ke isian pertama yang salah
        onInvalid(fields) {
            if (fields.some((f) => f.endsWith('_id'))) this.tab = 'id';
            else if (fields.some((f) => f.endsWith('_en'))) this.tab = 'en';
            setTimeout(() => {
                const el = document.querySelector('[data-error]');
                if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 80);
        },
    }));

    /* Area unggah foto: klik atau tarik-lepas file, dengan progres unggah Livewire */
    Alpine.data('cmsDropzone', () => ({
        over: false,
        uploading: false,
        progress: 0,
        drop(e) {
            this.over = false;
            if (!e.dataTransfer.files.length) return;
            this.$refs.input.files = e.dataTransfer.files;
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
    }));
});
