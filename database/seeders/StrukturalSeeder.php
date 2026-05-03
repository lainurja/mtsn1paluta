<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Struktural;

class StrukturalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = [
            // Struktural
            [
                'nama' => 'H. Bambang Setiawan, S.Ag., M.Pd.',
                'jabatan' => 'Kepala Madrasah',
                'kategori' => 'struktural',
                'urutan' => 1,
            ],
            [
                'nama' => 'Siti Aminah, S.Pd.I.',
                'jabatan' => 'Kepala Tata Usaha',
                'kategori' => 'struktural',
                'urutan' => 2,
            ],
            // Wali Kelas
            [
                'nama' => 'Drs. Ahmad Fauzi, M.Pd.',
                'jabatan' => 'Wali Kelas 9A',
                'kategori' => 'walas',
                'urutan' => 1,
            ],
            [
                'nama' => 'Nur Aini, S.Pd., M.Pd.',
                'jabatan' => 'Wali Kelas 7G',
                'kategori' => 'walas',
                'urutan' => 2,
            ],
            // Guru Mata Pelajaran
            [
                'nama' => 'Budi Santoso, S.Si.',
                'jabatan' => 'Guru Matematika',
                'kategori' => 'guru',
                'urutan' => 1,
            ],
            [
                'nama' => 'Dewi Sartika, S.S.',
                'jabatan' => 'Guru Bahasa Inggris',
                'kategori' => 'guru',
                'urutan' => 2,
            ],
        ];

        foreach ($staff as $item) {
            Struktural::create($item);
        }
    }
}
