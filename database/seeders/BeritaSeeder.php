<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Berita;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beritas = [
            [
                'judul' => 'Pendaftaran Siswa Baru (PPDB) 2026/2027 Dibuka!',
                'slug' => Str::slug('Pendaftaran Siswa Baru (PPDB) 2026/2027 Dibuka!'),
                'kategori' => 'Informasi Sekolah',
                'gambar' => 'ppdb-2026.jpg',
                'desc' => 'MTsN 1 Paluta resmi membuka pendaftaran peserta didik baru tahun ajaran 2026/2027. Segera daftar melalui website resmi kami.',
                'konten' => '<p>Kegiatan Penerimaan Peserta Didik Baru (PPDB) di MTsN 1 Paluta telah resmi dimulai. Calon siswa diharapkan untuk menyiapkan berkas administratif yang diperlukan.<\/p><p>Pendaftaran dilakukan sepenuhnya secara online untuk memudahkan orang tua dan wali murid. Pastikan data yang dimasukkan sudah benar dan valid.<\/p>',
            ],
            [
                'judul' => 'Juara 1 Kompetisi Sains Madrasah (KSM) Tingkat Provinsi',
                'slug' => Str::slug('Juara 1 Kompetisi Sains Madrasah (KSM) Tingkat Provinsi'),
                'kategori' => 'Prestasi',
                'gambar' => 'ksm-juara.jpg',
                'desc' => 'Siswa kami berhasil meraih medali emas dalam ajang KSM tingkat provinsi di bidang Matematika Terintegrasi.',
                'konten' => '<p>Prestasi membanggakan kembali diraih oleh keluarga besar MTsN 1 Paluta. Ananda Ahmad Syarif berhasil membawa pulang medali emas dari ajang bergengsi KSM.<\/p><p>Kepala Madrasah menyampaikan apresiasi setinggi-tingginya kepada ananda dan para guru pembimbing yang telah bekerja keras.<\/p>',
            ],
            [
                'judul' => 'Kegiatan Bakti Sosial Ramadhan 1447 H',
                'slug' => Str::slug('Kegiatan Bakti Sosial Ramadhan 1447 H'),
                'kategori' => 'Kegiatan Keagamaan',
                'gambar' => 'baksos-ramadhan.jpg',
                'desc' => 'OSIS MTsN 1 Paluta mengadakan pembagian paket sembako untuk masyarakat kurang mampu di lingkungan sekitar sekolah.',
                'konten' => '<p>Bulan suci Ramadhan menjadi momentum untuk meningkatkan kepedulian sosial. Siswa-siswi yang tergabung dalam OSIS berinisiatif mengumpulkan donasi.<\/p><p>Hasil donasi tersebut kemudian disalurkan dalam bentuk paket bahan pokok kepada warga yang membutuhkan.<\/p>',
            ],
        ];

        foreach ($beritas as $berita) {
            Berita::create($berita);
        }
    }
}
