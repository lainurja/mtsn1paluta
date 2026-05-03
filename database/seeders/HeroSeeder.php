<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Hero;

class HeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heroes = [
            [
                'judul' => 'Madrasah Unggul & Berkarakter',
                'tagline' => 'Mewujudkan generasi berilmu, berakhlak mulia, dan berprestasi.',
                'gambar' => 'tes.jpeg',
                'urutan' => 1,
            ],
            [
                'judul' => 'Madrasah Negeri Modern',
                'tagline' => 'Sekolah Islami Berbasis Teknologi.',
                'gambar' => 'tes.jpeg',
                'urutan' => 2,
            ],
            [
                'judul' => 'MTsN 1 Padang Lawas Utara',
                'tagline' => 'Menjadi madrasah pilihan utama masyarakat.',
                'gambar' => 'tes.jpeg',
                'urutan' => 3,
            ],
        ];

        foreach ($heroes as $hero) {
            Hero::create($hero);
        }
    }
}
