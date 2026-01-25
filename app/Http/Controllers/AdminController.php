<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TradeLog;
use App\Models\ErrorLog;
use App\Models\Account;
use App\Models\WhatsappSignal;
use App\Models\EaWhatsappExcution;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $week  = now()->subDays(7);

        /** ================= USERS ================= */
        $totalClients = User::count();
        $newClients   = User::where('created_at', '>=', $week)->count();

        /** ================= ACCOUNTS ================= */
        $connectedAccounts = Account::where('active', true)->count();

        /** ================= ACTIVE BOTS ================= */
        $activeBots = DB::table('ea_status_changes as e')
            ->whereIn('e.id', function ($q) {
                $q->selectRaw('MAX(id)')
                    ->from('ea_status_changes')
                    ->groupBy('account_id');
            })
            ->where('status', 'RUNNING')
            ->count();

        /** ================= TODAY PERFORMANCE ================= */
        $baseToday = TradeLog::query()->where('created_at', '>=', $today);

        $todaysProfit = (clone $baseToday)->sum(DB::raw('COALESCE(profit,0)'));
        $todaysTrades = (clone $baseToday)->count();
        $todaysWins   = (clone $baseToday)->where('profit', '>', 0)->count();
        $todaysLosses = (clone $baseToday)->where('profit', '<', 0)->count();

        $winRate = $todaysTrades > 0 ? round(($todaysWins / $todaysTrades) * 100, 1) : 0;

        $avgWin  = (clone $baseToday)->where('profit', '>', 0)->avg('profit') ?? 0;
        $avgLoss = (clone $baseToday)->where('profit', '<', 0)->avg('profit') ?? 0;

        $grossProfit = (clone $baseToday)->where('profit', '>', 0)->sum(DB::raw('COALESCE(profit,0)'));
        $grossLoss   = (clone $baseToday)->where('profit', '<', 0)->sum(DB::raw('COALESCE(profit,0)'));

        $profitFactor = ($grossLoss != 0) ? round($grossProfit / abs($grossLoss), 2) : null;

        $expectancy = ($todaysTrades > 0)
            ? round((($todaysWins / $todaysTrades) * $avgWin) - ((($todaysLosses / $todaysTrades)) * abs($avgLoss)), 2)
            : 0;

        /** ================= AVG TRADE DURATION (CLOSED TODAY) ================= */
        $avgTradeDuration = TradeLog::whereNotNull('close_price')
            ->where('created_at', '>=', $today)
            ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, created_at, updated_at)')) ?? 0;

        /** ================= ALERTS ================= */
        $alertsCount = ErrorLog::where('error_at', '>=', $today)->count();

        /** ================= SERVER HEALTH ================= */
        $lastPing = DB::table('ea_status_changes')
            ->latest('changed_at')
            ->value('changed_at');

        $serverHealth = $lastPing && now()->diffInMinutes($lastPing) < 5 ? 'OK' : 'DEGRADED';

        /** ================= 7-DAY DAILY PNL (BEST / WORST) ================= */
        $dailyPnL7d = TradeLog::selectRaw("DATE(created_at) as d, SUM(COALESCE(profit,0)) as pnl")
            ->where('created_at', '>=', $week)
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('pnl', 'd');

        $bestDayPnL  = $dailyPnL7d->count() ? round($dailyPnL7d->max(), 2) : 0;
        $worstDayPnL = $dailyPnL7d->count() ? round($dailyPnL7d->min(), 2) : 0;

        /** ================= MONTHLY PROFIT (12M) ================= */
        $months = [];
        $monthlyProfit = [];

        for ($i = 11; $i >= 0; $i--) {
            $m = now()->subMonths($i);
            $key = $m->format('Y-m');
            $months[$key] = $m->format('M Y');
            $monthlyProfit[$key] = 0;
        }

        $profits = TradeLog::selectRaw("
                DATE_FORMAT(updated_at, '%Y-%m') as ym,
                SUM(COALESCE(profit,0)) as total_profit
            ")
            ->whereNotNull('close_price')
            ->where('updated_at', '>=', now()->subMonths(12))
            ->groupBy('ym')
            ->pluck('total_profit', 'ym');

        foreach ($profits as $ym => $value) {
            if (isset($monthlyProfit[$ym])) {
                $monthlyProfit[$ym] = round($value, 2);
            }
        }

        $months = array_values($months);
        $monthlyProfit = array_values($monthlyProfit);

        /** ================= SYMBOL DISTRIBUTION (LAST 30D) ================= */
        $symbolData = TradeLog::selectRaw('symbol, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('symbol')
            ->orderByDesc('total')
            ->get();

        /** ================= LIVE INITIAL DATA ================= */
        $pendingSignals = WhatsappSignal::where('status', 'pending')->count();

        $lastSignal = WhatsappSignal::latest('received_at')->first();
        $lastSignalText = $lastSignal
            ? "{$lastSignal->symbol} {$lastSignal->type} @ {$lastSignal->entry} | SL {$lastSignal->stop_loss}"
            : null;

        $lastSignalAge = $lastSignal
            ? now()->diffInSeconds($lastSignal->received_at) . "s ago"
            : null;

        $lastExec = EaWhatsappExcution::latest()->first();
        $lastExecText = $lastExec ? "Signal #{$lastExec->whatsapp_signal_id} → Acc {$lastExec->account_id}" : null;
        $lastExecStatus = $lastExec ? strtoupper($lastExec->status) : null;

        $oneHour = now()->subHour();
        $execTotal1h = EaWhatsappExcution::where('created_at', '>=', $oneHour)->count();
        $execFailed1h = EaWhatsappExcution::where('created_at', '>=', $oneHour)->where('status', 'failed')->count();
        $execSuccessRate = $execTotal1h > 0 ? round((($execTotal1h - $execFailed1h) / $execTotal1h) * 100, 0) . '%' : '—';

        $openTrades = TradeLog::where('status', 'open')->count();
        $floatingPnL = (float) TradeLog::where('status', 'open')->sum(DB::raw('COALESCE(profit,0)'));

        $exposure = TradeLog::selectRaw("symbol, SUM(lots - closed_lots) as lots, SUM(COALESCE(profit,0)) as pnl")
            ->where('status', 'open')
            ->groupBy('symbol')
            ->orderByDesc('lots')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'symbol' => $r->symbol,
                'lots'   => (float) $r->lots,
                'pnl'    => (float) $r->pnl,
            ])->toArray();

        $live = [
            'openPositions'   => $openTrades,
            'floatingPnL'     => round($floatingPnL, 2),
            'signalQueue'     => $pendingSignals,
            'execSuccessRate' => $execSuccessRate,
            'lastSignalText'  => $lastSignalText,
            'lastSignalAge'   => $lastSignalAge,
            'lastExecText'    => $lastExecText,
            'lastExecStatus'  => $lastExecStatus,
            'exposure'        => $exposure,
        ];

        return view('dashboard', compact(
            'totalClients',
            'newClients',
            'connectedAccounts',
            'activeBots',
            'todaysProfit',
            'todaysTrades',
            'todaysWins',
            'todaysLosses',
            'winRate',
            'avgWin',
            'avgLoss',
            'profitFactor',
            'expectancy',
            'avgTradeDuration',
            'alertsCount',
            'serverHealth',
            'bestDayPnL',
            'worstDayPnL',
            'months',
            'monthlyProfit',
            'symbolData',
            'live'
        ));
    }

    /**
     * Route:
     * Route::get('/admin/dashboard/metrics', [AdminController::class,'metrics'])->name('admin.dashboard.metrics');
     */
    public function metrics()
    {
        $today = now()->startOfDay();
        $q = TradeLog::query()->where('created_at', '>=', $today);

        /** ================= TODAY PERFORMANCE ================= */
        $profit = (clone $q)->sum(DB::raw('COALESCE(profit,0)'));
        $trades = (clone $q)->count();
        $wins   = (clone $q)->where('profit', '>', 0)->count();
        $losses = (clone $q)->where('profit', '<', 0)->count();

        $winRate = $trades > 0 ? round(($wins / $trades) * 100, 1) : 0;

        $avgWin  = (clone $q)->where('profit', '>', 0)->avg('profit') ?? 0;
        $avgLoss = (clone $q)->where('profit', '<', 0)->avg('profit') ?? 0;

        $grossProfit = (clone $q)->where('profit', '>', 0)->sum(DB::raw('COALESCE(profit,0)'));
        $grossLoss   = (clone $q)->where('profit', '<', 0)->sum(DB::raw('COALESCE(profit,0)'));

        $profitFactor = ($grossLoss != 0) ? round($grossProfit / abs($grossLoss), 2) : null;

        $expectancy = ($trades > 0)
            ? round((($wins / $trades) * $avgWin) - ((($losses / $trades)) * abs($avgLoss)), 2)
            : 0;

        /** ================= LIVE / OPS ================= */
        $pendingSignals = WhatsappSignal::where('status', 'pending')->count();

        $lastSignal = WhatsappSignal::latest('received_at')->first();
        $lastSignalText = $lastSignal
            ? "{$lastSignal->symbol} {$lastSignal->type} @ {$lastSignal->entry} | SL {$lastSignal->stop_loss}"
            : null;

        $lastSignalAge = $lastSignal
            ? now()->diffInSeconds($lastSignal->received_at) . "s ago"
            : null;

        $oneHour = now()->subHour();
        $execTotal1h = EaWhatsappExcution::where('created_at', '>=', $oneHour)->count();
        $execFailed1h = EaWhatsappExcution::where('created_at', '>=', $oneHour)->where('status', 'failed')->count();
        $execSuccessRate = $execTotal1h > 0 ? round((($execTotal1h - $execFailed1h) / $execTotal1h) * 100, 0) . '%' : '—';

        $lastExec = EaWhatsappExcution::latest()->first();
        $lastExecText = $lastExec ? "Signal #{$lastExec->whatsapp_signal_id} → Acc {$lastExec->account_id}" : null;
        $lastExecStatus = $lastExec ? strtoupper($lastExec->status) : null;

        $openTrades = TradeLog::where('status', 'open')->count();
        $floatingPnL = (float) TradeLog::where('status', 'open')->sum(DB::raw('COALESCE(profit,0)'));

        $exposure = TradeLog::selectRaw("symbol, SUM(lots - closed_lots) as lots, SUM(COALESCE(profit,0)) as pnl")
            ->where('status', 'open')
            ->groupBy('symbol')
            ->orderByDesc('lots')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'symbol' => $r->symbol,
                'lots'   => (float) $r->lots,
                'pnl'    => (float) $r->pnl,
            ]);

        $recentErrors = ErrorLog::where('error_at', '>=', $today)
            ->latest('error_at')
            ->limit(8)
            ->get(['error_type', 'error_message', 'account_id', 'error_at'])
            ->map(fn($e) => [
                'type' => $e->error_type,
                'msg'  => (string) \Illuminate\Support\Str::limit($e->error_message, 70),
                'acc'  => $e->account_id,
                'at'   => $e->error_at ? $e->error_at->format('H:i:s') : '',
            ]);

        $errorBreakdown = ErrorLog::selectRaw("error_type, COUNT(*) as total")
            ->where('error_at', '>=', $today)
            ->groupBy('error_type')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn($r) => ['type' => $r->error_type, 'total' => (int)$r->total]);

        return response()->json([
            'profit'       => round($profit, 2),
            'trades'       => $trades,
            'wins'         => $wins,
            'losses'       => $losses,
            'winRate'      => $winRate,
            'avgWin'       => round($avgWin, 2),
            'avgLoss'      => round($avgLoss, 2),
            'profitFactor' => $profitFactor,
            'expectancy'   => $expectancy,

            'live' => [
                'openPositions'   => $openTrades,
                'floatingPnL'     => round($floatingPnL, 2),
                'signalQueue'     => $pendingSignals,
                'execSuccessRate' => $execSuccessRate,
                'lastSignalText'  => $lastSignalText,
                'lastSignalAge'   => $lastSignalAge,
                'lastExecText'    => $lastExecText,
                'lastExecStatus'  => $lastExecStatus,
                'exposure'        => $exposure,
                'recentErrors'    => $recentErrors,
                'errorBreakdown'  => $errorBreakdown,
            ]
        ]);
    }
}
