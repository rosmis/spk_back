<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $shopify_gid
 * @property string $handle
 * @property string $title
 * @property ProductType $type
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * Relations
 * @property Collection<int,ProductVariant> $variants
 * @property Collection<int,ProductImage> $images
 */
class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => ProductType::class,
    ];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
