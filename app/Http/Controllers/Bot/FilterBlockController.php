<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FilterBlock;
use App\Models\Account;
use App\Traits\ApiResponseFormatter;

class FilterBlockController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Log session filter block
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::warning('Trade filtered: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'account'        => 'required|numeric',
            'filter_type'    => 'required|string|in:ASIA,LONDON_DISABLED,NEWS,CORRELATION',
            'block_reason'   => 'required|string',
            'blocked_at'     => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        // Resolve account login to account_id
        $account = Account::where('login', $validated['account'])->first();
        if (!$account) {
            return $this->errorResponse('Account not found', ['account_login' => $validated['account']], 400);
        }
        $validated['account_id'] = $account->id;
        unset($validated['account']);

        try {
            $block = FilterBlock::create($validated);
            
            return $this->successResponse('Filter block logged', ['id' => $block->id], 201);
        } catch (\Exception $e) {
            \Log::error('Filter block log failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to log filter block', null, 500);
        }
    }
}
