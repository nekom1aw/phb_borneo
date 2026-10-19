<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Satu baris: isi Beranda (hero + kutipan) dan footer
    public function up(): void
    {
        Schema::create('setting', function (Blueprint $table) {
            $table->id();

            $table->string('hero_home')->nullable();   // foto hero Beranda
            $table->string('hero_page')->nullable();   // foto hero halaman lain

            $table->string('quote_id')->nullable();
            $table->string('quote_en')->nullable();

            $table->text('footer_id')->nullable();
            $table->text('footer_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
};
