#!/bin/bash
# VIOMIA Trade Outcome API Testing Script
# 
# Purpose: Test all trade outcome endpoints with realistic data
# Usage: bash test_trade_outcome_api.sh
# 
# Requires: curl command-line tool
# Configuration: Update API_URL and API_KEY if different

# ==========================================
# CONFIGURATION
# ==========================================

API_URL="http://94.72.112.148:8011/api/bot"
API_KEY="TEST_API_KEY_123"
ACCOUNT_ID="102734606"

# Colors for output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}═══════════════════════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}VIOMIA Trade Outcome API Testing${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════════════════════${NC}\n"

# ==========================================
# TEST 1: POST Trade Outcome (Save)
# ==========================================
echo -e "${BLUE}TEST 1: POST Trade Outcome (Save to Database)${NC}"
echo -e "${BLUE}──────────────────────────────────────────────${NC}\n"

TICKET=$((RANDOM * 1000 + 1))

echo "Creating test outcome with ticket: $TICKET"
echo ""

RESPONSE=$(curl -s -X POST "${API_URL}/trade/outcome" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: ${API_KEY}" \
  -d "{
    \"ticket\": $TICKET,
    \"account_id\": \"${ACCOUNT_ID}\",
    \"symbol\": \"EURUSD\",
    \"decision\": \"BUY\",
    \"entry\": 1.0850,
    \"sl\": 1.0825,
    \"tp\": 1.0900,
    \"close_price\": 1.0875,
    \"profit\": 42.50,
    \"close_reason\": \"TP_HIT\",
    \"duration_mins\": 45,
    \"result\": \"WIN\",
    \"rsi\": 72.3,
    \"atr\": 0.0048,
    \"trend\": 1,
    \"session\": 2,
    \"bos\": 1,
    \"liquidity_sweep\": 0,
    \"equal_highs\": 1,
    \"equal_lows\": 0,
    \"volume_spike\": 1,
    \"dxy_trend\": 0,
    \"risk_off\": 0
  }")

echo -e "${GREEN}Response:${NC}"
echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
echo ""

# ==========================================
# TEST 2: GET Trade Outcome by Ticket
# ==========================================
echo -e "${BLUE}TEST 2: GET Trade Outcome by Ticket${NC}"
echo -e "${BLUE}─────────────────────────────────────${NC}\n"

echo "Retrieving outcome for ticket: $TICKET"
echo ""

RESPONSE=$(curl -s -X GET "${API_URL}/trade/outcome/${TICKET}" \
  -H "X-API-KEY: ${API_KEY}")

echo -e "${GREEN}Response:${NC}"
echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
echo ""

# ==========================================
# TEST 3: GET Performance Statistics
# ==========================================
echo -e "${BLUE}TEST 3: GET Performance Statistics${NC}"
echo -e "${BLUE}──────────────────────────────────${NC}\n"

echo "Getting stats for last 30 days"
echo ""

RESPONSE=$(curl -s -X GET "${API_URL}/trade/outcome/stats?days=30" \
  -H "X-API-KEY: ${API_KEY}")

echo -e "${GREEN}Response:${NC}"
echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
echo ""

# ==========================================
# TEST 4: GET Pattern Analysis
# ==========================================
echo -e "${BLUE}TEST 4: GET Pattern Analysis${NC}"
echo -e "${BLUE}─────────────────────────────${NC}\n"

for pattern in "bos" "liquidity_sweep" "equal_highs" "equal_lows" "volume_spike"; do
    echo "Analyzing pattern: $pattern"
    
    RESPONSE=$(curl -s -X GET "${API_URL}/trade/outcome/pattern-analysis?pattern=${pattern}" \
      -H "X-API-KEY: ${API_KEY}")
    
    echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
    echo ""
done

# ==========================================
# TEST 5: Test Lost Trade Outcome
# ==========================================
echo -e "${BLUE}TEST 5: POST Lost Trade Outcome${NC}"
echo -e "${BLUE}────────────────────────────────${NC}\n"

LOSS_TICKET=$((RANDOM * 1000 + 2000))

echo "Creating losing trade outcome with ticket: $LOSS_TICKET"
echo ""

RESPONSE=$(curl -s -X POST "${API_URL}/trade/outcome" \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: ${API_KEY}" \
  -d "{
    \"ticket\": $LOSS_TICKET,
    \"account_id\": \"${ACCOUNT_ID}\",
    \"symbol\": \"GBPUSD\",
    \"decision\": \"SELL\",
    \"entry\": 1.2750,
    \"sl\": 1.2800,
    \"tp\": 1.2600,
    \"close_price\": 1.2790,
    \"profit\": -40.00,
    \"close_reason\": \"SL_HIT\",
    \"duration_mins\": 30,
    \"result\": \"LOSS\",
    \"rsi\": 35.2,
    \"atr\": 0.0052,
    \"trend\": -1,
    \"session\": 1,
    \"bos\": 0,
    \"liquidity_sweep\": 1,
    \"equal_highs\": 0,
    \"equal_lows\": 1,
    \"volume_spike\": 0,
    \"dxy_trend\": 1,
    \"risk_off\": 1
  }")

echo -e "${GREEN}Response:${NC}"
echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
echo ""

# ==========================================
# TEST 6: Verify Both Outcomes in Stats
# ==========================================
echo -e "${BLUE}TEST 6: Verify Both Outcomes in Stats${NC}"
echo -e "${BLUE}──────────────────────────────────────${NC}\n"

echo "Getting updated stats (should show 1 WIN + 1 LOSS)"
echo ""

RESPONSE=$(curl -s -X GET "${API_URL}/trade/outcome/stats?days=1" \
  -H "X-API-KEY: ${API_KEY}")

echo -e "${GREEN}Response:${NC}"
echo "$RESPONSE" | jq . 2>/dev/null || echo "$RESPONSE"
echo ""

# ==========================================
# SUMMARY
# ==========================================
echo -e "${BLUE}═══════════════════════════════════════════════════════════════════════════════${NC}"
echo -e "${BLUE}TEST SUMMARY${NC}"
echo -e "${BLUE}═══════════════════════════════════════════════════════════════════════════════${NC}\n"

echo -e "${GREEN}✅ All tests completed!${NC}\n"

echo "Created test outcomes:"
echo "  - Winning trade (BOS pattern): Ticket $TICKET"
echo "  - Losing trade (Equal levels): Ticket $LOSS_TICKET"
echo ""

echo "To verify in database, run:"
echo '  php artisan tinker'
echo '  >>> ViomiaTradeOutcome::whereIn("ticket", ['$TICKET', '$LOSS_TICKET'])->get()'
echo ""

echo "To get all outcomes:"
echo '  >>> ViomiaTradeOutcome::all()'
echo ""

echo "═══════════════════════════════════════════════════════════════════════════════"
echo ""
echo "For more details, see: P0_1_TRADE_OUTCOME_FIX_IMPLEMENTATION.md"
echo ""
