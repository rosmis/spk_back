<?php

namespace App\Models;

use App\Enums\MediaContentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $shopify_gid
 * @property string $url
 * @property ?string $alt
 * @property string $product_id
 * @property ?MediaContentType $media_content_type
 * @property string $created_at
 * @property string $updated_at
 *
 * Relations
 * @property Product $product
 */
class ProductImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'media_content_type' => MediaContentType::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
