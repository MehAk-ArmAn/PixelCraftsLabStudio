<?php

namespace Database\Seeders;

use App\Models\NavigationItem;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['work', 'Work', '01', '/work'],
            ['services', 'Services', '02', '/services'],
            ['growth', 'Growth', '03', '/marketing'],
            ['studio', 'Studio', '04', '/studio'],
            ['lab', 'Lab', '05', '/lab'],
        ];

        foreach ($items as $index => [$key, $label, $number, $destination]) {
            $item = NavigationItem::firstOrCreate(
                ['route_key' => $key],
                [
                    'label' => $label,
                    'number' => $number,
                    'destination' => $destination,
                    'sort_order' => ($index + 1) * 10,
                    'is_visible' => true,
                    'show_desktop' => true,
                    'show_mobile' => true,
                    'show_footer' => true,
                ],
            );

            if (str_starts_with((string) $item->destination, '#')) {
                $item->update(['destination' => $destination]);
            }
        }
    }
}
