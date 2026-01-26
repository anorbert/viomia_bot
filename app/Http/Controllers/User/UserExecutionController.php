<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EAWhatsappExcution;

class UserExecutionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $account = trim($request->get('account', ''));

        $executions = EAWhatsappExcution::query()
            ->with(['signal']) // relation
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->when($account, fn($qry) => $qry->where('account_id','like',"%{$account}%"))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('users.executions.index', compact('executions','status','account'));
    }
}
