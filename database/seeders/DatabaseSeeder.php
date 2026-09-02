<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seeds the CMS with the exact content the locked design used to hard-code,
     * plus the marketing and growth offering. Every seeder is idempotent — it
     * creates missing rows and never overwrites an admin's edits.
     *
     * No admin account is created here on purpose: run `php artisan pcl:admin`.
     */
    public function run(): void
    {
        $this->call([
            SiteSettingsSeeder::class,
            MarketingChannelSeeder::class,
            NavigationSeeder::class,
            ProcessStageSeeder::class,
            ServiceSeeder::class,
            ProjectSeeder::class,
            TeamSeeder::class,
            SocialLinkSeeder::class,
            GrowthPlanSeeder::class,
            PackageSeeder::class,
            ContactOptionSeeder::class,
            PageContentSeeder::class,
            MediaSeeder::class,
        ]);
    }
}
