<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Package>
 */
class PackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'category' => 'Growth Bundles',
            'billing_type' => 'monthly',
            'price' => 2990,
            'currency' => 'AED',
            'billing_period' => 'month',
            'short_description' => fake()->sentence(),
            'is_published' => true,
            'sort_order' => 10,
        ];
    }
}
