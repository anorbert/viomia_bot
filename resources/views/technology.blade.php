@extends('layouts.app')

@section('content')
<style>
    body {
        background: #0f0f0f;
        color: #b0b0b0;
    }

    .navbar {
        background: rgba(15, 15, 15, 0.95);
        border-bottom: 1px solid rgba(0, 168, 132, 0.1);
    }

    .technology-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .tech-header {
        text-align: center;
        margin-bottom: 60px;
        padding: 40px 20px;
        background: linear-gradient(135deg, rgba(0, 109, 91, 0.15) 0%, rgba(0, 43, 36, 0.15) 100%);
        border-left: 4px solid #006d5b;
        border-right: 4px solid #002b24;
        border-radius: 8px;
    }

    .tech-header h1 {
        color: white;
        font-size: 32px;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .tech-header p {
        color: #b0b0b0;
        font-size: 16px;
        line-height: 1.6;
    }

    .tech-section {
        margin-bottom: 50px;
        padding: 30px;
        background: rgba(26, 26, 26, 0.8);
        border-left: 4px solid #006d5b;
        border-radius: 8px;
    }

    .tech-section h2 {
        color: #00a884;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .tech-section h2 i {
        color: #006d5b;
        margin-right: 12px;
        font-size: 24px;
    }

    .tech-section h3 {
        color: white;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 12px;
    }

    .tech-section h3:first-child {
        margin-top: 0;
    }

    .tech-section p {
        color: #b0b0b0;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .tech-section ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 15px;
    }

    .tech-section li {
        color: #b0b0b0;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 10px;
        padding-left: 25px;
        position: relative;
    }

    .tech-section li:before {
        content: "▸";
        position: absolute;
        left: 0;
        color: #00a884;
        font-weight: bold;
    }

    .tech-stack-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: rgba(34, 34, 34, 0.5);
    }

    .tech-stack-table thead {
        background: rgba(0, 109, 91, 0.2);
    }

    .tech-stack-table th {
        color: #00a884;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #006d5b;
    }

    .tech-stack-table td {
        color: #b0b0b0;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(0, 168, 132, 0.1);
    }

    .tech-stack-table tr:hover {
        background: rgba(0, 168, 132, 0.05);
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .feature-card {
        padding: 20px;
        background: rgba(34, 34, 34, 0.8);
        border-left: 3px solid #00a884;
        border-radius: 4px;
    }

    .feature-card h4 {
        color: white;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .feature-card h4 i {
        color: #00a884;
        margin-right: 10px;
    }

    .feature-card p {
        font-size: 13px;
        color: #999;
        margin: 0;
    }

    .architecture-box {
        background: rgba(34, 34, 34, 0.8);
        border: 1px solid #006d5b;
        padding: 30px;
        margin: 20px 0;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #00a884;
        overflow-x: auto;
        line-height: 1.6;
    }

    .code-block {
        background: rgba(0, 0, 0, 0.5);
        border: 1px solid #006d5b;
        padding: 15px;
        margin: 15px 0;
        border-radius: 4px;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        color: #00a884;
        overflow-x: auto;
    }

    .metrics-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: rgba(34, 34, 34, 0.5);
    }

    .metrics-table th {
        color: #00a884;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #006d5b;
        background: rgba(0, 109, 91, 0.2);
    }

    .metrics-table td {
        color: #b0b0b0;
        padding: 12px 15px;
        border-bottom: 1px solid rgba(0, 168, 132, 0.1);
    }

    .highlight-box {
        background: rgba(0, 168, 132, 0.1);
        border-left: 4px solid #00a884;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }

    .highlight-box p {
        color: #00a884;
        margin: 0;
        font-weight: 500;
    }

    .cta-footer {
        text-align: center;
        margin-top: 60px;
        padding: 40px 20px;
        background: rgba(26, 26, 26, 0.8);
        border-radius: 8px;
    }

    .cta-footer h3 {
        color: white;
        margin-bottom: 20px;
    }

    .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 168, 132, 0.2);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: #00a884;
        padding: 12px 30px;
        border: 1px solid #00a884;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: rgba(0, 168, 132, 0.1);
    }

    @media (max-width: 768px) {
        .technology-container {
            padding: 40px 15px;
        }

        .tech-header h1 {
            font-size: 24px;
        }

        .tech-section {
            padding: 20px;
        }

        .tech-section h2 {
            font-size: 18px;
        }

        .tech-stack-table {
            font-size: 12px;
        }

        .feature-grid {
            grid-template-columns: 1fr;
        }

        .architecture-box {
            font-size: 11px;
            padding: 20px;
        }
    }
</style>

<div class="technology-container">
    <!-- Header -->
    <div class="tech-header">
        <h1>Technology Stack & Infrastructure</h1>
        <p>Enterprise-grade technology powering Viomia's algorithmic trading system</p>
    </div>

    <!-- Trading Platform Section -->
    <div class="tech-section">
        <h2><i class="fas fa-exchange-alt"></i>Trading Platform</h2>
        
        <h3>MetaTrader 5 (MT5)</h3>
        <p>
            MetaTrader 5 is the world's most popular algorithmic trading platform, trusted by institutional traders worldwide. 
            It provides a robust execution engine, historical data access, and real-time market connectivity.
        </p>
        <ul>
            <li><strong>Platform:</strong> Supports Forex, Commodities, Stocks, and Cryptocurrencies</li>
            <li><strong>Execution Speed:</strong> Sub-millisecond order processing</li>
            <li><strong>Historical Data:</strong> Years of tick-level market data for backtesting</li>
            <li><strong>Live Trading:</strong> Direct connection to all major brokers and exchanges</li>
            <li><strong>Multi-Currency Support:</strong> Trade across dozens of currency pairs and instruments</li>
        </ul>
    </div>

    <!-- Scalability Section -->
    <div class="tech-section">
        <h2><i class="fas fa-expand"></i>Scalability & Multi-Account</h2>

        <h3>Multi-Account Support</h3>
        <p>Viomia supports running multiple trading bots across different accounts and brokers simultaneously.</p>
        <ul>
            <li><strong>Account Isolation:</strong> Each account operates independently with separate risk limits</li>
            <li><strong>Broker Flexibility:</strong> Connect to Binance, Coinbase, Kraken, Bybit, and other exchanges</li>
            <li><strong>Concurrent Trading:</strong> Multiple bots can trade simultaneously without conflicts</li>
            <li><strong>Centralized Dashboard:</strong> Monitor all accounts from a single interface</li>
        </ul>

        <h3>Load Balancing</h3>
        <p>Cloud infrastructure scales automatically to handle increased load:</p>
        <ul>
            <li>Distributed database architecture for high throughput</li>
            <li>Caching layers to reduce API response times</li>
            <li>Queue systems for asynchronous task processing</li>
            <li>Auto-scaling based on server load</li>
        </ul>
    </div>

    <!-- Performance Metrics Section -->
    <div class="tech-section">
        <h2><i class="fas fa-chart-bar"></i>Performance Metrics Framework</h2>

        <p>Viomia tracks comprehensive performance metrics to help you evaluate system effectiveness:</p>

        <table class="metrics-table">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Definition</th>
                    <th>Target</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Win Rate</strong></td>
                    <td>Percentage of profitable trades</td>
                    <td>40-55%</td>
                </tr>
                <tr>
                    <td><strong>Profit Factor</strong></td>
                    <td>Gross profit ÷ Gross loss</td>
                    <td>> 1.5</td>
                </tr>
                <tr>
                    <td><strong>Average Win</strong></td>
                    <td>Average profit per winning trade</td>
                    <td>Variable</td>
                </tr>
                <tr>
                    <td><strong>Average Loss</strong></td>
                    <td>Average loss per losing trade</td>
                    <td>1-2% of account</td>
                </tr>
                <tr>
                    <td><strong>Risk-Reward Ratio</strong></td>
                    <td>Avg win size ÷ Avg loss size</td>
                    <td>1:3 minimum</td>
                </tr>
                <tr>
                    <td><strong>Maximum Drawdown</strong></td>
                    <td>Largest peak-to-trough decline</td>
                    <td>< 20%</td>
                </tr>
                <tr>
                    <td><strong>Sharpe Ratio</strong></td>
                    <td>Risk-adjusted returns</td>
                    <td>> 1.0</td>
                </tr>
                <tr>
                    <td><strong>Trade Count</strong></td>
                    <td>Number of trades per week</td>
                    <td>5-15</td>
                </tr>
            </tbody>
        </table>

        <h3>Real-Time Dashboard Metrics</h3>
        <p>The Viomia dashboard displays these metrics updated in real-time:</p>
        <div class="highlight-box">
            <p>✓ Current account balance and equity | ✓ Today's P&L and win rate | ✓ Open positions | ✓ Recent trades | ✓ Risk utilization | ✓ Monthly performance</p>
        </div>
    </div>

    <!-- Backtesting Section -->
    <div class="tech-section">
        <h2><i class="fas fa-flask"></i>Backtesting Methodology</h2>

        <h3>Historical Testing</h3>
        <p>Before going live, Viomia's algorithms are thoroughly backtested using years of historical market data.</p>
        <ul>
            <li><strong>Data Source:</strong> Tick-level historical data from MetaTrader 5 servers</li>
            <li><strong>Time Period:</strong> Typically 2-5+ years of data to cover various market cycles</li>
            <li><strong>Testing Framework:</strong> MQL5 Strategy Tester with realistic market conditions</li>
            <li><strong>Slippage Simulation:</strong> Includes estimated execution slippage and spreads</li>
        </ul>

        <h3>Backtesting Report</h3>
        <p>Each backtest generates a detailed report including:</p>
        <ul>
            <li>Total trades tested and results</li>
            <li>Win rate and profit factor</li>
            <li>Maximum consecutive wins and losses</li>
            <li>Drawdown analysis and recovery times</li>
            <li>Performance by market conditions (trending, ranging, volatile)</li>
        </ul>

        <h3>Limitations</h3>
        <p>Important: Backtesting results have limitations:</p>
        <ul>
            <li>Historical data cannot perfectly predict future markets</li>
            <li>Backtesting cannot account for all real-time execution issues</li>
            <li>Market structure changes over time reduce historical relevance</li>
            <li>Results may be overfitted to the tested time period</li>
        </ul>
    </div>

    <!-- Live Testing Section -->
    <div class="tech-section">
        <h2><i class="fas fa-eye"></i>Forward Testing & Live Performance</h2>

        <h3>Forward Testing</h3>
        <p>After backtesting approves an algorithm, it undergoes forward testing with real market conditions:</p>
        <ul>
            <li><strong>Paper Trading:</strong> Trades are executed on paper (not real money) to verify algorithm behavior</li>
            <li><strong>Real Execution:</strong> Uses actual market prices and broker execution speed</li>
            <li><strong>Duration:</strong> Typically 1-3 months to capture various market conditions</li>
            <li><strong>Monitoring:</strong> Real-time monitoring for unexpected behavior or bugs</li>
        </ul>

        <h3>Live Performance Tracking</h3>
        <p>Once live trading begins, continuous monitoring ensures:</p>
        <ul>
            <li>Actual execution prices match historical expectations</li>
            <li>No abnormal slippage or execution issues</li>
            <li>Risk management systems are working correctly</li>
            <li>System alerts function properly</li>
        </ul>
    </div>

    <!-- Call to Action Footer -->
    <div class="cta-footer">
        <h3>Want to Learn More About Technology?</h3>
        <p>Explore our help section or contact our technical team for detailed architecture discussions.</p>
        <div class="cta-buttons">
            <a href="/" class="btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="/help" class="btn-secondary">
                <i class="fas fa-question-circle"></i> Help & Support
            </a>
            <a href="https://wa.me/0787373722" target="_blank" class="btn-primary">
                <i class="fas fa-comments"></i> Contact Us
            </a>
        </div>
    </div>
</div>

<style>
    footer {
        margin-top: 80px;
    }
</style>
@endsection
