<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailySummary;
use App\Models\Account;
use App\Traits\ApiResponseFormatter;

class DailySummaryController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store daily trading summary
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::info('Daily summary: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'account'           => 'required|numeric',
            'daily_pl'          => 'required|numeric',
            'trades_count'      => 'required|integer',
            'winning_trades'    => 'nullable|integer',
            'losing_trades'     => 'nullable|integer',
            'win_rate_percent'  => 'required|numeric',
            'balance'           => 'required|numeric',
            'equity'            => 'required|numeric',
            'summary_date'      => 'required|date_format:Y-m-d',
            'captured_at'       => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        // Resolve account login to account_id
        $account = Account::where('login', $validated['account'])->first();
        if (!$account) {
            return $this->errorResponse('Account not found', ['account_login' => $validated['account']], 400);
        }
        $validated['account_id'] = $account->id;
        unset($validated['account']);

        try {
            $existing = DailySummary::where('summary_date', $validated['summary_date'])->first();
            
            if ($existing) {
                $existing->update($validated);
                return $this->successResponse('Daily summary updated', ['id' => $existing->id], 200);
            }

            $summary = DailySummary::create($validated);
            
            return $this->successResponse(
                'Daily summary recorded',
                ['id' => $summary->id, 'date' => $summary->summary_date],
                201
            );
        } catch (\Exception $e) {
            \Log::error('Daily summary creation failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to record summary', null, 500);
        }
    }
}
