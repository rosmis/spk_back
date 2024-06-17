<?php

namespace App\Jobs;

use App\Dto\ProductDto;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessWebhookProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductDto $productDto;

    /**
     * Create a new job instance.
     */
    public function __construct(
        ProductDto $productDto
    )
    {
        $this->productDto = $productDto;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::transaction(function () {
                /** @var  Product $product */
                $product = Product::query()
                    ->updateOrCreate(
                        ['shopify_gid' => $this->productDto->id],
                        [
                            'handle' => $this->productDto->handle,
                            'title' => $this->productDto->title,
                            'description' => $this->productDto->description,
                        ]
                    );

                foreach ($this->productDto->variants as $variant) {
                    $product->variants()->updateOrCreate(
                        ['shopify_gid' => $variant->id],
                        [
                            'title' => $variant->title,
                            'price' => $variant->price,
                            'quantity_available' => $variant->quantityAvailable,
                        ]
                    );
                }

                foreach ($this->productDto->images as $image) {
                    $product->images()->updateOrCreate(
                        ['shopify_gid' => $image->id],
                        [
                            'url' => $image->url,
                            'alt' => $image->alt,
                        ]
                    );
                }

                Log::info('ALL created/updated', ['product' => $product->toArray()]);
            });
        } catch (\Throwable $e) {
            Log::error('Error processing webhook product', ['error' => $e->getMessage()]);
        }
    }
}
