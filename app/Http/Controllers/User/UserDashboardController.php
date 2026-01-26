<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Account;
use App\Models\TradeLog;
use App\Models\SignalAccount;
use App\Models\WhatsappSignal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Auth;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user  = Auth::user();
        $today = now()->startOfDay();

        // User accounts
        $accounts = Account::where('user_id', $user->id)->get();
        $accountIds = $accounts->pluck('id')->toArray();

        // Connected accounts count
        $connectedAccounts = Account::where('user_id', $user->id)->where('active', true)->count();

        // Running bots (latest status per account from ea_status_changes)
        $runningBots = 0;
        if (!empty($accountIds)) {
            $runningBots = DB::table('ea_status_changes as e')
                ->whereIn('e.id', function ($q) use ($accountIds) {
                    $q->selectRaw('MAX(id)')
                        ->from('ea_status_changes')
                        ->whereIn('account_id', $accountIds)
                        ->groupBy('account_id');
                })
                ->where('status', 'RUNNING')
                ->count();
        }

        // Today performance (only user trades)
        $baseToday = TradeLog::query()
            ->where('created_at', '>=', $today)
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0')); // no accounts => no data      
        

        $todaysProfit = (clone $baseToday)->sum(DB::raw('COALESCE(profit,0)'));
        $todaysTrades = (clone $baseToday)->count();
        $todaysWins   = (clone $baseToday)->where('profit', '>', 0)->count();
        $todaysLosses = (clone $baseToday)->where('profit', '<', 0)->count();
        $winRate      = $todaysTrades > 0 ? round(($todaysWins / $todaysTrades) * 100, 1) : 0;

        // Open positions + floating PnL (user)
        $openTrades = TradeLog::query()
            ->where('status', 'open')
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'));

        $openPositions = (clone $openTrades)->count();
        $floatingPnL   = (float) (clone $openTrades)->sum(DB::raw('COALESCE(profit,0)'));

        // Signal queue for user's accounts (pending)
        $signalQueue = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('status', 'pending')
            ->count();

        // Last signal that touched user's accounts
        $lastSignalRow = SignalAccount::with(['signal'])
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->latest()
            ->first();

        $lastSignalText = null;
        $lastSignalAge  = null;

        if ($lastSignalRow && $lastSignalRow->signal) {
            $s = $lastSignalRow->signal;
            $lastSignalText = "{$s->symbol} {$s->type} @ {$s->entry} | SL {$s->stop_loss}";
            $receivedAt = $s->received_at ?? $s->created_at;
            if ($receivedAt) $lastSignalAge = now()->diffInSeconds($receivedAt) . "s ago";
        }

        // Execution success last 1h based on signal_accounts status
        $oneHour = now()->subHour();
        $execTotal1h = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('created_at', '>=', $oneHour)
            ->count();

        $execFailed1h = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('created_at', '>=', $oneHour)
            ->whereIn('status', ['failed','skipped'])
            ->count();

        $execSuccessRate = $execTotal1h > 0
            ? round((($execTotal1h - $execFailed1h) / $execTotal1h) * 100, 0) . '%'
            : '—';

        // Exposure by symbol (open trades)
        $exposure = TradeLog::selectRaw("symbol, SUM(lots - COALESCE(closed_lots,0)) as lots, SUM(COALESCE(profit,0)) as pnl")
            ->where('status', 'open')
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->groupBy('symbol')
            ->orderByDesc('lots')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'symbol' => $r->symbol,
                'lots'   => (float) $r->lots,
                'pnl'    => (float) $r->pnl,
            ])->toArray();

        // Recent activity tables
        $recentSignals = SignalAccount::with(['signal','account'])
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->latest()
            ->limit(10)
            ->get();

        $recentTrades = TradeLog::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->latest()
            ->limit(10)
            ->get();

        $live = [
            'connectedAccounts' => $connectedAccounts,
            'runningBots'       => $runningBots,
            'signalQueue'       => $signalQueue,
            'openPositions'     => $openPositions,
            'floatingPnL'       => round($floatingPnL, 2),
            'execSuccessRate'   => $execSuccessRate,
            'lastSignalText'    => $lastSignalText,
            'lastSignalAge'     => $lastSignalAge,
            'exposure'          => $exposure,
        ];

        return view('users.dashboard', compact(
            'user',
            'accounts',
            'todaysProfit',
            'todaysTrades',
            'todaysWins',
            'todaysLosses',
            'winRate',
            'recentSignals',
            'recentTrades',
            'live'
        ));
    }

    /**
     * Route:
     * Route::get('/user/dashboard/metrics', [UserDashboardController::class,'metrics'])->name('user.dashboard.metrics');
     */
    public function metrics(Request $request)
    {
        $user  = $request->user();
        $today = now()->startOfDay();

        $accountIds = Account::where('user_id', $user->id)->pluck('id')->toArray();

        $qToday = TradeLog::query()
            ->where('created_at', '>=', $today)
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'));

        $profit = (clone $qToday)->sum(DB::raw('COALESCE(profit,0)'));
        $trades = (clone $qToday)->count();
        $wins   = (clone $qToday)->where('profit', '>', 0)->count();
        $losses = (clone $qToday)->where('profit', '<', 0)->count();

        $winRate = $trades > 0 ? round(($wins / $trades) * 100, 1) : 0;

        // Open
        $openQ = TradeLog::query()
            ->where('status', 'open')
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'));

        $openPositions = (clone $openQ)->count();
        $floatingPnL   = (float) (clone $openQ)->sum(DB::raw('COALESCE(profit,0)'));

        // Queue
        $signalQueue = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('status', 'pending')
            ->count();

        // Exec success
        $oneHour = now()->subHour();
        $execTotal1h = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('created_at', '>=', $oneHour)
            ->count();

        $execFailed1h = SignalAccount::query()
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->where('created_at', '>=', $oneHour)
            ->whereIn('status', ['failed','skipped'])
            ->count();

        $execSuccessRate = $execTotal1h > 0
            ? round((($execTotal1h - $execFailed1h) / $execTotal1h) * 100, 0) . '%'
            : '—';

        // exposure
        $exposure = TradeLog::selectRaw("symbol, SUM(lots - COALESCE(closed_lots,0)) as lots, SUM(COALESCE(profit,0)) as pnl")
            ->where('status', 'open')
            ->when(!empty($accountIds), fn($q) => $q->whereIn('account_id', $accountIds))
            ->when(empty($accountIds), fn($q) => $q->whereRaw('1=0'))
            ->groupBy('symbol')
            ->orderByDesc('lots')
            ->limit(10)
            ->get()
            ->map(fn($r) => ['symbol'=>$r->symbol,'lots'=>(float)$r->lots,'pnl'=>(float)$r->pnl]);

        return response()->json([
            'profit'  => round($profit, 2),
            'trades'  => $trades,
            'wins'    => $wins,
            'losses'  => $losses,
            'winRate' => $winRate,
            'live' => [
                'openPositions'   => $openPositions,
                'floatingPnL'     => round($floatingPnL, 2),
                'signalQueue'     => $signalQueue,
                'execSuccessRate' => $execSuccessRate,
                'exposure'        => $exposure,
            ]
        ]);
    }
}
