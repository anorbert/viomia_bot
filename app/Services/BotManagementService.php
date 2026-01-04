<?php

namespace App\Services;

use App\Models\BotStatus;
use App\Models\TradeLog;
use App\Models\ErrorLog;
use App\Models\DailySummary;
use App\Models\EaStatusChange;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BotManagementService
{
    /**
     * Get bot status with health check
     */
    public function getBotStatus(BotStatus $bot)
    {
        return [
            'id' => $bot->id,
            'name' => $bot->name ?? 'Bot ' . $bot->id,
            'balance' => $bot->balance ?? 0,
            'equity' => $bot->equity ?? 0,
            'daily_pl' => $bot->daily_pl ?? 0,
            'open_positions' => $bot->open_positions ?? 0,
            'max_dd' => $bot->max_dd ?? 0,
            'is_healthy' => $this->isBotHealthy($bot),
            'is_active' => $this->isBotActive($bot),
            'last_ping' => $bot->last_ping?->diffForHumans(),
            'uptime_percentage' => $this->calculateUptimePercentage($bot),
        ];
    }

    /**
     * Check if bot is healthy
     */
    public function isBotHealthy(BotStatus $bot): bool
    {
        if (!$bot->last_ping) {
            return false;
        }

        // Bot is healthy if last ping is within 5 minutes
        return $bot->last_ping->greaterThan(now()->subMinutes(5));
    }

    /**
     * Check if bot is active
     */
    public function isBotActive(BotStatus $bot): bool
    {
        if (!$bot->account) {
            return false;
        }

        return $bot->account->status === 'active';
    }

    /**
     * Get bot performance metrics
     */
    public function getBotPerformance(BotStatus $bot, $days = 30)
    {
        $fromDate = Carbon::now()->subDays($days)->startOfDay();
        $toDate = Carbon::now()->endOfDay();

        $totalTrades = TradeLog::where('bot_status_id', $bot->id)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        if ($totalTrades === 0) {
            return [
                'total_trades' => 0,
                'winning_trades' => 0,
                'losing_trades' => 0,
                'win_rate' => 0,
                'total_profit_loss' => 0,
                'average_trade_profit' => 0,
            ];
        }

        $winningTrades = TradeLog::where('bot_status_id', $bot->id)
            ->where('profit_loss', '>', 0)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        $totalProfitLoss = TradeLog::where('bot_status_id', $bot->id)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('profit_loss') ?? 0;

        return [
            'total_trades' => $totalTrades,
            'winning_trades' => $winningTrades,
            'losing_trades' => $totalTrades - $winningTrades,
            'win_rate' => round(($winningTrades / $totalTrades) * 100, 2),
            'total_profit_loss' => round($totalProfitLoss, 2),
            'average_trade_profit' => round($totalProfitLoss / $totalTrades, 2),
        ];
    }

    /**
     * Get bot errors
     */
    public function getBotErrors(BotStatus $bot, $limit = 10)
    {
        return ErrorLog::where('bot_status_id', $bot->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($error) => [
                'id' => $error->id,
                'message' => $error->message,
                'type' => $error->type ?? 'error',
                'created_at' => $error->created_at->diffForHumans(),
            ]);
    }

    /**
     * Get bot status changes
     */
    public function getBotStatusChanges(BotStatus $bot, $limit = 10)
    {
        return EaStatusChange::where('bot_status_id', $bot->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(fn ($change) => [
                'id' => $change->id,
                'old_status' => $change->old_status,
                'new_status' => $change->new_status,
                'reason' => $change->reason ?? 'No reason provided',
                'created_at' => $change->created_at->diffForHumans(),
            ]);
    }

    /**
     * Calculate bot uptime percentage
     */
    public function calculateUptimePercentage(BotStatus $bot, $days = 7): float
    {
        $fromDate = Carbon::now()->subDays($days)->startOfDay();
        $toDate = Carbon::now()->endOfDay();

        $totalStatusChanges = EaStatusChange::where('bot_status_id', $bot->id)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        if ($totalStatusChanges === 0) {
            // If no status changes, assume bot was healthy entire period
            return $this->isBotHealthy($bot) ? 100.0 : 0.0;
        }

        $upTimeChanges = EaStatusChange::where('bot_status_id', $bot->id)
            ->where('new_status', 'running')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        return round(($upTimeChanges / $totalStatusChanges) * 100, 2);
    }

    /**
     * Get all bots summary
     */
    public function getAllBotsSummary()
    {
        $bots = BotStatus::with('account')->get();

        return [
            'total_bots' => $bots->count(),
            'healthy_bots' => $bots->filter(fn ($bot) => $this->isBotHealthy($bot))->count(),
            'unhealthy_bots' => $bots->filter(fn ($bot) => !$this->isBotHealthy($bot))->count(),
            'active_bots' => $bots->filter(fn ($bot) => $this->isBotActive($bot))->count(),
            'inactive_bots' => $bots->filter(fn ($bot) => !$this->isBotActive($bot))->count(),
            'total_balance' => $bots->sum('balance') ?? 0,
            'total_equity' => $bots->sum('equity') ?? 0,
            'total_daily_pl' => $bots->sum('daily_pl') ?? 0,
        ];
    }

    /**
     * Restart bot
     */
    public function restartBot(BotStatus $bot): bool
    {
        try {
            // Log restart request
            EaStatusChange::create([
                'bot_status_id' => $bot->id,
                'old_status' => 'running',
                'new_status' => 'restarting',
                'reason' => 'Manual restart requested',
            ]);

            Log::info('Bot restart requested. Bot ID: ' . $bot->id);

            // In a real implementation, this would send a signal to the MQL5 bot
            // via API or websocket to restart itself

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to restart bot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Stop bot
     */
    public function stopBot(BotStatus $bot): bool
    {
        try {
            EaStatusChange::create([
                'bot_status_id' => $bot->id,
                'old_status' => 'running',
                'new_status' => 'stopped',
                'reason' => 'Manual stop requested',
            ]);

            Log::info('Bot stop requested. Bot ID: ' . $bot->id);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to stop bot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Enable bot
     */
    public function enableBot(BotStatus $bot): bool
    {
        try {
            $bot->update(['enabled' => true]);

            EaStatusChange::create([
                'bot_status_id' => $bot->id,
                'old_status' => 'disabled',
                'new_status' => 'running',
                'reason' => 'Bot enabled by admin',
            ]);

            Log::info('Bot enabled. Bot ID: ' . $bot->id);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to enable bot: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Disable bot
     */
    public function disableBot(BotStatus $bot): bool
    {
        try {
            $bot->update(['enabled' => false]);

            EaStatusChange::create([
                'bot_status_id' => $bot->id,
                'old_status' => 'running',
                'new_status' => 'disabled',
                'reason' => 'Bot disabled by admin',
            ]);

            Log::info('Bot disabled. Bot ID: ' . $bot->id);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to disable bot: ' . $e->getMessage());
            return false;
        }
    }
}
