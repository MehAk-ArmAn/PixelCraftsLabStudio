<?php

namespace Database\Seeders;

use App\Models\InteractiveExperience;
use Illuminate\Database\Seeder;

class InteractiveExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $experiences = [
            ['Home launch', 'home', 'intro', 'logo_assemble', 10],
            ['Featured project stage', 'home', 'selected_work', 'project_stack', 20],
            ['Work project stack', 'work', 'projects', 'project_stack', 10],
            ['Project gallery stack', 'project', 'gallery', 'project_stack', 10],
            ['Services build path', 'services', 'capabilities', 'build_path', 10],
            ['Marketing signal field', 'marketing', 'hero', 'signal_field', 10],
            ['Marketing growth network', 'marketing', 'capabilities', 'growth_network', 20],
            ['Lab pixel forge', 'lab', 'experiments', 'pixel_forge', 10],
        ];

        foreach ($experiences as [$name, $page, $section, $type, $order]) {
            InteractiveExperience::firstOrCreate(
                ['name' => $name],
                [
                    'page' => $page,
                    'section_key' => $section,
                    'type' => $type,
                    'enabled' => true,
                    'accent_preset' => 'violet-orange',
                    'intensity' => 1,
                    'sort_order' => $order,
                ],
            );
        }
    }
}
