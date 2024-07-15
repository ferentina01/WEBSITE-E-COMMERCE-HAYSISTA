<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $province = [
            ['code' => 'AC', 'name' => 'Aceh'],
            ['code' => 'SU', 'name' => 'Sumatera Utara'],
            ['code' => 'SB', 'name' => 'Sumatera Barat'],
            ['code' => 'RI', 'name' => 'Riau'],
            ['code' => 'KR', 'name' => 'Kepulauan Riau'],
            ['code' => 'JA', 'name' => 'Jambi'],
            ['code' => 'SS', 'name' => 'Sumatera Selatan'],
            ['code' => 'BB', 'name' => 'Bangka Belitung'],
            ['code' => 'BE', 'name' => 'Bengkulu'],
            ['code' => 'LA', 'name' => 'Lampung'],
            ['code' => 'JK', 'name' => 'DKI Jakarta'],
            ['code' => 'JB', 'name' => 'Jawa Barat'],
            ['code' => 'JT', 'name' => 'Jawa Tengah'],
            ['code' => 'JI', 'name' => 'Jawa Timur'],
            ['code' => 'YO', 'name' => 'DI Yogyakarta'],
            ['code' => 'BA', 'name' => 'Bali'],
            ['code' => 'NB', 'name' => 'Nusa Tenggara Barat'],
            ['code' => 'NT', 'name' => 'Nusa Tenggara Timur'],
            ['code' => 'KB', 'name' => 'Kalimantan Barat'],
            ['code' => 'KT', 'name' => 'Kalimantan Tengah'],
            ['code' => 'KI', 'name' => 'Kalimantan Timur'],
            ['code' => 'KS', 'name' => 'Kalimantan Selatan'],
            ['code' => 'KU', 'name' => 'Kalimantan Utara'],
            ['code' => 'SA', 'name' => 'Sulawesi Utara'],
            ['code' => 'ST', 'name' => 'Sulawesi Tengah'],
            ['code' => 'SG', 'name' => 'Sulawesi Tenggara'],
            ['code' => 'SR', 'name' => 'Sulawesi Barat'],
            ['code' => 'SN', 'name' => 'Sulawesi Selatan'],
            ['code' => 'GO', 'name' => 'Gorontalo'],
            ['code' => 'MA', 'name' => 'Maluku'],
            ['code' => 'MU', 'name' => 'Maluku Utara'],
            ['code' => 'PA', 'name' => 'Papua'],
            ['code' => 'PB', 'name' => 'Papua Barat'],
        ];

        // Insert data using DB facade
        DB::table('province')->insert($province);
    }
    
}
