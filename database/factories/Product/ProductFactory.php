<?php

namespace Database\Factories\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModelClass of Product
 *
 * @extends Factory<TModelClass>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'shopify_gid' => $this->faker->uuid,
            'handle' => $this->faker->slug,
        ];
    }
}
