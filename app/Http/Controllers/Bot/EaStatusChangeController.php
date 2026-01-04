<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EaStatusChange;
use App\Traits\ApiResponseFormatter;
use App\Models\Account;
use App\Models\AccountSnapshot as Snapshot;

class EaStatusChangeController extends Controller
{
    use ApiResponseFormatter;

    /**
     * Log EA status change
     */
    public function store(Request $request)
    {
        $raw = $request->getContent();
        \Log::warning('EA status change: ' . $raw);

        $data = $this->parseRawJson($raw);

        if (!$data) {
            return $this->errorResponse('Invalid JSON format', null, 400);
        }        

        $validated = validator($data, [
            'account'               => 'required|integer',
            'status'                => 'required|in:RUNNING,PAUSED,ERROR_STOP,DAILY_LOSS_HIT',
            'reason'                => 'required|string',
            'consecutive_losses'    => 'required|integer',
            'balance'               => 'required|numeric',
            'equity'                => 'required|numeric',
            'positions_open'        => 'required|integer',
            'changed_at'            => 'required|date_format:Y-m-d H:i:s',
        ])->validate();
        //check if account is aleady registered
        $account = Account::where('login', $data['account'])->first();
        if (!$account) {
            return $this->errorResponse('Account not registered: ' . $data['account'], null, 400);
        }        
        $validated['account_id'] = $account->id;

        //Now update account status to active
        $account->active = true;
        $account->save();
        
        //Now lets update the snapshot
        $snapshot=Snapshot::where('account_id',$account->id)->latest()->first();
        // if no snapshot availble create one else update
        if(!$snapshot){
            $snapshot=new Snapshot();
            $snapshot->account_id=$account->id;
            $snapshot->balance=$data['balance'];
            $snapshot->equity=$data['equity'];
            $snapshot->margin=0;
            $snapshot->free_margin=0;
            $snapshot->save();
        }else
        {
            $snapshot->balance=$data['balance'];
            $snapshot->equity=$data['equity'];

            $snapshot->save();
        }        
        try {
            $statusChange = EaStatusChange::create($validated);

            // TODO: Send notification for critical status changes
            
            return $this->successResponse('EA status change logged', ['id' => $statusChange->id], 201);
        } catch (\Exception $e) {
            \Log::error('EA status change log failed: ' . $e->getMessage());
            return $this->errorResponse('Failed to log status change', null, 500);
        }
    }
}
