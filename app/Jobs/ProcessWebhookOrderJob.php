<?php

namespace App\Jobs;

use App\Dto\Webhook\WebhookOrderDto;
use App\Enums\CartStatus;
use App\Models\Cart;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWebhookOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private WebhookOrderDto $orderDto;

    /**
     * Create a new job instance.
     */
    public function __construct(
        WebhookOrderDto $orderDto
    ) {
        $this->orderDto = $orderDto;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var ?Cart $userActiveCart */
        $userActiveCart = Cart::query()
            ->where('user_id', '=', $this->orderDto->user_id)
            ->where('status', '=', CartStatus::Pending)
            ->first();

        if (! $userActiveCart instanceof Cart) {
            Log::error('No active cart found for user', [
                'email' => $this->orderDto->email,
                'order' => $this->orderDto->id,
            ]);
        }

        try {
            DB::transaction(function () use ($userActiveCart) {

                $userActiveCart->update([
                    'status' => CartStatus::Completed,
                    'reference_order' => $this->orderDto->reference,
                ]);

                Log::info('Order processed and cart completed', [
                    'email' => $this->orderDto->email,
                    'order' => $this->orderDto->id,
                ]);
            });
        } catch (Throwable $e) {
            Log::error('Error processing webhook product', ['error' => $e->getMessage()]);
        }
    }
}
