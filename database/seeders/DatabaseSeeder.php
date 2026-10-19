<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi awal: akun CMS, Beranda, Who We Are, dan contoh konten sesuai desain.
     */
    public function run(): void
    {
        $now = now();

        // gambar contoh -> storage/app/public/seed
        foreach (['hero-home.jpg', 'hero.jpg', 'report.jpg', 'struktur-kepengurusan.jpg'] as $file) {
            Storage::disk('public')->put('seed/' . $file, file_get_contents(__DIR__ . '/images/' . $file));
        }

        // akun
        DB::table('accounts')->updateOrInsert(
            ['email' => env('ADMIN_EMAIL', 'admin@phb.test')],
            [
                'nama' => 'Super Admin',
                'password' => Hash::make(env('ADMIN_PASSWORD') ?: Str::random(16)),
                'role' => 'super_admin',
                'status' => 'Y',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $title = "Financing Sustainable Palm Oil in\u{00A0}Indonesia (2026)";
        $titleId = "Pembiayaan Sawit Berkelanjutan di\u{00A0}Indonesia (2026)";
        $text = 'Financing Sustainable Palm Oil in Indonesia (2026)Ferferum quosam, nonsequ atendes il ma a pores ressimi nisquat quia corio velita cus ario.';
        $long = $text . 'Um rest utatem fuga. Obis ratiur maxim explisquis et am faciis ulla con conseca borion rempore prepernam venimag nihitae sape ni ventur aut quo conesse quunt, eatiusae aborerum nimpe';

        $content = implode("\n", [
            '<p>' . $text . 'Lorem eium eicipsant ut officim ollitam fugitatius ab in placea quia sum quae porecus es volupta temqui accae prescipsam serit et harum illupta sperum, nonempo repudaectio dolum quiat.</p>',
            '<p>Incient audis et et pre, am et, qui occaborum, serum elluptati am veliqui cor secatiatiis diti reris is eos doluptas dolor rendae por ad ullam quiantibus reperei ciusant explant litaes dolo tem que sa nosti ut eoste consequodio idellaut quiame et et eium quiatur? Odignimagnia vendis as sum sequaspit quid mod et voloribus alique venisto tem acereiuris sunt quod et, ipis apedis sinihit vellanditiis perspellaute voluptat quibus exceperro maio od qui odignis ditatquo quod moluptas essimi, ulpa derfere, ut elest volor maximusdam qui nobitae riorrun tinctus corum volorunt.</p>',
            '<p>Ipsunde bitiur aut aut eume rehenti vel et pe aut molorempos ipis sim erro dus mos aliquam volorum cuptatibus, quiatio nsedit audiscitatum quist, quam sectess enimus, qui bernate mporepta voloritatium facepeditem volupti stecatiisti doluptam id modi omnis inullaut por audam dit la veriasp erumquia dellab is aut faces dolore none quam inctae cone aut aut preium ut dolupta quae etur re el eum rersped ute doluptatur?</p>',
            '<p>Beatemp oribea desed ellorestibus dollis autassi comniam est, siminis num solut od ullam rendund isciati od quaestrum accusdae cone verovit plita simolup tiossus illor aceste paribusam, ipis sit rendello odignate mo is quiandeliquo ium volorro ereptae exeriti onempor eicipitat alitatur rem fugia doluptatus aut aute consectorio ex eiunt as nonecest, omnisci ut quam excestio opta nis repe niasi blaboriones nonsequi suntem reptaep tiumque sum hariae cum ium fugiam, qui debis rereper umquam que cus seque rem que rae volo dit quatur.</p>',
        ]);

        // beranda + footer
        DB::table('setting')->truncate();
        DB::table('setting')->insert([
            'hero_home' => 'seed/hero-home.jpg',
            'hero_page' => 'seed/hero.jpg',
            'quote_id' => "Kami berpikir, kami\nmasuk belantara.",
            'quote_en' => "Kami berpikir, kami\nmasuk belantara.",
            'footer_id' => $text,
            'footer_en' => $text,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // who we are
        DB::table('about')->truncate();
        DB::table('about')->insert([
            'logo_title_id' => str_replace("\u{00A0}", ' ', $titleId),
            'logo_title_en' => str_replace("\u{00A0}", ' ', $title),
            'logo_content_id' => '<p>' . $long . '</p>',
            'logo_content_en' => '<p>' . $long . '</p>',
            'visi_title_id' => str_replace("\u{00A0}", ' ', $titleId),
            'visi_title_en' => str_replace("\u{00A0}", ' ', $title),
            'visi_content_id' => '<p>' . $long . '</p>',
            'visi_content_en' => '<p>' . $long . '</p>',
            'misi_title_id' => str_replace("\u{00A0}", ' ', $titleId),
            'misi_title_en' => str_replace("\u{00A0}", ' ', $title),
            'misi_content_id' => '<p>' . $long . '</p>',
            'misi_content_en' => '<p>' . $long . '</p>',
            'struktur_image' => 'seed/struktur-kepengurusan.jpg',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // resources, event, our work (photo), blog: 7 contoh per kategori
        DB::table('konten')->truncate();
        foreach (['resources', 'event', 'photo', 'blog'] as $kategori) {
            for ($i = 0; $i < 7; $i++) {
                $en = $kategori === 'event' && $i === 0 ? 'Nama acaranya Nama acaranya Nama acaranya' : $title;
                $id = $kategori === 'event' && $i === 0 ? 'Nama acaranya Nama acaranya Nama acaranya' : $titleId;

                DB::table('konten')->insert([
                    'kategori' => $kategori,
                    'slug' => Str::slug($en),
                    'tanggal' => $now->copy()->subDays($i * 7)->toDateString(),
                    'image' => 'seed/report.jpg',
                    'title_id' => $id,
                    'title_en' => $en,
                    'deskripsi_id' => $text,
                    'deskripsi_en' => $text,
                    'content_id' => $content,
                    'content_en' => $content,
                    'status' => 'publish',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
