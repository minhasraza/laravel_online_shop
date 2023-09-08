<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $title = fake()->unique()->name();
        $slug = Str::slug($title);

        $brands = [3,4];
        $subCategories = [15,17,18];
        $subCatRandomKey = array_rand($subCategories);
        $brandRandomKey = array_rand($brands);

        return [
            'title' => $title,
            'slug' => $slug,
            'category_id' => 12,
            'sub_category_id' => $subCategories[$subCatRandomKey],
            'brand_id' => $brands[$brandRandomKey],
            'price' => rand(10,1000),
            'sku' => rand(1000,10000),
            'track_qty' => 'Yes',
            'qty' => '10',
            'is_featured' => 'Yes',
            'status' => 1
        ];
    }
}
