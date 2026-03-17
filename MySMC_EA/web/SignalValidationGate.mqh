/**
 * MySMC_EA/web/SignalValidationGate.mqh
 * 
 * Validates signals before execution
 * Solves P0-6: "No Signal Validation Before Execution"
 * 
 * Usage:
 *   if (!ValidateSignalPreExecution(symbol, entry, sl, tp, lot, balance, equity, margin))
 *       return;  // Signal rejected
 */

#property strict

struct SignalValidation
{
    bool valid;
    string reason;
    int http_code;
};

/**
 * Validates signal before any trade execution
 * 
 * @param symbol       - Trading symbol (EURUSD, XAUUSD, etc)
 * @param entry        - Entry price
 * @param sl           - Stop Loss price
 * @param tp           - Take Profit price
 * @param lot          - Lot size
 * @param balance      - Account balance
 * @param equity       - Account equity
 * @param available_margin - Available margin
 * 
 * @return true if valid, false if any validation fails
 */
bool ValidateSignalPreExecution(
    string symbol,
    double entry,
    double sl,
    double tp,
    double lot,
    double balance,
    double equity,
    double available_margin
)
{
    SignalValidation result = SendSignalValidationRequest(
        symbol, entry, sl, tp, lot, balance, equity, available_margin
    );
    
    if (!result.valid)
    {
        Print("SIGNAL VALIDATION FAILED: ", result.reason);
        return false;
    }
    
    Print("Signal validated successfully");
    return true;
}

/**
 * Sends validation request to Laravel API
 */
SignalValidation SendSignalValidationRequest(
    string symbol,
    double entry,
    double sl,
    double tp,
    double lot,
    double balance,
    double equity,
    double available_margin
)
{
    string json = BuildSignalValidationPayload(
        symbol, entry, sl, tp, lot, balance, equity, available_margin
    );
    
    string headers = "Content-Type: application/json\r\n";
    headers += ("X-API-KEY: " + InpApiKey + "\r\n");
    
    string url = InpServerUrl + "/api/bot/validate-signal";
    
    // Use WebRequest with timeout
    char post_body[];
    char post_result[];
    StringToCharArray(json, post_body, 0, StringLen(json));
    
    int timeout_ms = 400;  // 400ms timeout for validation
    int response_code = 0;
    
    ResetLastError();
    response_code = WebRequest(
        "POST",
        url,
        headers,
        timeout_ms,
        post_body,
        post_result
    );
    
    SignalValidation validation;
    
    if (response_code == 200)
    {
        // Parse successful response
        string response = CharArrayToString(post_result);
        validation.valid = true;
        validation.reason = "Signal passed all validations";
        validation.http_code = 200;
        return validation;
    }
    else if (response_code == 422)
    {
        // Parse error response
        string response = CharArrayToString(post_result);
        validation.valid = false;
        validation.reason = "Signal validation failed: " + response;
        validation.http_code = 422;
        Print("Validation response: ", response);
        return validation;
    }
    else if (response_code == -1)
    {
        // Timeout or network error
        int error = GetLastError();
        validation.valid = false;
        validation.reason = "Validation request timeout/network error (" + error + ")";
        validation.http_code = -1;
        Print("WebRequest error: ", error);
        return validation;
    }
    else
    {
        // Unknown error
        validation.valid = false;
        validation.reason = StringFormat("Unexpected response code: %d", response_code);
        validation.http_code = response_code;
        return validation;
    }
}

/**
 * Builds JSON payload for signal validation
 */
string BuildSignalValidationPayload(
    string symbol,
    double entry,
    double sl,
    double tp,
    double lot,
    double balance,
    double equity,
    double available_margin
)
{
    // Round to appropriate decimals
    string entry_str = DoubleToString(entry, 5);
    string sl_str = DoubleToString(sl, 5);
    string tp_str = DoubleToString(tp, 5);
    string lot_str = DoubleToString(lot, 2);
    string balance_str = DoubleToString(balance, 2);
    string equity_str = DoubleToString(equity, 2);
    string margin_str = DoubleToString(available_margin, 2);
    
    string json = "{";
    json += "\"account_id\":\"" + InpAccountId + "\",";
    json += "\"symbol\":\"" + symbol + "\",";
    json += "\"entry_price\":" + entry_str + ",";
    json += "\"stop_loss\":" + sl_str + ",";
    json += "\"take_profit\":" + tp_str + ",";
    json += "\"lot_size\":" + lot_str + ",";
    json += "\"account_balance\":" + balance_str + ",";
    json += "\"account_equity\":" + equity_str + ",";
    json += "\"available_margin\":" + margin_str;
    json += "}";
    
    return json;
}

/**
 * Helper to convert char array to string
 */
string CharArrayToString(char &arr[])
{
    string result = "";
    int size = ArraySize(arr);
    
    for (int i = 0; i < size; i++)
    {
        if (arr[i] == 0) break;
        result += CharToString(arr[i]);
    }
    
    return result;
}
