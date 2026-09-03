<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * The exact PROJECTS constant from the locked design document, so the first
 * CMS-driven render is identical to the hard-coded one.
 */
class ProjectSeeder extends Seeder
{
    /** Same palette the frontend used to assign per-index. */
    private const TINTS = [
        ['#5B2394', '#8B45FF'],
        ['#0D0B12', '#3A3346'],
        ['#FF5F1F', '#F2894F'],
        ['#8B45FF', '#FF5F1F'],
        ['#3A3346', '#5B2394'],
        ['#FF5F1F', '#5B2394'],
    ];

    public function run(): void
    {
        foreach ($this->projects() as $index => $row) {
            [$tint, $tint2] = self::TINTS[$index % count(self::TINTS)];
            $media = $this->presentationMedia()[$row['slug']] ?? [];

            Project::firstOrCreate(
                ['slug' => $row['slug']],
                [
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'kind' => $row['kind'],
                    'platform' => $row['platform'],
                    'layout_size' => $row['size'],
                    'short_description' => $row['short'],
                    'full_description' => $row['blurb'],
                    'external_url' => $row['link'] ?: null,
                    'status' => Project::STATUS_PUBLISHED,
                    'is_published' => true,
                    'is_featured' => $index < 3,
                    'is_archived' => false,
                    'sort_order' => ($index + 1) * 10,
                    'primary_image' => $media['hero'] ?? null,
                    'icon_image' => $media['icon'] ?? null,
                    'feature_image' => $media['feature'] ?? null,
                    'gallery' => $media['gallery'] ?? [],
                    'is_ecosystem_head' => $row['slug'] === 'studybuddy',
                    'primary_tint' => $tint,
                    'secondary_tint' => $tint2,
                    'published_at' => now(),
                ],
            );
        }
    }

    /** @return array<string, array<string, mixed>> */
    private function presentationMedia(): array
    {
        $base = 'assets/projects/';

        return [
            'fikar' => ['hero' => $base.'fikar/hero.webp', 'icon' => $base.'fikar/icon.webp', 'feature' => $base.'fikar/feature-01.webp', 'gallery' => [$base.'fikar/feature-01.webp', $base.'fikar/mobile-01.webp']],
            'abandoned' => ['hero' => $base.'abandoned/hero.webp', 'icon' => $base.'abandoned/icon.webp', 'gallery' => [$base.'abandoned/screen-01.webp', $base.'abandoned/screen-02.webp']],
            'farmcare' => ['hero' => $base.'farmcare/hero.webp', 'icon' => $base.'farmcare/icon.webp', 'feature' => $base.'farmcare/feature-01.webp', 'gallery' => [$base.'farmcare/feature-01.webp', $base.'farmcare/mobile-01.webp']],
            'studybuddy' => ['hero' => $base.'studybuddy/hero.webp', 'icon' => $base.'studybuddy/icon.webp', 'feature' => $base.'studybuddy/feature-01.webp', 'gallery' => [$base.'studybuddy/feature-01.webp', $base.'studybuddy/mobile-01.webp']],
            'bangtan' => ['hero' => $base.'bangtan/hero.webp', 'icon' => $base.'bangtan/icon.webp', 'feature' => $base.'bangtan/feature-01.webp', 'gallery' => [$base.'bangtan/feature-01.webp', $base.'bangtan/mobile-01.webp']],
            'matchmallow' => ['hero' => $base.'matchmallow/hero.webp', 'icon' => $base.'matchmallow/icon.webp', 'gallery' => [$base.'matchmallow/screen-01.webp', $base.'matchmallow/screen-02.webp', $base.'matchmallow/screen-03.webp']],
            'coloriboo' => ['hero' => $base.'coloriboo/hero.webp', 'icon' => $base.'coloriboo/icon.webp', 'gallery' => [$base.'coloriboo/screen-01.webp', $base.'coloriboo/screen-02.webp']],
            'mathibble' => ['hero' => $base.'mathibble/hero.webp', 'icon' => $base.'mathibble/icon.webp', 'gallery' => [$base.'mathibble/screen-01.webp', $base.'mathibble/screen-02.webp']],
            'animal' => ['hero' => $base.'animal/hero.webp', 'icon' => $base.'animal/icon.webp', 'gallery' => [$base.'animal/screen-01.webp', $base.'animal/screen-02.webp']],
            'bloxabet' => ['hero' => $base.'bloxabet/hero.webp', 'icon' => $base.'bloxabet/icon.webp', 'gallery' => [$base.'bloxabet/screen-01.webp', $base.'bloxabet/screen-02.webp']],
            'globepop' => ['hero' => $base.'globepop/hero.webp', 'icon' => $base.'globepop/icon.webp', 'gallery' => [$base.'globepop/screen-01.webp', $base.'globepop/screen-02.webp']],
            'alphablock' => ['hero' => $base.'alphablock/hero.webp', 'icon' => $base.'alphablock/icon.webp', 'feature' => $base.'alphablock/feature-01.webp', 'gallery' => [$base.'alphablock/feature-01.webp', $base.'alphablock/mobile-01.webp']],
            'pulse' => ['hero' => $base.'pulse/hero.webp', 'icon' => $base.'pulse/icon.webp', 'gallery' => []],
        ];
    }

    /** @return list<array<string, string>> */
    private function projects(): array
    {
        return [
            [
                'slug' => 'fikar', 'name' => 'Fikar-e-Adab', 'category' => 'Web',
                'kind' => 'Web platform', 'platform' => 'Web', 'size' => 'wide',
                'short' => 'An Urdu-first literary world for poetry, audio and novels.',
                'blurb' => 'Fikar-e-Adab is an Urdu-first literary world for poetry, audio, novels and creators. Read, listen, learn, support writers, collect Pearls and build your own visible Personal Library.',
                'link' => 'https://fikareadab.com/',
            ],
            [
                'slug' => 'abandoned', 'name' => 'Abandoned City: Zombie Attack', 'category' => 'Games',
                'kind' => 'Game', 'platform' => 'Google Play', 'size' => 'tall',
                'short' => 'A first-person survival shooter with bosses, vehicles and maps.',
                'blurb' => 'Abandoned City: Zombie Survival is a first-person survival shooter packed with weapons, bosses, vehicles, and multiple maps.',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.abandonedcity&hl=en',
            ],
            [
                'slug' => 'farmcare', 'name' => 'The Farm Care', 'category' => 'Web',
                'kind' => 'Website', 'platform' => 'Web', 'size' => 'std',
                'short' => 'Veterinary equipment and animal nutrition, built for buyers.',
                'blurb' => 'The Farm Care is a trusted name in veterinary equipment and animal nutrition solutions, proudly based in Sialkot, Pakistan. Their focus is on quality, reliability, and practical products for farms, clinics, distributors, and international buyers.',
                'link' => 'https://thefarmcare.com/',
            ],
            [
                'slug' => 'studybuddy', 'name' => 'Study Buddy', 'category' => 'Web',
                'kind' => 'Web platform', 'platform' => 'Web', 'size' => 'wide',
                'short' => 'A gamified peer-learning concept for homework and test prep.',
                'blurb' => 'Study buddy is a playful peer learning or gamified study tool concept designed to make homework and test prep less lonely and way more entertaining.',
                'link' => 'https://www.studybuddy.fun/',
            ],
            [
                'slug' => 'bangtan', 'name' => 'BangTan', 'category' => 'Web',
                'kind' => 'Fan platform', 'platform' => 'Web', 'size' => 'std',
                'short' => 'A fan-made BTS hub with vaults, quizzes and a leaderboard.',
                'blurb' => 'A fan-made BTS hub where ARMY can learn, explore member vaults, take quizzes, earn points, unlock profile upgrades, and climb the leaderboard.',
                'link' => 'https://bangtan.info/',
            ],
            [
                'slug' => 'matchmallow', 'name' => 'MatchMallow: Memory Match', 'category' => 'Games',
                'kind' => 'Game', 'platform' => 'Google Play', 'size' => 'std',
                'short' => 'Match animal pairs, build streaks, beat your best score.',
                'blurb' => 'Match cute animal pairs, build streaks, and beat your best score with Mallow!',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.matchmallow',
            ],
            [
                'slug' => 'coloriboo', 'name' => 'Coloriboo: Learn Colors', 'category' => 'Apps',
                'kind' => 'Kids app', 'platform' => 'Google Play', 'size' => 'std',
                'short' => 'Pop, play and learn colors with Boo.',
                'blurb' => 'Pop, play and learn colors with Boo through fun, magical mini-games.',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.coloriboo',
            ],
            [
                'slug' => 'mathibble', 'name' => 'Mathibble: Kids Math Games', 'category' => 'Apps',
                'kind' => 'Kids app', 'platform' => 'Google Play', 'size' => 'std',
                'short' => 'Visual math questions, friendly feedback and rewards.',
                'blurb' => 'Fun math games for kids with visual questions, friendly feedback and rewards.',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.mathibble',
            ],
            [
                'slug' => 'animal', 'name' => 'Animal Adventure: Learn & Play', 'category' => 'Apps',
                'kind' => 'Kids app', 'platform' => 'Google Play', 'size' => 'std',
                'short' => 'Meet animals, learn their names, discover their sounds.',
                'blurb' => 'Meet colorful animals, learn their names and discover their fun sounds!',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.animalkingdom',
            ],
            [
                'slug' => 'bloxabet', 'name' => 'Bloxabet: Learn ABC', 'category' => 'Apps',
                'kind' => 'Kids app', 'platform' => 'Google Play', 'size' => 'wide',
                'short' => 'An interactive alphabet adventure in colorful letter blocks.',
                'blurb' => 'Bloxabet: Learn ABC is a vibrant, interactive alphabet adventure where toddlers can tap, listen, and play their way from A to Z using colorful letter blocks and friendly animations!',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.abclearning',
            ],
            [
                'slug' => 'globepop', 'name' => 'GlobePop: Learn Flags', 'category' => 'Apps',
                'kind' => 'Kids app', 'platform' => 'Google Play', 'size' => 'std',
                'short' => 'Learn world flags through quizzes with Orbit.',
                'blurb' => 'Learn world flags through fun quizzes with Orbit, clues, sounds and rewards.',
                'link' => 'https://play.google.com/store/apps/details?id=com.pixelcraftslab.flagquest',
            ],
            [
                'slug' => 'alphablock', 'name' => 'Alpha Block Solutions', 'category' => 'Lab',
                'kind' => 'Coming soon', 'platform' => 'In build', 'size' => 'std',
                'short' => 'Secure platforms, trading intelligence and automation tools.',
                'blurb' => 'ABS builds secure digital platforms, trading-intelligence systems and automation tools for founders, teams and investors who want to move from idea to working product.',
                'link' => 'https://alphablocksolutions.com/',
            ],
            [
                'slug' => 'pulse', 'name' => 'Pulse', 'category' => 'Lab',
                'kind' => 'Coming soon', 'platform' => 'In build', 'size' => 'std',
                'short' => 'AI-powered crypto trading signals and market intelligence.',
                'blurb' => 'AI-powered crypto trading signals and market intelligence.',
                'link' => '',
            ],
        ];
    }
}
