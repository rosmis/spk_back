<?php

namespace Database\Factories\Cart;

use App\Enums\CartStatus;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @template TModelClass of Cart
 *
 * @extends Factory<TModelClass>
 */
class CartFactory extends Factory
{
    protected $model = Cart::class;

    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(CartStatus::cases()),
        ];
    }

    public function forUser(int $userId): self
    {
        return $this->state([
            'user_id' => $userId,
        ]);
    }

    public function withCartStatusPending(): self
    {
        return $this->state([
            'status' => CartStatus::Pending,
        ]);
    }
}
