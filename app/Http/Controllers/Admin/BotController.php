<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BotStatus;
use App\Models\Account;
use App\Models\TradeLog;
use App\Models\ErrorLog;
use App\Models\EaStatusChange;
use App\Models\DailySummary;
use Carbon\Carbon;
use App\Models\EaBot;
use Log;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bots = EaBot::get();
        return view('admin.bots.index', compact('bots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = EaBot::where('status', 'Active')->get();
        return view('admin.bots.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'account_id' => 'required|exists:accounts,id',
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $bot = EaBot::create([
                'name' => $validated['name'],
                'version' => $validated['version'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            Log::info('Bot created successfully. Bot ID: ' . $bot->id);
            return redirect()->route('admin.bots.show', $bot)->with('success', 'Bot created successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to create bot: ' . $e->getMessage());
            return back()->with('error', 'Failed to create bot.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BotStatus $bot)
    {
        $bot->load('account');
        
        // Get bot statistics
        $stats = [
            'totalTrades' => TradeLog::where('bot_status_id', $bot->id)->count(),
            'winningTrades' => TradeLog::where('bot_status_id', $bot->id)
                ->where('profit_loss', '>', 0)
                ->count(),
            'losingTrades' => TradeLog::where('bot_status_id', $bot->id)
                ->where('profit_loss', '<', 0)
                ->count(),
            'totalProfitLoss' => TradeLog::where('bot_status_id', $bot->id)->sum('profit_loss'),
            'winRate' => $this->calculateWinRate($bot->id),
            'recentErrors' => ErrorLog::where('bot_status_id', $bot->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'statusChanges' => EaStatusChange::where('bot_status_id', $bot->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
        ];

        // Check bot health
        $lastPing = $bot->last_ping;
        $isHealthy = $lastPing && $lastPing->greaterThan(now()->subMinutes(5));

        return view('admin.bots.show', compact('bot', 'stats', 'isHealthy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BotStatus $bot)
    {
        $accounts = Account::where('status', 'active')->get();
        return view('admin.bots.edit', compact('bot', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BotStatus $bot)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'strategy' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $bot->update($validated);
            Log::info('Bot updated successfully. Bot ID: ' . $bot->id);
            return redirect()->route('admin.bots.show', $bot)->with('success', 'Bot updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update bot: ' . $e->getMessage());
            return back()->with('error', 'Failed to update bot.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BotStatus $bot)
    {
        try {
            $bot->delete();
            Log::info('Bot deleted successfully. Bot ID: ' . $bot->id);
            return redirect()->route('admin.bots.index')->with('success', 'Bot deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete bot: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete bot.');
        }
    }

    /**
     * Display bot logs
     */
    public function logs(Request $request)
    {
        $botId = $request->get('bot_id');
        $type = $request->get('type', 'error'); // error, status, trade

        $query = match ($type) {
            'error' => ErrorLog::query(),
            'status' => EaStatusChange::query(),
            'trade' => TradeLog::query(),
            default => ErrorLog::query(),
        };

        if ($botId) {
            $query->where('bot_status_id', $botId);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(20);
        $bots = BotStatus::all();

        return view('admin.bots.logs', compact('logs', 'bots', 'type', 'botId'));
    }

    /**
     * Display bot settings
     */
    public function settings(Request $request)
    {
        $botId = $request->get('bot_id');
        
        if ($botId) {
            $bot = BotStatus::findOrFail($botId);
            return view('admin.bots.settings', compact('bot'));
        }

        $bots = BotStatus::with('account')->get();
        return view('admin.bots.settings', compact('bots'));
    }

    /**
     * Calculate win rate
     */
    private function calculateWinRate($botId)
    {
        $totalTrades = TradeLog::where('bot_status_id', $botId)->count();
        if ($totalTrades === 0) {
            return 0;
        }

        $winningTrades = TradeLog::where('bot_status_id', $botId)
            ->where('profit_loss', '>', 0)
            ->count();

        return round(($winningTrades / $totalTrades) * 100, 2);
    }

    /**
     * Get bot status summary
     */
    public function getStatus($botId)
    {
        $bot = BotStatus::find($botId);
        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        return response()->json([
            'id' => $bot->id,
            'balance' => $bot->balance,
            'equity' => $bot->equity,
            'daily_pl' => $bot->daily_pl,
            'open_positions' => $bot->open_positions,
            'max_dd' => $bot->max_dd,
            'is_healthy' => $bot->last_ping && $bot->last_ping->greaterThan(now()->subMinutes(5)),
            'last_ping' => $bot->last_ping?->diffForHumans(),
        ]);
    }
}
