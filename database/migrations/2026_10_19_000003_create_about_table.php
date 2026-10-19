<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Satu baris: halaman Who We Are (Arti Logo, Visi, Misi, Struktur Kepengurusan)
    public function up(): void
    {
        Schema::create('about', function (Blueprint $table) {
            $table->id();

            $table->string('logo_title_id')->nullable();
            $table->string('logo_title_en')->nullable();
            $table->longText('logo_content_id')->nullable();
            $table->longText('logo_content_en')->nullable();

            $table->string('visi_title_id')->nullable();
            $table->string('visi_title_en')->nullable();
            $table->longText('visi_content_id')->nullable();
            $table->longText('visi_content_en')->nullable();

            $table->string('misi_title_id')->nullable();
            $table->string('misi_title_en')->nullable();
            $table->longText('misi_content_id')->nullable();
            $table->longText('misi_content_en')->nullable();

            $table->string('struktur_image')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about');
    }
};
