/**
 * MySMC_EA/web/TradeEntryContextCapture.mqh
 * 
 * Captures technical state AT trade entry for proper AI training
 * Solves P0-4: "Patterns Detected at Wrong Time"
 * 
 * Usage:
 *   OnTradeTransaction() {
 *       if (deal.Entry() == DEAL_ENTRY)
 *           CaptureAndStoreEntryContext(deal, Ask, Bid);
 *   }
 */

#property strict

/**
 * Structure to hold entry-time technical data
 */
struct EntryContext
{
    // Trade identification
    datetime entry_time;
    int ticket;
    string symbol;
    int direction;  // OP_BUY or OP_SELL
    double entry_price;
    
    // Technical indicators AT ENTRY
    double rsi;
    double atr;
    string rsi_level;  // oversold, neutral, overbought
    
    // Trend
    string trend;  // UP, DOWN, RANGE
    double trend_strength;  // 0-1 confidence
    
    // Pattern AT ENTRY (not at close)
    string pattern;  // BOS, LIQSWP, OBBLOCK, FVG, NONE
    double pattern_quality;  // 0-100
    
    // Market context
    double spread;
    double bid;
    double ask;
    double atr_multiplier;  // SL = entry +/- atr_multiplier * ATR
    
    // Macro context
    string dxy_trend;
    string dxy_level;
    bool risk_off;
    
    // Account state
    double balance_at_entry;
    double equity_at_entry;
    double margin_used_percent;
    
    // Signal linkage
    string signal_id;
    string correlation_id;
};

/**
 * Main function: Capture entry-time context when trade opens
 * Called from OnTradeTransaction when DEAL_ENTRY occurs
 */
bool CaptureAndStoreEntryContext(
    int ticket,
    string symbol,
    int direction,
    double entry_price
)
{
    EntryContext ctx;
    
    // === CAPTURE CURRENT STATE ===
    ctx.entry_time = TimeCurrent();
    ctx.ticket = ticket;
    ctx.symbol = symbol;
    ctx.direction = direction;
    ctx.entry_price = entry_price;
    
    // === TECHNICAL STATE AT ENTRY ===
    // Get RSI from iRSI indicator
    double rsi_values[2];
    ArraySetAsSeries(rsi_values, true);
    CopyBuffer(iRSI(symbol, PERIOD_M5, 14), 0, 0, 2, rsi_values);
    ctx.rsi = rsi_values[0];
    ctx.rsi_level = GetRSILevel(ctx.rsi);
    
    // Get ATR from iATR indicator
    double atr_values[2];
    ArraySetAsSeries(atr_values, true);
    CopyBuffer(iATR(symbol, PERIOD_M5, 14), 0, 0, 2, atr_values);
    ctx.atr = atr_values[0];
    
    // === TREND AT ENTRY ===
    GetTrendAtEntry(ctx, symbol);
    
    // === PATTERN AT ENTRY ===
    // This should call your pattern detection logic from Entry_SMC.mqh
    GetPatternAtEntry(ctx, symbol, direction, entry_price);
    
    // === MARKET CONTEXT ===
    ctx.bid = SymbolInfoDouble(symbol, SYMBOL_BID);
    ctx.ask = SymbolInfoDouble(symbol, SYMBOL_ASK);
    ctx.spread = ctx.ask - ctx.bid;
    ctx.atr_multiplier = 1.5;  // SL = entry +/- 1.5*ATR
    
    // === MACRO CONTEXT ===
    GetMacroContext(ctx);
    
    // === ACCOUNT STATE ===
    ctx.balance_at_entry = AccountInfoDouble(ACCOUNT_BALANCE);
    ctx.equity_at_entry = AccountInfoDouble(ACCOUNT_EQUITY);
    
    // Calculate margin used
    double margin_required = AccountInfoDouble(ACCOUNT_MARGIN);
    double margin_free = AccountInfoDouble(ACCOUNT_MARGIN_FREE);
    ctx.margin_used_percent = (margin_required / (margin_required + margin_free)) * 100;
    
    // === SIGNAL LINKAGE (optional) ===
    // If this trade came from an AI signal, link it
    // ctx.signal_id = GetLinkedSignalId(ticket);
    
    // === SEND TO LARAVEL ===
    return SendEntryContextToLaravel(ctx);
}

/**
 * Send captured context to Laravel API
 */
bool SendEntryContextToLaravel(EntryContext &ctx)
{
    string json = BuildEntryContextJson(ctx);
    string headers = "Content-Type: application/json\r\n";
    headers += ("X-API-KEY: " + InpApiKey + "\r\n");
    
    string url = InpServerUrl + "/api/bot/trade/entry-context";
    
    char post_body[];
    char post_result[];
    StringToCharArray(json, post_body, 0, StringLen(json));
    
    int timeout_ms = 200;  // Entry context capture should be fast
    int response_code = WebRequest("POST", url, headers, timeout_ms, post_body, post_result);
    
    if (response_code == 201)
    {
        Print("Entry context captured for ticket ", ctx.ticket);
        return true;
    }
    else if (response_code == -1)
    {
        Print("WebRequest error capturing entry context, code: ", GetLastError());
        // Don't fail the trade, just log the miss
        return true;  // Failure to capture context is not a blocker
    }
    else
    {
        Print("Entry context upload failed, code: ", response_code);
        return true;  // Still not a blocker
    }
}

/**
 * Build JSON payload with entry context
 */
string BuildEntryContextJson(EntryContext &ctx)
{
    string json = "{";
    
    // Trade ID
    json += "\"account_id\":\"" + InpAccountId + "\",";
    json += "\"ticket\":" + IntegerToString(ctx.ticket) + ",";
    json += "\"symbol\":\"" + ctx.symbol + "\",";
    json += "\"direction\":\"" + (ctx.direction == OP_BUY ? "BUY" : "SELL") + "\",";
    json += "\"entry_price\":" + DoubleToString(ctx.entry_price, 5) + ",";
    json += "\"entry_time\":\"" + TimeToString(ctx.entry_time, TIME_DATE | TIME_MINUTES) + "\",";
    
    // Technical
    json += "\"entry_rsi\":" + DoubleToString(ctx.rsi, 2) + ",";
    json += "\"entry_atr\":" + DoubleToString(ctx.atr, 5) + ",";
    json += "\"entry_rsi_level\":\"" + ctx.rsi_level + "\",";
    
    // Trend
    json += "\"entry_trend\":\"" + ctx.trend + "\",";
    json += "\"trend_strength\":" + DoubleToString(ctx.trend_strength, 2) + ",";
    
    // Pattern
    json += "\"entry_pattern_type\":\"" + ctx.pattern + "\",";
    json += "\"pattern_quality\":" + DoubleToString(ctx.pattern_quality, 2) + ",";
    
    // Market
    json += "\"entry_spread\":" + DoubleToString(ctx.spread, 5) + ",";
    json += "\"entry_bid\":" + DoubleToString(ctx.bid, 5) + ",";
    json += "\"entry_ask\":" + DoubleToString(ctx.ask, 5) + ",";
    json += "\"entry_atr_multiplier\":" + DoubleToString(ctx.atr_multiplier, 2) + ",";
    
    // Macro
    json += "\"dxy_trend\":\"" + ctx.dxy_trend + "\",";
    json += "\"dxy_level\":\"" + ctx.dxy_level + "\",";
    json += "\"risk_off\":" + (ctx.risk_off ? "true" : "false") + ",";
    
    // Account
    json += "\"account_balance_at_entry\":" + DoubleToString(ctx.balance_at_entry, 2) + ",";
    json += "\"account_equity_at_entry\":" + DoubleToString(ctx.equity_at_entry, 2) + ",";
    json += "\"margin_used_percent\":" + DoubleToString(ctx.margin_used_percent, 2);
    
    json += "}";
    return json;
}

/**
 * Determine RSI level interpretation
 */
string GetRSILevel(double rsi)
{
    if (rsi < 30)
        return "oversold";
    else if (rsi > 70)
        return "overbought";
    else
        return "neutral";
}

/**
 * Get trend at entry (calls existing trend indicator)
 * INTEGRATE WITH YOUR ACTUAL TREND DETECTION
 */
void GetTrendAtEntry(EntryContext &ctx, string symbol)
{
    // TODO: Call your AdvancedTrend.mqh indicator
    // Example stub:
    ctx.trend = "UP";      // Should be UP, DOWN, or RANGE
    ctx.trend_strength = 0.75;  // Should be 0-1 confidence
}

/**
 * Get pattern detected at entry
 * INTEGRATE WITH YOUR PATTERN DETECTION FROM Entry_SMC.mqh
 */
void GetPatternAtEntry(EntryContext &ctx, string symbol, int direction, double entry)
{
    // TODO: Integrate with your BOS/LIQSWP/OBBLOCK detection
    // This is critical for accurate training data
    
    ctx.pattern = "BOS";        // Should be BOS, LIQSWP, OBBLOCK, FVG, or NONE
    ctx.pattern_quality = 85;   // Should be 0-100
}

/**
 * Get macro context (DXY trend, risk-off indicators)
 */
void GetMacroContext(EntryContext &ctx)
{
    // TODO: Get DXY trend from iMA(EURUSD, PERIOD_D1)
    // TODO: Detect risk-off conditions (VIX equivalent, news events)
    
    ctx.dxy_trend = "UP";
    ctx.dxy_level = "HIGH";
    ctx.risk_off = false;
}

/**
 * Helper: Convert int to string
 */
string IntegerToString(int value)
{
    return StringFormat("%d", value);
}
