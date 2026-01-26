<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsappSignal;
use App\Models\EaWhatsappExcution;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSignalController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim($request->get('q', ''));
        $status = $request->get('status');
        $symbol = $request->get('symbol');

        $signals = WhatsappSignal::query()
            ->when($q, function($qry) use ($q){
                $qry->where(function($x) use ($q){
                    $x->where('sender', 'like', "%{$q}%")
                      ->orWhere('symbol', 'like', "%{$q}%")
                      ->orWhere('raw_text', 'like', "%{$q}%");
                });
            })
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when($symbol, fn($qry) => $qry->where('symbol', $symbol))
            ->orderByDesc('received_at')
            ->paginate(20)
            ->withQueryString();

        $symbols = WhatsappSignal::query()
            ->select('symbol')
            ->distinct()
            ->orderBy('symbol')
            ->pluck('symbol');

        return view('users.signals.index', compact('signals','symbols','q','status','symbol'));
    }

    public function executions(Request $request)
    {
        $status = $request->get('status');
        $account = trim($request->get('account', ''));

        $executions = EaWhatsappExcution::query()
            ->with(['signal']) // relation
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when($account, fn($qry) => $qry->where('account_id','like',"%{$account}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('users.executions.index', compact('executions','status','account'));
    }
}
