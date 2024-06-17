<?php

namespace App\Jobs;

use App\Dto\ProductDto;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    ){
        $this->productDto = $productDto;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
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

        $product->variants()
            ->updateOrCreate(
                ['product_id' => $product->id],
                $this->productDto->variants
            );
    }
}
