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
use App\Models\EaBot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BotController extends Controller
{
    /**
     * Display a listing of the resource.
     * NOTE: This "bots" resource uses EaBot (bot registry / downloadable builds).
     */
    public function index()
    {
        $bots = EaBot::orderByDesc('id')->get();
        return view('admin.bots.index', compact('bots'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // If you intended accounts list, make it Account::... not EaBot::...
        $accounts = Account::where('status', 'active')->get();
        return view('admin.bots.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     * IMPORTANT: supports AJAX JSON response for your Blade.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'version'     => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:Active,Inactive',
        ]);

        try {
            $bot = EaBot::create([
                'name'        => $validated['name'],
                'version'     => $validated['version'],
                'address'     => $validated['address'] ?? null,
                'description' => $validated['description'] ?? null,
                'status'      => $validated['status'] ?? 'Active',
            ]);

            Log::info('Bot created successfully', ['ea_bot_id' => $bot->id, 'by' => auth()->id()]);

            // AJAX expects JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($bot, 201);
            }

            return redirect()
                ->route('admin.bots.index')
                ->with('success', 'Bot created successfully.');

        } catch (\Throwable $e) {
            Log::error('Failed to create bot', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['message' => 'Failed to create bot.'], 500);
            }

            return back()->with('error', 'Failed to create bot.');
        }
    }

    /**
     * Display the specified resource.
     * NOTE: We show EaBot details here.
     * If you also want runtime bot status, we try to attach BotStatus by bot_id when available.
     */
    public function show(EaBot $bot)
    {
        // Optional: if your BotStatus has ea_bot_id or bot_id column linking to EaBot
        $runtime = null;
        if (\Schema::hasColumn('bot_statuses', 'ea_bot_id')) {
            $runtime = BotStatus::with('account')->where('ea_bot_id', $bot->id)->first();
        } elseif (\Schema::hasColumn('bot_statuses', 'bot_id')) {
            $runtime = BotStatus::with('account')->where('bot_id', $bot->id)->first();
        }

        // If runtime exists, build stats from bot_status_id
        $stats = null;
        $isHealthy = null;

        if ($runtime) {
            $stats = [
                'totalTrades'     => TradeLog::where('bot_status_id', $runtime->id)->count(),
                'winningTrades'   => TradeLog::where('bot_status_id', $runtime->id)->where('profit_loss', '>', 0)->count(),
                'losingTrades'    => TradeLog::where('bot_status_id', $runtime->id)->where('profit_loss', '<', 0)->count(),
                'totalProfitLoss' => TradeLog::where('bot_status_id', $runtime->id)->sum('profit_loss'),
                'winRate'         => $this->calculateWinRate($runtime->id),
                'recentErrors'    => ErrorLog::where('bot_status_id', $runtime->id)->latest()->limit(5)->get(),
                'statusChanges'   => EaStatusChange::where('bot_status_id', $runtime->id)->latest()->limit(10)->get(),
            ];

            $lastPing  = $runtime->last_ping;
            $isHealthy = $lastPing && $lastPing->greaterThan(now()->subMinutes(5));
        }

        return view('admin.bots.show', compact('bot', 'runtime', 'stats', 'isHealthy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EaBot $bot)
    {
        return view('admin.bots.edit', compact('bot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EaBot $bot)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'version'     => 'required|string|max:255',
            'address'     => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:Active,Inactive',
        ]);

        try {
            $bot->update($validated);

            Log::info('Bot updated successfully', ['ea_bot_id' => $bot->id, 'by' => auth()->id()]);

            return redirect()
                ->route('admin.bots.index')
                ->with('success', 'Bot updated successfully.');

        } catch (\Throwable $e) {
            Log::error('Failed to update bot', ['error' => $e->getMessage(), 'ea_bot_id' => $bot->id]);
            return back()->with('error', 'Failed to update bot.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EaBot $bot)
    {
        try {
            $bot->delete();

            Log::info('Bot deleted successfully', ['ea_bot_id' => $bot->id, 'by' => auth()->id()]);

            return redirect()
                ->route('admin.bots.index')
                ->with('success', 'Bot deleted successfully.');

        } catch (\Throwable $e) {
            Log::error('Failed to delete bot', ['error' => $e->getMessage(), 'ea_bot_id' => $bot->id]);
            return back()->with('error', 'Failed to delete bot.');
        }
    }

    /**
     * Display bot logs (runtime logs are tied to bot_status_id).
     */
    public function logs(Request $request)
    {
        $botId = $request->get('bot_id'); // bot_status_id
        $type  = $request->get('type', 'error'); // error, status, trade

        $query = match ($type) {
            'error'  => ErrorLog::query(),
            'status' => EaStatusChange::query(),
            'trade'  => TradeLog::query(),
            default  => ErrorLog::query(),
        };

        if ($botId) {
            $query->where('bot_status_id', $botId);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20);

        // These are runtime bots (BotStatus)
        $bots = BotStatus::with('account')->orderByDesc('id')->get();

        return view('admin.bots.logs', compact('logs', 'bots', 'type', 'botId'));
    }

    /**
     * Bot Settings page (GLOBAL settings row in bot_settings table)
     * If you built bot_settings as a single-row table.
     */
    public function settings()
    {
        $settings = \App\Models\BotSetting::query()->first();
        return view('admin.bots.settings', compact('settings'));
    }

    /**
     * Update GLOBAL bot settings (single row).
     */
    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'signal_check_interval'      => ['required', 'integer', 'min:1', 'max:3600'],
            'max_spread_points'          => ['required', 'integer', 'min:0', 'max:100000'],

            'risk_per_trade'             => ['required', 'numeric', 'min:0', 'max:100'],
            'max_trades_per_day'         => ['required', 'integer', 'min:0', 'max:1000'],

            'block_before_news_minutes'  => ['required', 'integer', 'min:0', 'max:1440'],
            'block_after_news_minutes'   => ['required', 'integer', 'min:0', 'max:1440'],
            'filter_currencies'          => ['required', 'string', 'max:255'],
        ]);

        $settings = \App\Models\BotSetting::query()->firstOrCreate(['id' => 1], [
            'bot_enabled' => true,
            'use_news_filter' => true,
            'debug_mode' => false,
        ]);

        $data['bot_enabled']     = $request->has('bot_enabled');
        $data['use_news_filter'] = $request->has('use_news_filter');
        $data['debug_mode']      = $request->has('debug_mode');

        $data['filter_currencies'] = collect(explode(',', $data['filter_currencies']))
            ->map(fn($c) => strtoupper(trim($c)))
            ->filter()
            ->unique()
            ->implode(',');

        $settings->update($data);

        Log::info('Global bot settings updated', ['by' => auth()->id()]);

        return back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Calculate win rate
     */
    private function calculateWinRate($botStatusId): float
    {
        $totalTrades = TradeLog::where('bot_status_id', $botStatusId)->count();
        if ($totalTrades === 0) return 0;

        $winningTrades = TradeLog::where('bot_status_id', $botStatusId)
            ->where('profit_loss', '>', 0)
            ->count();

        return round(($winningTrades / $totalTrades) * 100, 2);
    }

    /**
     * Runtime bot status endpoint used by your table polling:
     * GET /admin/bots/status/{botStatusId}
     */
    public function getStatus($botStatusId)
    {
        $bot = BotStatus::find($botStatusId);
        if (!$bot) {
            return response()->json(['error' => 'Bot not found'], 404);
        }

        $isHealthy = $bot->last_ping && $bot->last_ping->greaterThan(now()->subMinutes(5));

        return response()->json([
            'id'             => $bot->id,
            'balance'        => $bot->balance,
            'equity'         => $bot->equity,
            'daily_pl'       => $bot->daily_pl,
            'open_positions' => $bot->open_positions,
            'max_dd'         => $bot->max_dd,
            'is_healthy'     => $isHealthy,
            'last_ping'      => $bot->last_ping ? $bot->last_ping->diffForHumans() : null,
        ]);
    }
}
