<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $cats = collect([
            ['Bencana', 'bencana', 'wave'],
            ['Pendidikan', 'pendidikan', 'book'],
            ['Kesehatan', 'kesehatan', 'heart'],
            ['Kemanusiaan', 'kemanusiaan', 'people'],
        ])->mapWithKeys(fn ($c) => [$c[1] => Category::create(['name' => $c[0], 'slug' => $c[1], 'icon' => $c[2]])]);

        $story = "Ceritakan kondisi penerima, kebutuhan, dan rencana penggunaan dana di sini.\n\nContoh data. Ganti dengan cerita asli yang sudah diverifikasi tim.";
        $rows = [
            ['bencana', 'Bantu korban banjir bandang di Demak', 'Tim Sahabat Peduli', 150000000, 92450000, 12, true],
            ['pendidikan', 'Beasiswa 50 anak yatim agar tetap sekolah', 'Yayasan Cahaya Ilmu', 80000000, 31200000, 40, false],
            ['kesehatan', 'Operasi jantung bayi Nayla, usia 8 bulan', 'Keluarga Nayla', 120000000, 74800000, 21, true],
            ['kemanusiaan', 'Pangan untuk lansia sebatang kara di Solo', 'Komunitas Peduli Solo', 25000000, 9300000, 30, false],
            ['pendidikan', 'Perpustakaan desa untuk 200 anak di Wonogiri', 'Karang Taruna Girimarto', 35000000, 21000000, 25, false],
            ['bencana', 'Rumah sementara untuk korban longsor Banjarnegara', 'Tim Sahabat Peduli', 200000000, 61000000, 18, true],
        ];
        foreach ($rows as [$cat, $title, $org, $target, $got, $days, $urgent]) {
            Campaign::create([
                'category_id' => $cats[$cat]->id, 'title' => $title, 'slug' => str($title)->slug(),
                'organizer' => $org, 'excerpt' => 'Satu donasi kecil dari banyak orang bisa mengubah hidup mereka.',
                'story' => $story, 'target' => $target, 'collected' => $got,
                'deadline' => now()->addDays($days), 'is_urgent' => $urgent, 'is_verified' => true,
            ]);
        }

        foreach (['Bagaimana dana donasi disalurkan', 'Kisah relawan di posko pengungsian', 'Cara memeriksa kampanye yang terpercaya'] as $t) {
            Article::create([
                'title' => $t, 'slug' => str($t)->slug(),
                'excerpt' => 'Ringkasan singkat artikel. Ganti dengan berita kemanusiaan asli.',
                'body' => 'Isi artikel.', 'published_at' => now()->subDays(rand(1, 10)),
            ]);
        }
    }
}
