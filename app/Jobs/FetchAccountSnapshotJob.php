<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Account;
use App\Services\MetaApiService;
use App\Services\LocalDispatcherService;
use Illuminate\Support\Facades\Log;

class FetchAccountSnapshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $accountId;
    protected $order;
    protected $idempotencyKey;

    /**
     * Create a new job instance.
     */
    public function __construct($accountId, array $order, $idempotencyKey)
    {
        //
        $this->accountId = $accountId;
        $this->order = $order;
        $this->idempotencyKey = $idempotencyKey;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Logic to fetch account snapshot from external service
        // Use $this->accountId, $this->order, and $this->idempotencyKey as needed
        $account = Account::find($this->accountId);
        if (!$account || !$account->active) {
            Log::warning("DispatchOrderJob: account not found or inactive: {$this->accountId}");
            return;
        }

        // choose method by account.meta['bridge'] or global config
        $bridge = data_get($account->meta, 'bridge', env('DEFAULT_BRIDGE', 'local')); // 'metaapi' or 'local'

        try {
            if ($bridge === 'metaapi') {
                app(MetaApiService::class)->placeOrder($account, $this->order, $this->idempotencyKey);
            } else {
                app(LocalDispatcherService::class)->placeOrder($account, $this->order, $this->idempotencyKey);
            }
        } catch (\Exception $e) {
            Log::error("DispatchOrderJob failed for account {$account->id}: ".$e->getMessage());
            // implement retries, failure notifications...
            throw $e;
        }
    }
}
