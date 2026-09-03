<?php

namespace Database\Seeders;

use App\Models\HomepageFeaturedProject;
use App\Models\Project;
use Illuminate\Database\Seeder;

class HomepageFeaturedProjectSeeder extends Seeder
{
    public function run(): void
    {
        if (HomepageFeaturedProject::query()->exists()) {
            return;
        }

        foreach (['bangtan', 'studybuddy', 'alphablock'] as $index => $slug) {
            $project = Project::query()->where('slug', $slug)->first();

            if (! $project) {
                continue;
            }

            HomepageFeaturedProject::create([
                'project_id' => $project->id,
                'slot' => $index + 1,
                'sort_order' => ($index + 1) * 10,
                'is_primary' => $index === 0,
                'enabled' => true,
                'display_mode' => 'auto',
                'media_mode' => 'auto',
            ]);
        }
    }
}
