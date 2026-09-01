<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        $links = [
            ['Instagram', 'https://www.instagram.com/pixel_crafts_lab_studio?igsh=dzQzczA4aDUyaGYz'],
            ['LinkedIn', 'http://linkedin.com/in/pixelcraftslab-studio-46364139b'],
            ['X', 'https://x.com/PixelCraftsLab'],
            ['TikTok', 'http://tiktok.com/@pixel_crafts_lab_studio'],
            ['Pinterest', 'https://pin.it/7maousVqc'],
            ['Facebook', 'https://facebook.com/profile.php?id=61584760521477'],
            ['YouTube', 'https://www.youtube.com/@PIXELCRAFTSLABSTUDIO'],
            ['WhatsApp', 'https://www.whatsapp.com/channel/0029Vb7gHBr9Bb5revqEZz1W'],
        ];

        foreach ($links as $index => [$platform, $url]) {
            SocialLink::firstOrCreate(
                ['slug' => Str::slug($platform)],
                [
                    'platform' => $platform,
                    'url' => $url,
                    'label' => $platform,
                    'sort_order' => ($index + 1) * 10,
                    'is_enabled' => true,
                ],
            );
        }
    }
}
