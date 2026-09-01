<?php

namespace Database\Seeders;

use App\Services\MediaLibraryService;
use Illuminate\Database\Seeder;

/**
 * Registers the assets already sitting in public/ so admins can pick them in
 * the media library. Nothing is moved or renamed — existing paths keep working.
 */
class MediaSeeder extends Seeder
{
    public function run(): void
    {
        app(MediaLibraryService::class)->importLegacyAssets(['assets', 'uploads']);
    }
}
