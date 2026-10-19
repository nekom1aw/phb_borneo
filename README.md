# Prasasti Hijau Borneo — Laravel

Website Prasasti Hijau Borneo (desain dari `prasasti-hijau-borneo/`, HTML + Tailwind + Alpine) yang dipindah ke
**Laravel 13 + Livewire 4 + Tailwind CSS 4**, dengan halaman user dan CMS admin (TinyMCE).

- **Tanpa model / Eloquent.** Semua akses data memakai Query Builder (`DB::table(...)`) di komponen Livewire.
- **Controller hanya mengembalikan view** (`UserController`, `CmsController`), lalu view memanggil komponen Livewire.
- **Dua bahasa** lewat URL: `/en/...` dan `/id/...` (`LanguageMiddleware`). Kolom isi selalu berpasangan `*_id` / `*_en`.
  Tombol **ID / EN** ada di ujung pil kanan navbar (desktop) dan di pil kanan (mobile).

## Menjalankan

```bash
composer install
npm install          # sekaligus menyalin TinyMCE ke public/tinymce
cp .env.example .env && php artisan key:generate   # isi DB_PASSWORD dan ADMIN_PASSWORD
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve --port=8010
```

Buka http://127.0.0.1:8010 (port 8000 dipakai container lain). CMS: http://127.0.0.1:8010/cms/login,
akun awal dari `ADMIN_EMAIL` / `ADMIN_PASSWORD` di `.env` (dibuat oleh `DatabaseSeeder`).

Database: PostgreSQL `phb_borneo` (container Docker `postgis`, port 5432, user `docker`).

## Struktur

| Bagian | Lokasi |
| --- | --- |
| Route user & CMS | `routes/web.php` |
| Controller (hanya `return view`) | `app/Http/Controllers/UserController.php`, `CmsController.php`, `TinyUploadController.php` (upload gambar TinyMCE) |
| Middleware | `app/Http/Middleware/LanguageMiddleware.php`, `cmslogin.php` (cek `session('account_id')` + role) |
| Komponen Livewire user | `app/Livewire/User/*` → `resources/views/livewire/user/*` |
| Komponen Livewire CMS | `app/Livewire/Cms/*` → `resources/views/livewire/cms/*` |
| Halaman (extends layout + `<livewire:...>`) | `resources/views/user/*`, `resources/views/cms/*` |
| Layout | `resources/views/layouts/user.blade.php`, `admin.blade.php`, `login.blade.php` |
| Navbar / footer / kartu | `resources/views/components/user/*` |
| Editor TinyMCE | `resources/views/components/tinymce.blade.php` (`<x-tinymce id="..." model="..." />`) |
| Kategori konten | `config/phb.php` |
| Teks UI bahasa Indonesia | `lang/id.json` |
| Helper view | `app/helpers.php` (`tr($row, 'title')`, `storage_url()`, `konten_url()`) |

### Tabel

| Tabel | Isi |
| --- | --- |
| `accounts` | akun CMS (`super_admin` / `admin`) |
| `setting` | 1 baris: foto hero, kutipan Beranda, deskripsi footer |
| `about` | 1 baris: Who We Are (Arti Logo, Visi, Misi, gambar struktur) |
| `konten` | Our Work (`photo`), Resources, Event, Blog — dibedakan kolom `kategori` |
| `images` | gambar yang diunggah lewat TinyMCE |

### Satuan desain

Tampilan desktop (`xl:`, ≥1280px) adalah replika PDF: `1u = 1px` di layar 1920px dan mengecil proporsional.
Utility `w-u-[760]`, `text-u-[29.4]`, `left-u-[141]`, dst. didefinisikan dengan `@utility` di `resources/css/app.css`,
`--u` dihitung oleh JS (`resources/js/app.js`).
