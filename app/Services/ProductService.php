<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

readonly class ProductService
{
    public function __construct(
    ) {
    }

    public function index(): LengthAwarePaginator
    {
        return Product::query()
            ->with(['images'])
            ->paginate(9);
    }

    public function show(string $handle): Product
    {
        /** @var Product $product */
        $product = Product::query()
            ->where('handle', $handle)
            ->with(['images'])
            ->firstOrFail();

        return $product;
    }
}
