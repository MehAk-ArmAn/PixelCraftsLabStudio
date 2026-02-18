<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Service::create([
            'title' => 'Website Development',
            'description' => 'Modern responsive websites'
        ]);

        Service::create([
            'title' => 'UI/UX Design',
            'description' => 'Clean and user-friendly designs'
        ]);

        Service::create([
            'title' => 'Brand Identity',
            'description' => 'Logos and branding packages'
        ]);

        Service::create([
            'title' => 'Maintenance & Support',
            'description' => 'Ongoing updates and fixes'
        ]);
    }
}
