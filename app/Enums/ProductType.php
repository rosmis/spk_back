<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductType: string
{
    case TShirt = 'tshirts';
    case Sweatshirt = 'sweatshirts';
    case Jacket = 'jackets';
    case Pants = 'pants';
    case Gloves = 'gloves';
    case Headwear = 'headwear';
    case Shoes = 'shoes';
    case Accessories = 'accessories';
}
