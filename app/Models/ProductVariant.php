<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 * @property int $id
 * @property string $shopify_gid
 * @property string $title
 * @property float $price
 * @property integer $quantity_available
 * @property int $product_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 *  Relations
 * @property Product $product
 */
class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
