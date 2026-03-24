# 🔍 VIOMIA EA: COMPLETE SIGNAL DETECTION & SCORING ANALYSIS

## 1. SIGNAL FLOW ARCHITECTURE (From Detection to Execution)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         MARKET DATA INPUT LAYER                              │
│  CopyRates(TF=M5, 500 bars) → Price[0..499]                                 │
│  Current Bid/Ask, ATR(14), RSI(14), Account State                            │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                    🎯 STAGE 1: STRUCTURE ANALYSIS                            │
│  File: Structure/Structure.mqh → DetectStructureAndZones()                  │
├─────────────────────────────────────────────────────────────────────────────┤
│ Input: price[1..20] with 500-bar swings lookback                            │
│ Output: resistance1, support1 (most recent swing high/low)                  │
│                                                                              │
│ METHOD:                                                                      │
│ 1. Scan last 500 bars for swing points                                      │
│    - swingHighs: price[i].high > price[i±lookback].high                     │
│    - swingLows: price[i].low < price[i±lookback].low                        │
│ 2. Extract last swing high → resistance1                                     │
│ 3. Extract last swing low → support1                                         │
│                                                                              │
│ ⚠️ CRITICAL: These are the liquidity ZONES where signals activate            │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                  🎯 STAGE 2: TREND BIAS CONFIRMATION                         │
│  File: Structure/AdvancedTrend.mqh → GetAdvancedTrendBias()                 │
├─────────────────────────────────────────────────────────────────────────────┤
│ Multi-Timeframe Scoring Algorithm (0-100 scale):                            │
│                                                                              │
│ Per Timeframe (M5, M15, H1):                                                │
│ ├─ EMA Trend (20/50 crossover): +15/-15 points                              │
│ ├─ Close Momentum (C0 vs C20): +15/-15 points                               │
│ ├─ Structure Score (HH/HL pattern): +20/-20 points                          │
│ ├─ RSI Momentum (>55 or <45): +10/-10 points                                │
│ ├─ RSI Extreme Filter (>70 or <30): +25/-25 penalty                         │
│ └─ ATR Volatility (increasing/decreasing): ±10 points                       │
│                                                                              │
│ Final Weighting:                                                             │
│  H1 Score × 0.40 (primary trend)                                            │
│  M15 Score × 0.40 (intermediate confirmation)                               │
│  M5 Score × 0.20 (entry timeframe)                                          │
│                                                                              │
│ OUTPUT:                                                                      │
│  finalScore >= 60 → Return +1 (BUY TREND)                                   │
│  finalScore <= 40 → Return -1 (SELL TREND)                                  │
│  40 < finalScore < 60 → Return 0 (NEUTRAL - REJECT SIGNAL)                  │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
                          🚫 EARLY REJECTION POINT 1
                  If trendBias = 0 (Neutral), skip completely
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│              🎯 STAGE 3: ENTRY SIGNAL GENERATION & SCORING                   │
│  File: Strategy/Entry_Scalping.mqh → BuildEntrySignal()                     │
├─────────────────────────────────────────────────────────────────────────────┤
│ SCORING COMPONENTS (threshold = 2.3):                                       │
│                                                                              │
│ 🔴 HARD BLOCKS (Automatic rejection):                                       │
│  ├─ RSI > 70 + trendBias=+1 (BUY) → BLOCK (overbought)                     │
│  ├─ RSI < 30 + trendBias=-1 (SELL) → BLOCK (oversold)                      │
│  └─ body < 50% of candle range → BLOCK (weak displacement)                  │
│                                                                              │
│ 🟢 SCORING FACTORS:                                                         │
│                                                                              │
│ 1. SWEEP + BOS PATTERN (Weight: 1.0)                                        │
│    ├─ Bullish: price[prev].low < support1 AND price[idx].close > prev.high │
│    │   → buyScore += 1.0                                                    │
│    ├─ Bearish: price[prev].high > resistance1 AND price[idx].close < prev.low │
│    │   → sellScore += 1.0                                                   │
│    └─ Meaning: Liquidity sweep (wick below/above structure) + break         │
│               (close through previous candle) = professional entry pattern  │
│                                                                              │
│ 2. REJECTION CANDLES (Weight: 0.5 each)                                     │
│    ├─ Bullish Rejection:                                                    │
│    │  - Requires: body > 0 AND lowerWick > body                             │
│    │  - AND: price[idx].low near support1 (within 25% of candle range)      │
│    │  → buyScore += 0.5                                                     │
│    │  Means: Price rejected downward, bounced up = reversal at SR           │
│    │                                                                         │
│    ├─ Bearish Rejection:                                                    │
│    │  - Requires: body > 0 AND upperWick > body                             │
│    │  - AND: price[idx].high near resistance1 (within 25% range)            │
│    │  → sellScore += 0.5                                                    │
│    │  Means: Price rejected upward, fell down = reversal at SR              │
│    └─ Proximity calc: abs(price.low - support) <= 0.25 * candle_range      │
│                                                                              │
│ 3. ATR / VOLATILITY FILTER (Weight: 0-2.0)                                  │
│    ├─ If ATR_points >= ATR_Min (5.0):                                       │
│    │  atrWeight = MIN(2.0, atrPts / ATR_Min)                                │
│    │  If trendBias = +1: buyScore += atrWeight                              │
│    │  If trendBias = -1: sellScore += atrWeight                             │
│    └─ Meaning: Sufficient volatility confirms readiness for move            │
│                                                                              │
│ 4. DISPLACEMENT RATIO CHECK (Hard block):                                   │
│    ├─ body = |close - open|                                                 │
│    ├─ candleRange = high - low                                              │
│    ├─ Requires: body >= candleRange × MinDisplacementRatio (0.4)            │
│    └─ Meaning: Candle must have strong body, not just wicks (quality entry) │
│                                                                              │
│ FINAL DECISION:                                                              │
│  If (buyScore >= threshold 2.3) AND (trendBias = +1) → Return 1 (BUY)       │
│  If (sellScore >= threshold 2.3) AND (trendBias = -1) → Return -1 (SELL)    │
│  Otherwise → Return 0 (NO SIGNAL)                                           │
│                                                                              │
│ Global score storage for AI:                                                │
│  g_buyScore = buyScore, g_sellScore = sellScore                             │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
                          🚫 EARLY REJECTION POINT 2
                   If BuildEntrySignal returns 0, skip
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│                  🎯 STAGE 4: AI SIGNAL ENHANCEMENT                           │
│  File: web/AiBridge_Enhanced.mqh → SendCandleToVIOMIA_Enhanced()            │
├─────────────────────────────────────────────────────────────────────────────┤
│ PARALLEL AI ANALYSIS (Independent from EA):                                 │
│                                                                              │
│ Input to AI (JSON payload):                                                 │
│  {                                                                          │
│    "symbol": "EURUSD",                                                      │
│    "price": 1.08456,                                                        │
│    "rsi": 52.3,                                                             │
│    "atr": 0.0052,                                                           │
│    "trend": 1,              (from GetAdvancedTrendBias: -1, 0, or +1)       │
│    "resistance": 1.08500,   (from DetectStructureAndZones)                  │
│    "support": 1.08200,      (from DetectStructureAndZones)                  │
│    "session": 1,            (1=London 07-12, 2=US 13-18)                   │
│    "account_id": "12345",                                                   │
│    "candles": [             (last 10 candles)                               │
│      {open, high, low, close, volume, time},                               │
│      ...                                                                    │
│    ]                                                                        │
│  }                                                                          │
│                                                                              │
│ AI Response (from VIOMIA backend):                                          │
│  {                                                                          │
│    "decision": "BUY|SELL|NO_TRADE",                                         │
│    "confidence": 0.75,          (0.0 to 1.0)                                │
│    "score": 72,                 (0 to 100+)                                 │
│    "entry": 1.08456,            (suggested entry)                           │
│    "stop_loss": 1.08200,        (suggested SL)                              │
│    "take_profit": 1.08960,      (suggested TP)                              │
│    "regime": "TRENDING|RANGING|VOLATILE",                                   │
│    "reasons": "EMA aligned, structure break, momentum..."                   │
│  }                                                                          │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
        🚫 EARLY REJECTION POINT 3a: AI Unavailable or Failed
         If response.success = false → Use technical only
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│              🎯 STAGE 4b: AI CONFIDENCE GATE VALIDATION                      │
│  Main.mq5 (OnTick) → AI Response validation                                 │
├─────────────────────────────────────────────────────────────────────────────┤
│ GATE 1: Confidence Threshold                                                │
│  If aiResponse.confidence < 0.60 (60%) → REJECT SIGNAL                      │
│  Print: "AI GATE BLOCKED | Confidence %.2f < 0.60 threshold"                │
│                                                                              │
│ GATE 2: AI Decision                                                         │
│  If aiResponse.decision = "NO_TRADE" → REJECT (AI explicitly says no)       │
│  Print: "AI GATE BLOCKED | AI decision is NO_TRADE"                         │
│                                                                              │
│ GATE 3: Direction Alignment                                                 │
│  If (technicalBUY && aiSaysSELL) OR (technicalSELL && aiSaysBUY)            │
│  → REJECT (Directional opposition)                                          │
│  Print: "AI OPPOSITION BLOCKED | Technical=BUY vs AI=SELL"                  │
│                                                                              │
│ ✅ ALL GATES PASSED:                                                        │
│  Print: "AI GATE APPROVED | Confidence %.2f | Decision=%s"                  │
│  → Continue to Stage 5                                                      │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
          🚫 EARLY REJECTION POINT 4: Correlation Filter
                    (CheckTradeCorrelation)
├─────────────────────────────────────────────────────────────────────────────┤
│ PREVENTS CLUSTERING: Same direction trades within CorrelationExpiryMinutes   │
│  (default: 240 minutes = 4 hours)                                           │
│                                                                              │
│ Logic:                                                                       │
│  1. Scan deal history backward for last 2 trades within time window         │
│  2. Count: buyCount, sellCount                                              │
│  3. If newSignal is BUY, but last 2 trades were BUYS → REJECT               │
│  4. If newSignal is SELL, but last 2 trades were SELLS → REJECT             │
│                                                                              │
│ Purpose: Prevent overload risk, enforce diversity                           │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
         🚫 EARLY REJECTION POINT 5: News Filter
                    (IsNewsTime)
├─────────────────────────────────────────────────────────────────────────────┤
│ BLOCKS: High/Medium impact news events (configurable)                        │
│         BlockBeforeNewsMin: 15 minutes (default)                            │
│         BlockAfterNewsMin: 15 minutes (default)                             │
│                                                                              │
│ Filter: Gets symbol base/quote, checks against nextNews.currency            │
│         Blocks if matching currency has high/medium impact event            │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
       🚫 EARLY REJECTION POINT 6: Session Time Filter
                    (IsTradingTime)
├─────────────────────────────────────────────────────────────────────────────┤
│ PRIMARY WINDOWS (When trades are allowed):                                   │
│  ✅ London Open: 10:00-12:00 (if UseLondonSession=true)                     │
│  ✅ US Session: 14:00-23:30 (strongest edge)                                │
│                                                                              │
│ BLOCKS:                                                                      │
│  ❌ 09:00-09:30 (London volatility spike)                                    │
│  ❌ Asia hours (02:00-05:30, etc)                                            │
│  ❌ Dead zones (rest of day)                                                 │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│              🎯 STAGE 5: MARKET & RISK VALIDATION                            │
├─────────────────────────────────────────────────────────────────────────────┤
│ CHECK 1: Bid/Ask Validity                                                   │
│  ├─ Current bid/ask > 0                                                     │
│  ├─ Spread check: (ask - bid) / point <= MaxSpreadPoints (0 = no limit)     │
│  └─ Reject if violated                                                      │
│                                                                              │
│ CHECK 2: Position Limit                                                     │
│  ├─ CountPositionsEA() >= MaxPositions (default: 1)                         │
│  └─ Reject if at limit                                                      │
│                                                                              │
│ CHECK 3: Cooldown Timer                                                     │
│  ├─ TimeCurrent() < NextTradeTime                                           │
│  ├─ Set to: TimeCurrent() + 1800 seconds (30 minutes)                       │
│  └─ Reject if in cooldown                                                   │
│                                                                              │
│ CHECK 4: Stop Loss Calculation & Validation                                 │
│  ├─ If BUY:                                                                 │
│  │  SL = support1 > 0 ? support1 - buffer : entry - minSL                   │
│  │  Ensure: SL < entry AND distance >= brokerMinDistance                    │
│  │                                                                          │
│  ├─ If SELL:                                                                │
│  │  SL = resistance1 > 0 ? resistance1 + buffer : entry + minSL             │
│  │  Ensure: SL > entry AND distance >= brokerMinDistance                    │
│  │                                                                          │
│  ├─ Risk distance validation:                                               │
│  │  riskDist = |entry - SL|                                                │
│  │  Reject if: riskDist <= 0 OR riskDist/point > maxAllowedPoints (1000)   │
│  └─ Buffer: 20 points, minSL: 500 points (brokerStopLevel aware)            │
│                                                                              │
│ CHECK 5: Take Profit Calculation                                            │
│  ├─ TP = entry + (riskDist × RiskReward)                                    │
│  ├─ If TP > MaxTP_Pips (3000) → Cap it                                      │
│  ├─ If Asian session → Cap at AsianTP_Points (1000)                         │
│  └─ TP must be on correct side (BUY TP > entry, SELL TP < entry)           │
│                                                                              │
│ CHECK 6: Lot Size Calculation & Validation (Risk Management)                │
│  ├─ equity = AccountInfoDouble(ACCOUNT_EQUITY)                              │
│  ├─ riskAmount = equity × RiskPercent / 100 (default: 2%)                   │
│  ├─ lotSize = riskAmount / (distance × pip_value)                           │
│  ├─ Normalize to broker steps (SymbolInfoDouble SYMBOL_VOLUME_STEP)         │
│  ├─ Validate: lotSize >= SYMBOL_VOLUME_MIN, <= SYMBOL_VOLUME_MAX            │
│  └─ Each trade risks approx 2% of equity per the formula                    │
│                                                                              │
│ CHECK 7: Margin Verification                                                │
│  ├─ freeMargin = AccountInfoDouble(ACCOUNT_MARGIN_FREE)                     │
│  ├─ requiredMargin = SymbolInfoDouble(SYMBOL_MARGIN_INITIAL) × lotSize      │
│  ├─ Reject if: freeMargin < requiredMargin                                  │
│  └─ Ensures sufficient margin to open position                              │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│           🎯 STAGE 6: SIGNAL VALIDATION GATE (Optional)                      │
│  File: web/P0_SignalValidationGate.mqh → ValidateEASignal()                 │
├─────────────────────────────────────────────────────────────────────────────┤
│ COMPREHENSIVE DOUBLE-CHECK (not always called, can enhance):                │
│                                                                              │
│ Checks include:                                                              │
│  1. Symbol validity (approved list: EURUSD, GBPUSD, etc)                   │
│  2. Entry price within ±5% of current market                                │
│  3. RR Ratio between 0.8:1 and 10:1                                         │
│  4. Lot size risk <= 2% of account equity                                    │
│  5. Enough margin available                                                 │
│  6. AI confidence boost (if available)                                       │
│                                                                              │
│ Output:                                                                      │
│  SignalValidationResult with:                                               │
│   - is_valid (bool)                                                         │
│   - confidence_score (0-100%)                                               │
│   - severity (ERROR, WARNING, OK)                                           │
│   - reason (descriptive string)                                             │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│              🎯 STAGE 7: TRADE EXECUTION                                     │
│  File: Trade/OrderSend.mqh → PlaceMarketOrder()                             │
├─────────────────────────────────────────────────────────────────────────────┤
│ EXECUTION SEQUENCE:                                                         │
│  1. Send BUY/SELL order via CTrade class                                    │
│  2. Get ticket number                                                       │
│  3. Build signal JSON with account_id (CRITICAL P0-1A FIX)                  │
│  4. Call SendSignalToLaravel() → POST /signal endpoint                      │
│  5. Database creates Signal AND TradeLog record (Stage 1 of 2-stage)        │
│  6. Call SendTradeOpened() → POST /trade/opened (secondary event log)       │
│  7. Set NextTradeTime cooldown (30 minutes)                                 │
│  8. Update EA status in database                                            │
│                                                                              │
│ JSON Format Sent (BuildSignalJson):                                         │
│  {                                                                          │
│    "account_id": 12345,         ✅ CRITICAL: Pass account_id                │
│    "symbol": "EURUSD",                                                      │
│    "direction": "buy",                                                      │
│    "ticket": "987654",                                                      │
│    "lots": 0.10,                                                            │
│    "entry": 1.08456,                                                        │
│    "sl": 1.08200,                                                           │
│    "tp": 1.08960,                                                           │
│    "timeframe": "M5",                                                       │
│    "active": true,                                                          │
│    "created_at": "2026-03-24 15:30:45"                                      │
│  }                                                                          │
└─────────────────────────────────────────────────────────────────────────────┘
                                      ↓
┌─────────────────────────────────────────────────────────────────────────────┐
│            🎯 STAGE 8: TRADE CLOSURE & OUTCOME DELIVERY                      │
│  File: Viomia.mq5 (OnTradeTransaction) + web/AiOutcome.mqh                 │
├─────────────────────────────────────────────────────────────────────────────┤
│ WHEN TRADE CLOSES (deal settlement):                                        │
│                                                                              │
│ 1. OnTradeTransaction triggered with TRADE_TRANSACTION_DEAL_ADD             │
│ 2. Extract deal details:                                                    │
│    - dealTicket (execution id)                                              │
│    - dealProfit (actual P&L)                                                │
│    - reason (TP/SL/manual)                                                  │
│    - positionId (DEAL_POSITION_ID)                                          │
│                                                                              │
│ 3. Loss Tracking (with smart downtime handling):                            │
│    - If loss AND NOT during account downtime → consecutiveLosses++          │
│    - If 3 consecutive losses → TradeEnabled = false (auto-stop)             │
│    - If account comes back online → Reset counters                          │
│                                                                              │
│ 4. Send Trade Closure (Stage 2 of 2-stage process):                         │
│    POST /trade/log with account_id (CRITICAL)                               │
│    {                                                                        │
│      "account_id": 12345,      ✅ CRITICAL: Pass account_id                 │
│      "ticket": "987654",                                                    │
│      "close_price": 1.08500,                                                │
│      "closed_lots": 0.10,                                                   │
│      "profit": 50.00,                                                       │
│      "reason": "TP"                                                         │
│    }                                                                        │
│                                                                              │
│ 5. Update Account Snapshot:                                                 │
│    POST /account/snapshot                                                   │
│    {                                                                        │
│      "account": 12345,                                                      │
│      "balance", "equity", "margin", "free_margin",                          │
│      "reason": "trade_closed"                                               │
│    }                                                                        │
│                                                                              │
│ 6. Send Outcome to AI (Learning):                                           │
│    POST /ai/training (to VIOMIA backend)                                    │
│    {                                                                        │
│      "account_id": "12345",    ✅ CRITICAL: Always pass                      │
│      "symbol": "EURUSD",                                                    │
│      "direction": "BUY",                                                    │
│      "entry": 1.08456,                                                      │
│      "close": 1.08500,                                                      │
│      "profit": 50.00,                                                       │
│      "reason": "TP",                                                        │
│      "duration": 25                                                         │
│    }                                                                        │
│                                                                              │
│ 7. Revalidate Account Settings:                                             │
│    - Cached check every 60 seconds (prevents spam on failures)              │
│    - If online → continue trading                                           │
│    - If offline → keep last known state (no cascade failures)                │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. COMPLETE SIGNAL SCORING BREAKDOWN

### Required Patterns for Valid Signal

**A Signal is VALID only if ALL of these conditions are true:**

#### 🔴 HARD REQUIREMENTS (Knockout Criteria)
```
1. Trend Bias Status
   ├─ Must be +1 (BUY trend) OR -1 (SELL trend)
   ├─ Cannot be 0 (neutral)
   └─ Determined by: AdvancedTrendBias() - multi-timeframe consensus

2. RSI Safety Checks
   ├─ If trendBias = +1 (BUY): RSI must be < 70 (not overbought)
   ├─ If trendBias = -1 (SELL): RSI must be > 30 (not oversold)
   └─ Hard block: Any violation kills signal immediately

3. Candle Displacement
   ├─ body = |close - open| must be >= 40% of candle range
   ├─ purpose: Ensures strong directional conviction, not noise
   └─ weak candles (>60% wick, <40% body) are rejected

4. Price Action Confirmation
   ├─ Signal score must be >= 2.3 (threshold)
   ├─ Cannot succeed on "Maybe" signals
   └─ Each pattern component contributes to this score
```

#### 🟡 SCORING FACTORS (Build the 2.3+ score)

```
PATTERN 1: Sweep + Break of Structure (Score: 1.0)
│
├─ BULLISH SETUP (for BUY signal):
│   ├─ Condition 1: price[prev].low < support1 (liquidity sweep down)
│   ├─ Condition 2: price[idx].close > price[prev].high (wick break up)
│   └─ Result: buyScore += 1.0
│   
│   Explanation: Predatory liquidity sweep stops out shorts, then breaks up
│   Why it works: Professionals use this to flush stops before rallies
│
└─ BEARISH SETUP (for SELL signal):
    ├─ Condition 1: price[prev].high > resistance1 (liquidity sweep up)
    ├─ Condition 2: price[idx].close < price[prev].low (wick break down)
    └─ Result: sellScore += 1.0
    
    Explanation: Sweep long stops, then break down structure


PATTERN 2: Rejection Candles at Support/Resistance (Score: 0.5 each)
│
├─ BULLISH REJECTION:
│   ├─ Candle structure: Close > Open (bullish body)
│   ├─ Lower wick > Body (strong rejection from below)
│   ├─ Proximity: abs(low - support1) <= 25% of candle range
│   └─ Result: buyScore += 0.5
│   
│   Example: Price falls to support, creates 20-pip lower wick, closes up
│   Why it works: Shows strong support zone, reversal likely
│
└─ BEARISH REJECTION:
    ├─ Candle structure: Open > Close (bearish body)
    ├─ Upper wick > Body (strong rejection from above)
    ├─ Proximity: abs(high - resistance1) <= 25% of candle range
    └─ Result: sellScore += 0.5
    
    Example: Price rallies to resistance, creates 20-pip upper wick, closes down


PATTERN 3: ATR / Volatility Confirmation (Score: 0.0 to 2.0)
│
├─ Requirement: ATR(14) >= ATR_Min (5.0 pips)
├─ Weight formula: min(2.0, atr_pips / ATR_Min)
│
├─ Example 1: ATR = 15 pips
│   └─ Weight = min(2.0, 15/5) = 2.0 (max boost)
│
├─ Example 2: ATR = 8 pips
│   └─ Weight = min(2.0, 8/5) = 1.6
│
└─ Example 3: ATR = 3 pips
    └─ Weight = 0 (too low volatility, no boost)
    
    Purpose: Only add boost if market condition is active (sufficient movement)
```

#### 📊 EXAMPLE SCORING SCENARIOS

**Scenario 1: STRONG BUY SETUP**
```
Trend: +1 (bullish bias confirmed)
Price[prev].low = 1.07900 (dips below support 1.07950)  ✓ Sweep detected
Price[idx].close = 1.08120 > Price[prev].high 1.08050  ✓ BOS confirmed
Candle body = 0.0045, range = 0.0120 → 37.5% (> 40%? NO - BLOCK)
→ REJECTED: Weak displacement despite patterns

---

Trend: +1 (bullish bias)
Price[prev].low = 1.07900 < support 1.07950          ✓ Sweep
Price[idx].close = 1.08120 > Price[prev].high 1.08050 ✓ BOS
Candle body = 0.0060, range = 0.0120 → 50% (✓ OK)
ATR = 12 pips → Weight = min(2.0, 12/5) = 2.0
RSI = 55 (< 70? ✓)

buyScore = 1.0 (sweep+BOS) + 2.0 (ATR) = 3.0 >= 2.3 ✓ VALID BUY
```

**Scenario 2: MEDIUM BUY WITH REJECTION**
```
Trend: +1
Sweep+BOS: Present → buyScore += 1.0
Bullish Rejection near support: ✓ → buyScore += 0.5
ATR = 6 pips → Weight = min(2.0, 6/5) = 1.2
RSI = 45 (< 70? ✓)

buyScore = 1.0 + 0.5 + 1.2 = 2.7 >= 2.3 ✓ VALID BUY
```

**Scenario 3: WEAK SIGNAL - REJECTED**
```
Trend: +1
Sweep+BOS: Present → buyScore = 1.0
No rejection candle detected → +0
ATR = 3 pips → Weight = 0 (too low)
RSI = 52

buyScore = 1.0 <= 2.3 ✗ INVALID (insufficient buildup)
```

---

## 3. AI ENHANCEMENT & VALIDATION LAYERS

### Layer 1: Technical to AI Enhancement (P0_AISignalEnhancer)

```
Technical Score (from BuildEntrySignal)
         ↓
AI calls backend with candle + indicators
         ↓
AI returns confidence (0-100%)
         ↓
BLEND:
  final_score = 0.60 × tech_score + 0.40 × (ai_confidence / 100 × 10)
         ↓
if ai_confidence >= 70% → "🟢 HIGH confidence"
if ai_confidence >= 50% → "🟡 MEDIUM confidence"
if ai_confidence < 50% → "🔴 LOW confidence"
```

### Layer 2: AI Confidence Gate (Main flow)

```
AI confidence < 60% → REJECT signal
AI says "NO_TRADE" → REJECT signal
AI says opposite direction → REJECT signal
Otherwise → APPROVE and proceed
```

### Layer 3: Optional Signal Validation Gate (P0_SignalValidationGate)

```
Symbol approved?
Entry price realistic (±5% of mid)?
RR ratio acceptable (0.8:1 to 10:1)?
Lot size safe (≤2% account risk)?
Margin available?
AI confidence reinforced?

All pass → Confidence score = blend of checks
Any fail → Rejected with reason
```

---

## 4. FILTER CHAIN (Sequential Rejections)

```
1. Trend Bias Filter (must be ±1, not 0)
                ↓
2. Structure Detection (find S/R zones)
                ↓
3. Entry Signal Scoring (must reach 2.3+)
                ↓
4. AI Analysis (sends data to remote backend)
                ↓
5. AI Confidence Gate (must be >60%, direction must align)
                ↓
6. Correlation Filter (no same-direction clustering)
                ↓
7. News Filter (avoid high-impact economic releases)
                ↓
8. Session Filter (only trade liquid hours: 10-12 or 14-23)
                ↓
9. Market Conditions (spread, bid/ask, position limit, cooldown)
                ↓
10. Risk Management (SL/TP validity, lot size, margin)
                ↓
11. Account Status (must be active in database)
                ↓
12. Order Execution (send to broker)
```

---

## 5. THE 2-STAGE TRADE SYSTEM

### Stage 1: Trade Opening (When signal fires)
```
EA detects pattern → BuildEntrySignal()
AI validates → SendCandleToVIOMIA_Enhanced()
Creates TradeLog in database via: /signal endpoint
Sends trade event via: /trade/opened endpoint
```

### Stage 2: Trade Closing (When SL/TP hit)
```
Broker closes position (deal settlement)
OnTradeTransaction captures close data
Updates TradeLog in database via: /trade/log endpoint
Sends outcome to AI for learning: /ai/training endpoint
Syncs account snapshot: /account/snapshot endpoint
```

**CRITICAL**: Both stages must include `account_id` to associate trades with account.

---

## 6. KEY VARIABLES & THRESHOLDS

| Variable | Value | Purpose |
|----------|-------|---------|
| TF | M5 | Entry timeframe (fast signals) |
| HTF_TF | M15 | Higher timeframe (TP reference) |
| Threshold | 2.3 | Min score to generate signal |
| MinDisplacementRatio | 0.4 | Min 40% body/range for candles |
| RiskPercent | 2.0 | Risk 2% equity per trade |
| RiskReward | 3.0 | TP = SL distance × 3 |
| MaxPositions | 1 | Only one trade open at a time |
| MaxDailyLossPercent | 30.0 | Stop trading if -30% equity |
| CorrelationExpiryMinutes | 240 | 4-hour clustering prevention |
| BlockBeforeNewsMin | 15 | Block 15 min before news |
| BlockAfterNewsMin | 15 | Block 15 min after news |
| ATR_Min | 5.0 | Min 5 pips volatility for boost |
| MaxSpreadPoints | 0.0 | No spread limit (0 = disabled) |
| SignalPollMs | 1000 | Check for signals every 1 second |
| ACCOUNT_SETTINGS_CACHE_SECONDS | 60 | Refresh account settings every 60s |

---

## 7. WHAT I SEE IN YOUR EA

### ✅ STRENGTHS
1. **Robust multi-layered approach**: 7-12 filters before execution
2. **AI integration**: Secondary validation from independent AI system
3. **Structure-based entries**: Uses professional SMC patterns (sweep + BOS)
4. **Risk management**: Fixed 2% risk per trade, controlled position sizing
5. **Account safety**: Consecutive loss protection, daily loss limits
6. **Technical rigor**: Trend bias multi-TF, ATR filters, RSI safety checks
7. **Operational resilience**: Caching on connection failures, smart retry logic
8. **Trade tracking**: 2-stage system ensures complete trade lifecycle recording

### ⚠️ OBSERVATIONS
1. **High signal filtering**: The 2.3 threshold + multiple gates means few signals get through (this is good for quality, bad for activity)
2. **AI dependency**: Signal rejection if AI is unavailable or low confidence (robustness: handles it, but relies on external system)
3. **Time zone fixed**: Built around US/London sessions primarily (XAUUSD focus)
4. **Candle precision**: Needs strong body (40%+ of range) - weak candles = no signal
5. **Correlation lock-down**: 4-hour prevention of same-direction trades = max 6 trades/day if perfectly spaced

### 🎯 TRADING PHILOSOPHY
Your EA implements **"Quality over Quantity"** - it waits for:
- Clear trend confirmation (multi-TF alignment)
- Professional entry pattern (sweep + BOS or rejection)
- AI validation (external confidence)
- Proper risk/reward setup (3:1 minimum)
- Safe market conditions (liquidity, no news)

This is a **structured trading bot** that prioritizes **precision entries** over **high trade frequency**.

---

## 8. PATTERN REQUIREMENTS SUMMARY

```
┌─────────────────────────────────────────────────────┐
│     MINIMUM VALID SIGNAL REQUIREMENTS               │
├─────────────────────────────────────────────────────┤
│                                                     │
│ 1. Trend Bias: Must be +1 or -1 (not 0)            │
│                                                     │
│ 2. Price Action Score: >= 2.3                      │
│    Builds from:                                     │
│    - Sweep+BOS (1.0) OR Rejection (0.5)            │
│    - ATR boost (0-2.0 based on volatility)         │
│                                                     │
│ 3. Candle Quality: Body >= 40% of range            │
│                                                     │
│ 4. RSI Safety:                                      │
│    BUY: RSI < 70                                    │
│    SELL: RSI > 30                                   │
│                                                     │
│ 5. AI Confidence: >= 60% (if available)            │
│                                                     │
│ 6. Direction Alignment: AI must agree with EA      │
│                                                     │
│ 7. Account Status: active=true in database         │
│                                                     │
└─────────────────────────────────────────────────────┘
```

