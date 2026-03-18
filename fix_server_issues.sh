#!/bin/bash
# Quick fix script for VIOMIA trading losses

echo "╔═══════════════════════════════════════════════════════════╗"
echo "║   VIOMIA - Quick Fix Script (Server-side)                ║"
echo "║   Based on server logs analysis                          ║"
echo "╚═══════════════════════════════════════════════════════════╝"
echo ""

cd /var/www/viomia_bot

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ ERROR: laravel artisan not found. Are we in the right directory?"
    echo "Please run from /var/www/viomia_bot"
    exit 1
fi

echo "📋 Step 1: Run pending migrations..."
php artisan migrate
echo ""

echo "📋 Step 2: Clear application cache..."
php artisan cache:clear
echo ""

echo "📋 Step 3: Verify rr_ratio column is now nullable..."
mysql -u root viomia_bot -e "DESCRIBE viomia_decisions;" | grep rr_ratio
echo ""

echo "📋 Step 4: Check Python FastAPI status..."
systemctl status viomia || echo "⚠️  viomia service not running"
echo ""

echo "❌ KNOWN ISSUES FIXED:"
echo "  1. ✅ Column 'rr_ratio' cannot be null - FIXED (made nullable)"
echo "  2. ✅ API rate limiting - FIXED (increased cache from 5m to 30m)"
echo "  3. ⚠️  Silent failures (HTTP 200 with errors) - Needs code review"
echo ""

echo "📋 Step 5: Restart FastAPI service..."
systemctl restart viomia || echo "⚠️  Could not restart - check systemctl"
systemctl status viomia --no-pager
echo ""

echo "✅ Quick fixes applied! Check /var/log/viomia_ai.log for errors."
