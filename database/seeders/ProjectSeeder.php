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
                    'initials' => $this->initials($row['name']),
                    'primary_tint' => $tint,
                    'secondary_tint' => $tint2,
                    'published_at' => now(),
                ],
            );
        }
    }

    private function initials(string $name): string
    {
        return collect(explode(' ', (string) preg_replace('/[^A-Za-z ]/', '', $name)))
            ->filter()
            ->take(2)
            ->map(fn ($w) => strtoupper($w[0]))
            ->implode('');
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
