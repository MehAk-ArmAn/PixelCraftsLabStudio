<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['work', 'Work', '01'],
            ['services', 'Services', '02'],
            ['growth', 'Growth', '03'],
            ['studio', 'Studio', '04'],
            ['lab', 'Lab', '05'],
        ];

        foreach ($items as $index => [$key, $label, $number]) {
            NavigationItem::firstOrCreate(
                ['route_key' => $key],
                [
                    'label' => $label,
                    'number' => $number,
                    'destination' => '#'.$key,
                    'sort_order' => ($index + 1) * 10,
                    'is_visible' => true,
                    'show_desktop' => true,
                    'show_mobile' => true,
                    'show_footer' => true,
                ],
            );
        }
    }
}
