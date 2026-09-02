<?php

namespace Database\Factories;

use App\Models\Package;
use App\Models\PackageItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PackageItem>
 */
class PackageItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_id' => Package::factory(),
            'text' => fake()->sentence(4),
            'sort_order' => 10,
            'is_included' => true,
            'is_highlighted' => false,
        ];
    }
}
