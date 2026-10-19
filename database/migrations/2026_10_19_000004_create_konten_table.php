<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Resources, Event, Our Work (photo) dan Blog dalam satu tabel, dibedakan kolom kategori
    public function up(): void
    {
        Schema::create('konten', function (Blueprint $table) {
            $table->id();

            $table->enum('kategori', [
                'resources',
                'event',
                'photo',
                'blog',
            ]);

            $table->string('slug')->nullable();
            $table->date('tanggal')->nullable();

            $table->string('image')->nullable();
            $table->string('file')->nullable();   // PDF untuk resources

            $table->string('title_id')->nullable();
            $table->string('title_en')->nullable();

            $table->text('deskripsi_id')->nullable();
            $table->text('deskripsi_en')->nullable();

            $table->longText('content_id')->nullable();
            $table->longText('content_en')->nullable();

            $table->enum('status', [
                'draft',
                'publish',
            ])->default('draft');

            $table->timestamps();

            $table->index(['kategori', 'status', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
