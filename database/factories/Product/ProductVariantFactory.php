<?php

namespace Database\Factories\Product;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModelClass of ProductVariant
 *
 * @extends Factory<TModelClass>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'shopify_gid' => $this->faker->uuid,
            'price' => $this->faker->randomFloat(2, 1, 100),
            'quantity_available' => $this->faker->numberBetween(1, 100),
        ];
    }

    public function forProduct(int $productId): self
    {
        return $this->state([
            'product_id' => $productId,
        ]);
    }
}
