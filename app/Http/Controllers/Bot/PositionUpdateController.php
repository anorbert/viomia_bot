<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PositionUpdate;
use App\Models\Account;
use App\Traits\ApiResponseFormatter;

class PositionUpdateController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Store position update (unrealized P/L)
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        
        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }

        $validated = validator($data, [
            'account'                  => 'required|numeric',
            'ticket'                   => 'required|string',
            'entry_price'              => 'required|numeric',
            'current_price'            => 'required|numeric',
            'unrealized_pl'            => 'required|numeric',
            'unrealized_pl_percent'    => 'required|numeric',
            'lot_size'                 => 'required|numeric',
            'updated_at'               => 'required|date_format:Y-m-d H:i:s',
        ])->validate();

        // Resolve account login to account_id
        $account = Account::where('login', $validated['account'])->first();
        if (!$account) {
            return $this->errorResponse('Account not found', ['account_login' => $validated['account']], 400);
        }
        $validated['account_id'] = $account->id;
        unset($validated['account']);

        try {
            $update = PositionUpdate::updateOrCreate(
                ['ticket' => $validated['ticket']],
                $validated
            );

            return $this->successResponse('Position updated', ['ticket' => $update->ticket], 200);
        } catch (\Exception $e) {
            \Log::error('Position update failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to update position', null, 500);
        }
    }
}
