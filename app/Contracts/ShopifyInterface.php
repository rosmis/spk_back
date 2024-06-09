<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Dto\ProductDto;
use Illuminate\Support\Collection;

interface ShopifyInterface
{
    public function fetchProduts(): Collection;

    public function fetchProductByHandle(string $handle): ProductDto;
}
