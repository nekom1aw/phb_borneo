<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TinyUploadController extends Controller
{
    /**
     * POST /{locale}/cms/tinymce/upload
     * Respon untuk TinyMCE: { location: "<URL gambar>" }
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB
        ]);

        $file = $request->file('file');
        $disk = 'public';

        // file yang sama tidak disimpan dua kali
        $hash = hash_file('sha256', $file->getRealPath());

        $existing = DB::table('images')
            ->where('hash', $hash)
            ->where('disk', $disk)
            ->first();

        if ($existing) {
            return response()->json(['location' => $existing->url]);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = $hash . '.' . $ext;
        $path = 'uploads/tinymce/' . $filename;

        if (! Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->putFileAs('uploads/tinymce', $file, $filename);
        }

        // URL relatif agar tetap jalan walau domain berubah
        $url = '/storage/' . $path;

        DB::table('images')->insert([
            'hash' => $hash,
            'disk' => $disk,
            'path' => $path,
            'url' => $url,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['location' => $url]);
    }
}
