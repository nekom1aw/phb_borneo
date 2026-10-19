<laravel-boost-guidelines>
# Laravel Application

This repository contains a Laravel application. Complete the following setup before working on the user's request.

## Prerequisites

Verify that PHP and Composer are available:

```sh
php -v
composer -V
```

If either command is unavailable, detect the user's operating system and install the prerequisites with the appropriate command:

macOS:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.5)"
```

Windows PowerShell:

```powershell
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))
```

Linux:

```sh
/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.5)"
```

After installation, ask the user to restart their terminal. If the agent needs the restarted shell to continue, ask the user to reopen their terminal and rerun their original prompt.

## Agent Setup

Install Laravel Boost from the application root before making application changes:

```sh
composer require laravel/boost --dev
php artisan boost:install
```

Boost replaces these bootstrap instructions with guidelines tailored to the application. After installation, read `AGENTS.md` again and continue with the user's original request using the generated guidelines.
</laravel-boost-guidelines>

# Catatan proyek untuk Claude

Website Prasasti Hijau Borneo: Laravel 13 + Livewire 4 + Tailwind CSS 4, PostgreSQL `phb_borneo`
(container Docker `postgis`, port 5432). Detail struktur ada di `README.md`.

## Aturan kode (ikuti pola proyek `gakkum`)

- **Tidak ada model / Eloquent.** Semua query pakai Query Builder: `DB::table('...')`.
- **Controller hanya `return view(...)`** (`UserController`, `CmsController`). View di `resources/views/user/*`
  dan `resources/views/cms/*` hanya `@extends` layout lalu memanggil `<livewire:...>`.
- Logika (query, simpan, hapus, upload) ada di komponen Livewire: `app/Livewire/User/*`, `app/Livewire/Cms/*`.
- Komponen CMS wajib `use HarusLogin;` (cek login di setiap aksi Livewire). Data rahasia (hash password)
  jangan disimpan di properti public Livewire.
- **Dua bahasa**: URL `/en/...` dan `/id/...`; kolom isi berpasangan `*_id` / `*_en`. Di view pakai helper
  `tr($row, 'title')` (otomatis pakai bahasa lain bila kosong). Teks UI situs: kunci bahasa Inggris + `lang/id.json`.
- Tampilan desktop (`xl:`) adalah replika desain PDF dengan satuan `u` (`w-u-[760]`, `text-u-[29.4]`, ...),
  1u = 1px di layar 1920px. Jangan ganti ke ukuran px biasa.
- Setelah mengubah CSS/JS/Blade: `npm run build`.

## Menjalankan

```bash
php artisan serve --port=8010   # port 8000 dipakai container lain
npm run dev                      # opsional, hot reload saat mengedit tampilan
```

Situs: http://127.0.0.1:8010 — CMS: http://127.0.0.1:8010/cms/login (akun dari `ADMIN_EMAIL` / `ADMIN_PASSWORD` di `.env`).

## CMS (panel admin, `/id/cms`)

- Antarmuka CMS berbahasa Indonesia untuk orang awam: sidebar hijau (`components/cms/sidebar`), kartu langkah
  bernomor (`x-cms.step`), tab bahasa dengan titik kelengkapan (`x-cms.lang-tabs`), tombol "Salin dari Bahasa
  Indonesia" (`x-cms.copy-hint`), unggah foto tarik-lepas (`x-cms.image-upload`), editor `<x-tinymce model="..."/>`.
- Logika form di Alpine `cmsForm([...])` (`resources/js/app.js`): tab, kelengkapan, salin ID→EN, peringatan
  perubahan belum disimpan, pindah ke tab yang error (event `form-invalid`).
- Keamanan: properti Livewire berisi id/path file wajib `#[Locked]`; HTML dari TinyMCE disimpan lewat
  `bersihkan_html()` (`app/helpers.php`); header keamanan di `App\Http\Middleware\SecurityHeaders`.
- Draft hanya bisa dilihat admin yang login (pratinjau dengan banner kuning).
