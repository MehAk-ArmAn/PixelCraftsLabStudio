<?php

namespace Database\Factories;

use App\Models\HomepageFeaturedProject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomepageFeaturedProject>
 */
class HomepageFeaturedProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'slot' => 1,
            'sort_order' => 10,
            'is_primary' => false,
            'enabled' => true,
            'display_mode' => 'auto',
            'media_mode' => 'auto',
        ];
    }
}
