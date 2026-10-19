<?php

namespace App\Livewire\Cms\Concerns;

trait PesanValidasi
{
    protected function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_without' => 'Isi judul minimal dalam salah satu bahasa.',
            'uploaded' => ':attribute gagal diunggah. Pastikan ukurannya tidak terlalu besar.',
            'email' => ':attribute harus berupa email yang valid.',
            'unique' => ':attribute sudah dipakai.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute terlalu panjang/besar (maks :max).',
            'image' => ':attribute harus berupa gambar (jpg, png, webp).',
            'mimes' => ':attribute harus berformat :values.',
            'date' => ':attribute harus berupa tanggal.',
            'in' => ':attribute tidak valid.',
        ];
    }

    protected function validationAttributes()
    {
        return [
            'title_id' => 'Judul (ID)',
            'title_en' => 'Judul (EN)',
            'deskripsi_id' => 'Ringkasan (ID)',
            'deskripsi_en' => 'Ringkasan (EN)',
            'tanggal' => 'Tanggal',
            'image' => 'Gambar',
            'file' => 'File',
            'nama' => 'Nama',
            'email' => 'Email',
            'password' => 'Password',
        ];
    }
}
