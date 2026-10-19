{{-- Hero foto untuk halaman selain Beranda --}}
@props(['src' => null])

<section class="relative">
    <img src="{{ $src ?: asset('storage/seed/hero.jpg') }}" alt="Hamparan hutan Kalimantan berkabut saat fajar"
         class="block w-full h-[40vh] object-cover object-center xl:h-auto">
</section>
