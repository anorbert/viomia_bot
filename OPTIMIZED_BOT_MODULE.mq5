//+------------------------------------------------------------------+
//|                  OPTIMIZED NEWS FETCHING MODULE                  |
//|                     For Viomia Bot Integration                    |
//+------------------------------------------------------------------+

//===============================================================
// CONFIGURATION SECTION
//===============================================================
#define API_VERSION "1.0"
#define MAX_RETRY_ATTEMPTS 3
#define RETRY_DELAY_MS 1000
#define CONNECTION_TIMEOUT_MS 5000

string API_KEY = "TEST_API_KEY_123";  // ⚠️ TODO: Move to secure config
string API_BASE_URL = "http://127.0.0.1:8000/api/bot";
bool ENABLE_SYNC = true;
bool ENABLE_LOGGING = true;

//===============================================================
// HELPER FUNCTION: Reliable API Request with Retry Logic
//===============================================================
int SendApiRequest(
    string method,
    string endpoint,
    string jsonData,
    uchar &result[]
)
{
    string url = API_BASE_URL + endpoint;
    
    for(int attempt = 0; attempt < MAX_RETRY_ATTEMPTS; attempt++)
    {
        string headers = "Content-Type: application/json\r\n"
                        "X-API-KEY: " + API_KEY + "\r\n"
                        "Accept: application/json\r\n"
                        "User-Agent: ViomiaBot/" + API_VERSION + "\r\n";

        uchar post[];
        if(method == "POST")
        {
            StringToCharArray(jsonData, post);
        }

        ArrayResize(result, 0);
        string response_headers;

        int res = WebRequest(
            method,
            url,
            headers,
            CONNECTION_TIMEOUT_MS,
            post,
            result,
            response_headers
        );

        if(res != -1)
        {
            if(ENABLE_LOGGING)
                Print("✅ API Request Success [", attempt + 1, "/", MAX_RETRY_ATTEMPTS, "] - ", endpoint);
            return res;
        }

        int err = GetLastError();
        if(ENABLE_LOGGING)
            Print("⚠️ API Request Failed [", attempt + 1, "/", MAX_RETRY_ATTEMPTS, "] - Error: ", err);
        
        ResetLastError();

        if(attempt < MAX_RETRY_ATTEMPTS - 1)
        {
            Sleep(RETRY_DELAY_MS);
        }
    }

    if(ENABLE_LOGGING)
        Print("❌ API Request Failed after ", MAX_RETRY_ATTEMPTS, " attempts - ", endpoint);
    
    return -1;
}

//===============================================================
// FUNCTION: Safe JSON Escape String
//===============================================================
string SafeJsonString(string text)
{
    // Escape special JSON characters
    text = StringSubstr(text, 0);
    
    // Replace backslashes first
    while(StringFind(text, "\\") >= 0)
        text = StringSetChar(text, StringFind(text, "\\"), '/');
    
    // Replace quotes
    while(StringFind(text, "\"") >= 0)
    {
        int pos = StringFind(text, "\"");
        text = StringSubstr(text, 0, pos) + "\\\"" + StringSubstr(text, pos + 1);
    }
    
    return text;
}

//===============================================================
// NEWS MODULE
//===============================================================

struct NewsEvent
{
    string currency;
    string event_name;
    datetime event_time;
    string impact;
    string forecast;
    string previous;
    string actual;
    int    notified;
};

#define MAX_NEWS 100
NewsEvent newsList[MAX_NEWS];
int newsCount = 0;

//+------------------------------------------------------------------+
//| Get Next News Event (with improved error handling)
//+------------------------------------------------------------------+
bool GetNextNewsEvent(string symbol = "USD")
{
#ifdef __TESTER__
    return GetNextNewsEventFromCSV(symbol);
#endif

    if(!ENABLE_SYNC) return false;

    string url = "/news/next?currency=" + symbol;

    uchar result[];

    if(SendApiRequest("GET", url, "", result) != -1)
    {
        string response = CharArrayToString(result);
        
        if(StringLen(response) == 0)
        {
            if(ENABLE_LOGGING)
                Print("⚠️ Empty response from server, using CSV fallback");
            return GetNextNewsEventFromCSV(symbol);
        }

        // Parse JSON response
        int idx;
        
        idx = StringFind(response, "\"currency\":\"");
        if(idx >= 0) 
            nextNews.currency = StringSubstr(response, idx + 12, StringFind(response, "\"", idx + 12) - (idx + 12));

        idx = StringFind(response, "\"event_name\":\"");
        if(idx >= 0) 
            nextNews.event_name = StringSubstr(response, idx + 14, StringFind(response, "\"", idx + 14) - (idx + 14));

        idx = StringFind(response, "\"event_time\":\"");
        if(idx >= 0) 
            nextNews.event_time = StringToTime(StringSubstr(response, idx + 14, StringFind(response, "\"", idx + 14) - (idx + 14)));

        idx = StringFind(response, "\"impact\":\"");
        if(idx >= 0) 
            nextNews.impact = StringSubstr(response, idx + 10, StringFind(response, "\"", idx + 10) - (idx + 10));

        nextNews.notified = 0;
        
        if(ENABLE_LOGGING)
            Print("📰 News event fetched: ", nextNews.event_name, " (", nextNews.currency, ")");
        
        return true;
    }

    return GetNextNewsEventFromCSV(symbol);
}

//+------------------------------------------------------------------+
//| Load News from CSV (Backtesting)
//+------------------------------------------------------------------+
bool LoadNewsFromCSV(string filename)
{
    int fileHandle = FileOpen(filename, FILE_READ | FILE_CSV | FILE_ANSI);
    if(fileHandle == INVALID_HANDLE)
    {
        Print("❌ Failed to open CSV file: ", filename);
        return false;
    }

    newsCount = 0;

    // Skip header
    FileReadString(fileHandle);

    while(!FileIsEnding(fileHandle) && newsCount < MAX_NEWS)
    {
        FileReadString(fileHandle); // id
        string currency = FileReadString(fileHandle);
        string event_name = FileReadString(fileHandle);
        string event_time_str = FileReadString(fileHandle);
        string impact = FileReadString(fileHandle);
        string notified_str = FileReadString(fileHandle);

        datetime event_time = StringToTime(event_time_str);

        newsList[newsCount].currency = currency;
        newsList[newsCount].event_name = event_name;
        newsList[newsCount].event_time = event_time;
        newsList[newsCount].impact = impact;
        newsList[newsCount].notified = (int)StringToInteger(notified_str);

        newsCount++;
    }

    FileClose(fileHandle);
    if(ENABLE_LOGGING)
        Print("📚 Loaded ", newsCount, " news events from CSV");
    
    return true;
}

//+------------------------------------------------------------------+
//| Get Next News from CSV with Fallback
//+------------------------------------------------------------------+
bool GetNextNewsEventFromCSV(string symbol)
{
    datetime now = TimeCurrent();
    bool found = false;
    datetime closestTime = 0;

    for(int i = 0; i < newsCount; i++)
    {
        if(newsList[i].currency != symbol) continue;
        
        if(newsList[i].event_time >= now)
        {
            if(!found || newsList[i].event_time < closestTime)
            {
                nextNews = newsList[i];
                closestTime = newsList[i].event_time;
                found = true;
            }
        }
    }

    return found;
}

//===============================================================
// TRADE & ACCOUNT SYNC MODULE (OPTIMIZED)
//===============================================================

//+------------------------------------------------------------------+
//| Send Trade Closed Event (OPTIMIZED)
//+------------------------------------------------------------------+
bool SendClosedTrade(
    ulong deal_id,
    double profit,
    string close_reason
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"ticket\":%I64u,"
        "\"profit\":%.2f,"
        "\"reason\":\"%s\""
        "}",
        deal_id,
        profit,
        SafeJsonString(close_reason)
    );

    uchar result[];

    if(SendApiRequest("POST", "/trade/log", json, result) != -1)
    {
        if(ENABLE_LOGGING)
            Print("✅ Trade closed: Ticket=", deal_id, " | Profit=", profit);
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Account Snapshot (OPTIMIZED)
//+------------------------------------------------------------------+
bool SendAccountSnapshot(string reason = "scheduled")
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    double balance = AccountInfoDouble(ACCOUNT_BALANCE);
    double equity = AccountInfoDouble(ACCOUNT_EQUITY);
    double margin = AccountInfoDouble(ACCOUNT_MARGIN);
    double freeMargin = AccountInfoDouble(ACCOUNT_MARGIN_FREE);
    int positions = PositionsTotal();

    string json = StringFormat(
        "{"
        "\"account\":%I64u,"
        "\"balance\":%.2f,"
        "\"equity\":%.2f,"
        "\"margin\":%.2f,"
        "\"free_margin\":%.2f,"
        "\"positions\":%d,"
        "\"reason\":\"%s\","
        "\"captured_at\":\"%s\""
        "}",
        (ulong)AccountInfoInteger(ACCOUNT_LOGIN),
        balance,
        equity,
        margin,
        freeMargin,
        positions,
        SafeJsonString(reason),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/account/snapshot", json, result) != -1)
    {
        if(ENABLE_LOGGING)
            Print("📊 Account snapshot sent | Balance=", balance, " | Equity=", equity);
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Trade Opened Event
//+------------------------------------------------------------------+
bool SendTradeOpened(
    ulong ticket,
    bool isBuy,
    double entry_price,
    double sl_price,
    double tp_price,
    double lot_size,
    string signal_source = "MANUAL"
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"ticket\":%I64u,"
        "\"direction\":\"%s\","
        "\"entry_price\":%.5f,"
        "\"sl_price\":%.5f,"
        "\"tp_price\":%.5f,"
        "\"lot_size\":%.2f,"
        "\"signal_source\":\"%s\","
        "\"opened_at\":\"%s\""
        "}",
        ticket,
        isBuy ? "BUY" : "SELL",
        entry_price,
        sl_price,
        tp_price,
        lot_size,
        SafeJsonString(signal_source),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/trade/opened", json, result) != -1)
    {
        if(ENABLE_LOGGING)
            Print("✅ Trade opened: Ticket=", ticket, " | ", (isBuy ? "BUY" : "SELL"), " | Lot=", lot_size);
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Daily Summary
//+------------------------------------------------------------------+
bool SendDailySummary(
    double daily_pl,
    int trades_count,
    int winning_trades,
    int losing_trades,
    double win_rate_percent,
    string summary_date
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    double balance = AccountInfoDouble(ACCOUNT_BALANCE);
    double equity = AccountInfoDouble(ACCOUNT_EQUITY);

    string json = StringFormat(
        "{"
        "\"daily_pl\":%.2f,"
        "\"trades_count\":%d,"
        "\"winning_trades\":%d,"
        "\"losing_trades\":%d,"
        "\"win_rate_percent\":%.2f,"
        "\"balance\":%.2f,"
        "\"equity\":%.2f,"
        "\"summary_date\":\"%s\","
        "\"captured_at\":\"%s\""
        "}",
        daily_pl,
        trades_count,
        winning_trades,
        losing_trades,
        win_rate_percent,
        balance,
        equity,
        summary_date,
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/trading/daily-summary", json, result) != -1)
    {
        if(ENABLE_LOGGING)
            Print("📈 Daily summary sent | P/L=", daily_pl, " | Trades=", trades_count, " | WR=", win_rate_percent, "%");
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Position Update (Live P/L)
//+------------------------------------------------------------------+
bool SendPositionUpdate(
    ulong ticket,
    double entry_price,
    double current_price,
    double unrealized_pl,
    double unrealized_pl_percent,
    double lot_size
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"ticket\":%I64u,"
        "\"entry_price\":%.5f,"
        "\"current_price\":%.5f,"
        "\"unrealized_pl\":%.2f,"
        "\"unrealized_pl_percent\":%.2f,"
        "\"lot_size\":%.2f,"
        "\"updated_at\":\"%s\""
        "}",
        ticket,
        entry_price,
        current_price,
        unrealized_pl,
        unrealized_pl_percent,
        lot_size,
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    // Silent success for frequent updates
    if(SendApiRequest("POST", "/position/update", json, result) != -1)
    {
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Daily Loss Limit Alert
//+------------------------------------------------------------------+
bool SendDailyLossLimitHit(
    double daily_loss,
    double daily_loss_limit,
    string limit_type
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"daily_loss\":%.2f,"
        "\"daily_loss_limit\":%.2f,"
        "\"limit_type\":\"%s\","
        "\"balance\":%.2f,"
        "\"equity\":%.2f,"
        "\"alert_at\":\"%s\""
        "}",
        daily_loss,
        daily_loss_limit,
        limit_type,
        AccountInfoDouble(ACCOUNT_BALANCE),
        AccountInfoDouble(ACCOUNT_EQUITY),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/alert/daily-loss-limit", json, result) != -1)
    {
        Print("🔴 CRITICAL: Daily loss limit hit! ", limit_type, " Loss: ", daily_loss);
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Filter Block Event
//+------------------------------------------------------------------+
bool SendSessionFilterBlock(
    string filter_type,
    string block_reason
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"filter_type\":\"%s\","
        "\"block_reason\":\"%s\","
        "\"blocked_at\":\"%s\""
        "}",
        filter_type,
        SafeJsonString(block_reason),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/filter/blocked", json, result) != -1)
    {
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Technical Signals
//+------------------------------------------------------------------+
bool SendTechnicalSignals(
    double trend_score,
    int choch_signal,
    double rsi_value,
    double atr_value,
    double ema_20_value,
    double ema_50_value,
    string signal_description
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string choch_label = (choch_signal == 1) ? "BULLISH_REVERSAL" : 
                         (choch_signal == -1) ? "BEARISH_REVERSAL" : "NO_REVERSAL";

    string json = StringFormat(
        "{"
        "\"trend_score\":%.2f,"
        "\"choch_signal\":\"%s\","
        "\"rsi_value\":%.2f,"
        "\"atr_value\":%.5f,"
        "\"ema_20\":%.5f,"
        "\"ema_50\":%.5f,"
        "\"signal_description\":\"%s\","
        "\"captured_at\":\"%s\""
        "}",
        trend_score,
        choch_label,
        rsi_value,
        atr_value,
        ema_20_value,
        ema_50_value,
        SafeJsonString(signal_description),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/signal/technical", json, result) != -1)
    {
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send EA Status Change
//+------------------------------------------------------------------+
bool SendEAStatusChange(
    string status,
    string reason,
    int consecutive_losses
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"status\":\"%s\","
        "\"reason\":\"%s\","
        "\"consecutive_losses\":%d,"
        "\"balance\":%.2f,"
        "\"equity\":%.2f,"
        "\"positions_open\":%d,"
        "\"changed_at\":\"%s\""
        "}",
        status,
        SafeJsonString(reason),
        consecutive_losses,
        AccountInfoDouble(ACCOUNT_BALANCE),
        AccountInfoDouble(ACCOUNT_EQUITY),
        PositionsTotal(),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/ea/status-change", json, result) != -1)
    {
        Print("🔔 EA Status: ", status, " | Reason: ", reason);
        return true;
    }

    return false;
}

//+------------------------------------------------------------------+
//| Send Error Event
//+------------------------------------------------------------------+
bool SendErrorEvent(
    string error_type,
    string error_message,
    double price_at_error = 0.0
)
{
#ifdef __TESTER__
    return true;
#endif

    if(!ENABLE_SYNC) return false;

    string json = StringFormat(
        "{"
        "\"error_type\":\"%s\","
        "\"error_message\":\"%s\","
        "\"price_at_error\":%.5f,"
        "\"balance\":%.2f,"
        "\"equity\":%.2f,"
        "\"error_at\":\"%s\""
        "}",
        error_type,
        SafeJsonString(error_message),
        price_at_error,
        AccountInfoDouble(ACCOUNT_BALANCE),
        AccountInfoDouble(ACCOUNT_EQUITY),
        TimeToString(TimeCurrent(), TIME_DATE | TIME_SECONDS)
    );

    uchar result[];

    if(SendApiRequest("POST", "/error/log", json, result) != -1)
    {
        Print("⚠️ ERROR: ", error_type, " - ", error_message);
        return true;
    }

    return false;
}

//===============================================================
// END OF OPTIMIZED BOT MODULE
//===============================================================
