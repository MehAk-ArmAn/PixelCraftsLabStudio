<?php

namespace Database\Factories;

use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<SocialLink> */
class SocialLinkFactory extends Factory
{
    public function definition(): array
    {
        $platform = fake()->unique()->word();

        return [
            'platform' => Str::title($platform),
            'slug' => Str::slug($platform),
            'url' => 'https://example.com/'.$platform,
            'sort_order' => 10,
            'is_enabled' => true,
        ];
    }
}
