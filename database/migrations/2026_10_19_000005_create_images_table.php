<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Gambar yang diunggah lewat TinyMCE (dedup dengan hash)
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 64);
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('url');
            $table->string('original_name')->nullable();
            $table->string('mime')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();

            $table->unique(['hash', 'disk']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
