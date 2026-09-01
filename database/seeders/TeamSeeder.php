<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    private const TINTS = [
        ['#5B2394', '#8B45FF'],
        ['#0D0B12', '#3A3346'],
        ['#FF5F1F', '#F2894F'],
        ['#8B45FF', '#FF5F1F'],
    ];

    public function run(): void
    {
        $members = [
            [
                'name' => 'Mehak Arman',
                'role' => 'Founder, Creative Director & Digital Marketing Lead',
                'photo' => 'storage/team/22RPejylwi0liOaJqP23dw8rlzLSKGokLYFJyHqv.png',
                'bio' => 'Leads the vision, branding, and overall direction of PixelCraftsLabStudio. Oversees creative strategy, digital presence, and audience growth while ensuring every project aligns with a strong, modern brand identity.',
            ],
            [
                'name' => 'Sahil Arman',
                'role' => 'Lead Backend Engineer',
                'photo' => 'storage/team/Q4e0fZV365WfYaJYIAEIzb9S48InXC1Pfvd3XoAe.png',
                'bio' => 'Heads backend architecture and system development for web and application projects. Specializes in building scalable, secure, and high-performance systems.',
            ],
            [
                'name' => 'Hamdan Arman',
                'role' => 'Game Developer',
                'photo' => 'storage/team/QvhfCoo6xE9yDpcCoQASeXG4fLOGk7niVbsfqfXs.png',
                'bio' => 'Designs and develops interactive gameplay systems and immersive experiences. Focuses on performance optimization, game mechanics and engaging gaming solutions.',
            ],
            [
                'name' => 'Mobeen Bhalli',
                'role' => 'API & Integration Engineer',
                'photo' => 'storage/team/kSsegaCWHBpnOyx7W7eNWy1DGpJeBNpBd5Ax0PtD.png',
                'bio' => 'Handles API development and system integrations, enabling seamless communication between platforms and reliable backend connectivity.',
            ],
        ];

        foreach ($members as $index => $row) {
            [$tint, $tint2] = self::TINTS[$index % count(self::TINTS)];

            TeamMember::firstOrCreate(
                ['slug' => Str::slug($row['name'])],
                [
                    'name' => $row['name'],
                    'role' => $row['role'],
                    'bio' => $row['bio'],
                    'photo' => $row['photo'],
                    'initials' => collect(explode(' ', $row['name']))->map(fn ($w) => strtoupper($w[0]))->implode(''),
                    'primary_tint' => $tint,
                    'secondary_tint' => $tint2,
                    'sort_order' => ($index + 1) * 10,
                    'is_published' => true,
                ],
            );
        }
    }
}
