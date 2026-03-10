<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticleImagesSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 20; $i++) {
            DB::table('particles')->insert([
                'image_path' => $i . '.png',
                'active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}