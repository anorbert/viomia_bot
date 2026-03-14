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

    .risk-disclosure-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 60px 20px;
    }

    .disclosure-header {
        text-align: center;
        margin-bottom: 60px;
        padding: 40px 20px;
        background: linear-gradient(135deg, rgba(0, 109, 91, 0.15) 0%, rgba(0, 43, 36, 0.15) 100%);
        border-left: 4px solid #006d5b;
        border-right: 4px solid #002b24;
        border-radius: 8px;
    }

    .disclosure-header h1 {
        color: white;
        font-size: 32px;
        margin-bottom: 15px;
        font-weight: 700;
    }

    .disclosure-header p {
        color: #b0b0b0;
        font-size: 16px;
        line-height: 1.6;
    }

    .disclosure-section {
        margin-bottom: 50px;
        padding: 30px;
        background: rgba(26, 26, 26, 0.8);
        border-left: 4px solid #006d5b;
        border-radius: 8px;
    }

    .disclosure-section h2 {
        color: #00a884;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .disclosure-section h2 i {
        color: #006d5b;
        margin-right: 12px;
        font-size: 24px;
    }

    .disclosure-section h3 {
        color: white;
        font-size: 16px;
        font-weight: 600;
        margin-top: 20px;
        margin-bottom: 12px;
    }

    .disclosure-section h3:first-child {
        margin-top: 0;
    }

    .disclosure-section p {
        color: #b0b0b0;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .disclosure-section ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 15px;
    }

    .disclosure-section li {
        color: #b0b0b0;
        font-size: 14px;
        line-height: 1.8;
        margin-bottom: 10px;
        padding-left: 25px;
        position: relative;
    }

    .disclosure-section li:before {
        content: "▸";
        position: absolute;
        left: 0;
        color: #00a884;
        font-weight: bold;
    }

    .warning-box {
        background: rgba(220, 53, 69, 0.1);
        border-left: 4px solid #dc3545;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }

    .warning-box p {
        color: #ff9999;
        margin: 0;
        font-weight: 500;
    }

    .info-box {
        background: rgba(0, 168, 132, 0.1);
        border-left: 4px solid #00a884;
        padding: 20px;
        margin: 20px 0;
        border-radius: 4px;
    }

    .info-box p {
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

    .cta-footer p {
        color: #b0b0b0;
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
        .risk-disclosure-container {
            padding: 40px 15px;
        }

        .disclosure-header h1 {
            font-size: 24px;
        }

        .disclosure-section {
            padding: 20px;
        }

        .disclosure-section h2 {
            font-size: 18px;
        }
    }
</style>

<div class="risk-disclosure-container">
    <!-- Header -->
    <div class="disclosure-header">
        <h1>Risk Disclosure & Legal Notice</h1>
        <p>Important information about trading risks, system limitations, and regulatory compliance</p>
    </div>

    <!-- Executive Disclaimer -->
    <div class="disclosure-section">
        <h2><i class="fas fa-exclamation-triangle"></i>Executive Disclaimer</h2>
        
        <div class="warning-box">
            <p><strong>⚠️ CRITICAL:</strong> Viomia is an automated trading system. It is NOT a regulated investment fund, financial advisor, or money management service.</p>
        </div>

        <h3><i class="fas fa-money-bill" style="color: #00a884; margin-right: 8px;"></i>No Guarantees on Profitability</h3>
        <p>
            <strong>Past performance does not guarantee future results.</strong> The Viomia Team make no guarantees, representations, or warranties regarding:
        </p>
        <ul>
            <li>Future profitability or investment returns</li>
            <li>Specific profit targets or performance metrics</li>
            <li>That the strategy will work during all market conditions</li>
            <li>That backtesting results will replicate in live trading</li>
        </ul>

        <h3><i class="fas fa-exclamation-circle" style="color: #ff9999; margin-right: 8px;"></i>System Use is at Your Risk</h3>
        <p>You use Viomia entirely at your own risk. The Viomia Team assume no liability for losses incurred through:</p>
        <ul>
            <li>Trading decisions made by the automated system</li>
            <li>Market volatility or unexpected price movements</li>
            <li>Broker execution issues or slippage</li>
            <li>System errors, bugs, or technical failures</li>
            <li>Your interpretation or use of the system</li>
        </ul>
    </div>

    <!-- Risk Disclosure Statement -->
    <div class="disclosure-section">
        <h2><i class="fas fa-chart-line"></i>Risk Disclosure Statement</h2>
        
        <p>
            Trading in financial markets, especially cryptocurrencies, carries substantial risk of loss. Before using Viomia, you must understand and accept the following risks:
        </p>

        <h3><i class="fas fa-chart-line" style="color: #00a884; margin-right: 8px;"></i>Market Risks</h3>
        <ul>
            <li><strong>Price Volatility:</strong> Cryptocurrency prices can swing 10-50% in a single day</li>
            <li><strong>Market Gaps:</strong> Markets can gap past your stop-loss orders</li>
            <li><strong>Black Swan Events:</strong> Unexpected geopolitical events can cause extreme moves</li>
            <li><strong>Liquidity Risk:</strong> Some trading pairs may have limited liquidity</li>
            <li><strong>Regulatory Risk:</strong> Government actions can cause market crashes</li>
        </ul>

        <h3><i class="fas fa-server" style="color: #00a884; margin-right: 8px;"></i>Technology Risks</h3>
        <ul>
            <li><strong>Internet Connection:</strong> Trading requires stable internet; disconnections can result in losses</li>
            <li><strong>System Failures:</strong> Computer crashes, power outages, or hardware failures</li>
            <li><strong>API Failures:</strong> Exchange API issues may prevent order submission or cancellation</li>
            <li><strong>Software Bugs:</strong> Despite testing, bugs may exist causing unintended trades</li>
            <li><strong>Hacking Risk:</strong> API keys could be compromised if not properly secured</li>
        </ul>

        <h3><i class="fas fa-exchange-alt" style="color: #00a884; margin-right: 8px;"></i>Broker/Exchange Risks</h3>
        <ul>
            <li><strong>Execution Slippage:</strong> Actual filled prices may be much worse than expected</li>
            <li><strong>Broker Bankruptcy:</strong> Exchange or broker failure could result in loss of funds</li>
            <li><strong>Withdrawal Delays:</strong> Difficulty withdrawing funds from exchanges</li>
            <li><strong>Account Restrictions:</strong> Exchanges may freeze accounts or reverse trades</li>
        </ul>

        <h3><i class="fas fa-wallet" style="color: #ff9999; margin-right: 8px;"></i>Capital Risk</h3>
        <ul>
            <li><strong>Total Loss Risk:</strong> You could lose your entire account balance</li>
            <li><strong>Leverage Risk (if used):</strong> Leveraged trading amplifies losses</li>
            <li><strong>Forced Liquidations:</strong> Position may be liquidated by the broker</li>
        </ul>
    </div>

    <!-- System Limitations -->
    <div class="disclosure-section">
        <h2><i class="fas fa-tools"></i>System Limitations</h2>

        <h3><i class="fas fa-sliders-h" style="color: #00a884; margin-right: 8px;"></i>Strategy Limitations</h3>
        <ul>
            <li>The SMC strategy has a ~40-55% win rate - <strong>you will lose money on 45-60% of trades</strong></li>
            <li>Drawdowns of 15-20% are normal and expected</li>
            <li>Strategy may underperform in ranging (sideways) markets</li>
            <li>Strategy requires adequate volatility to generate signals</li>
        </ul>

        <h3><i class="fas fa-filter" style="color: #00a884; margin-right: 8px;"></i>Market Protection Filters</h3>
        <p>While Viomia uses multiple protection filters, they are NOT foolproof:</p>
        <ul>
            <li><strong>News Filter:</strong> May miss sudden unscheduled announcements</li>
            <li><strong>Session Filter:</strong> Low-liquidity hours may still have unexpected moves</li>
            <li><strong>Spread Filter:</strong> Spreads can widen faster than filtering occurs</li>
            <li><strong>Risk Management:</strong> Stop-losses may not be filled at intended prices</li>
        </ul>

        <h3><i class="fas fa-flask" style="color: #00a884; margin-right: 8px;"></i>Backtesting Limitations</h3>
        <ul>
            <li>Backtests use historical data that may not represent future conditions</li>
            <li>Backtesting cannot account for slippage or execution delays</li>
            <li>Results may be overfitted to historical data</li>
            <li>Live trading performance may differ from backtest results</li>
        </ul>
    </div>

    <!-- No Investment Advice -->
    <div class="disclosure-section">
        <h2><i class="fas fa-ban"></i>Not Investment Advice</h2>
        
        <div class="info-box">
            <p><strong>ℹ️</strong> Viomia and its Viomia Team are NOT providing investment advice, financial recommendations, or portfolio management services.</p>
        </div>

        <p>You are solely responsible for:</p>
        <ul>
            <li>Your trading decisions and strategy selection</li>
            <li>Determining if Viomia is suitable for your financial situation</li>
            <li>Consulting with a qualified financial advisor if needed</li>
            <li>Understanding the risks of algorithmic trading</li>
            <li>Complying with local trading regulations in your jurisdiction</li>
        </ul>
    </div>

    <!-- Software Usage Terms -->
    <div class="disclosure-section">
        <h2><i class="fas fa-file-contract"></i>Software Usage Terms</h2>

        <h3><i class="fas fa-check-circle" style="color: #00a884; margin-right: 8px;"></i>Permitted Use</h3>
        <ul>
            <li>Personal, non-commercial use only</li>
            <li>Use with compatible exchanges (Binance, Coinbase, Kraken, etc.)</li>
            <li>Compliance with all exchange and broker terms of service</li>
        </ul>

        <h3><i class="fas fa-stop-circle" style="color: #ff9999; margin-right: 8px;"></i>Prohibited Activities</h3>
        <ul>
            <li>Redistributing, selling, or sharing the software</li>
            <li>Reverse engineering or modifying the source code</li>
            <li>Using the system for unauthorized commercial purposes</li>
            <li>Attempting to circumvent licensing or usage restrictions</li>
            <li>Using API keys or credentials that don't belong to you</li>
        </ul>

        <h3><i class="fas fa-times-circle" style="color: #ff9999; margin-right: 8px;"></i>License Termination</h3>
        <p>Viomia reserves the right to disable access if you:</p>
        <ul>
            <li>Violate these terms</li>
            <li>Use the system fraudulently or illegally</li>
            <li>Fail to pay subscription fees</li>
            <li>Attempt to harm the system or other users</li>
        </ul>
    </div>

    <!-- Data Privacy & Security -->
    <div class="disclosure-section">
        <h2><i class="fas fa-lock"></i>Data Privacy & Security</h2>

        <h3><i class="fas fa-database" style="color: #00a884; margin-right: 8px;"></i>What Data We Collect</h3>
        <ul>
            <li>Account username and email address</li>
            <li>API keys (encrypted and secured)</li>
            <li>Trading data (trades, positions, performance metrics)</li>
            <li>System logs and error reports</li>
        </ul>

        <h3><i class="fas fa-shield-alt" style="color: #00a884; margin-right: 8px;"></i>Data Security Measures</h3>
        <ul>
            <li>API keys are encrypted using industry-standard encryption</li>
            <li>Secure communication via HTTPS only</li>
            <li>We never have access to your exchange funds</li>
            <li>Regular security audits and updates</li>
        </ul>

        <h3><i class="fas fa-user-check" style="color: #00a884; margin-right: 8px;"></i>Your Responsibility</h3>
        <ul>
            <li>Keep your credentials and passwords secure</li>
            <li>Use API keys with restricted permissions (trading only, no withdrawal)</li>
            <li>Never share API keys or passwords</li>
            <li>Change passwords regularly</li>
        </ul>
    </div>

    <!-- Compliance Notice -->
    <div class="disclosure-section">
        <h2><i class="fas fa-gavel"></i>Compliance & Regulations</h2>

        <h3><i class="fas fa-ban" style="color: #ff9999; margin-right: 8px;"></i>Not a Regulated Investment Service</h3>
        <p>
            Viomia is NOT a regulated investment fund, brokerage, or financial institution. We do not:
        </p>
        <ul>
            <li>Manage client funds or hold customer assets</li>
            <li>Provide investment advisory services</li>
            <li>Fall under financial regulation in most jurisdictions</li>
            <li>Have insurance protection for your trading losses</li>
        </ul>

        <h3><i class="fas fa-gavel" style="color: #00a884; margin-right: 8px;"></i>Regulatory Compliance</h3>
        <p>Users are responsible for:</p>
        <ul>
            <li>Complying with cryptocurrency regulations in their country</li>
            <li>Reporting trading gains/losses for tax purposes</li>
            <li>Verifying that trading is legal in their jurisdiction</li>
            <li>Complying with their broker or exchange terms of service</li>
        </ul>

        <h3><i class="fas fa-globe" style="color: #00a884; margin-right: 8px;"></i>No Jurisdiction Specific Advice</h3>
        <p>These terms apply globally. Cryptocurrency regulations vary by country. You are solely responsible for understanding local requirements.</p>
    </div>

    <!-- Intellectual Property -->
    <div class="disclosure-section">
        <h2><i class="fas fa-copyright"></i>Intellectual Property</h2>

        <h3><i class="fas fa-copyright" style="color: #00a884; margin-right: 8px;"></i>Viomia Ownership</h3>
        <ul>
            <li>All Viomia software, algorithms, and strategy is proprietary</li>
            <li>You receive a license to use, not ownership of the system</li>
            <li>Copying, cloning, or reverse engineering is prohibited</li>
            <li>Unauthorized commercial use will be prosecuted</li>
        </ul>

        <h3><i class="fas fa-key" style="color: #00a884; margin-right: 8px;"></i>Your Data Rights</h3>
        <ul>
            <li>You retain ownership of your trading data</li>
            <li>We may use anonymized data for system improvements</li>
            <li>You can request data deletion (within legal limits)</li>
        </ul>
    </div>

    <!-- System Maintenance & Updates -->
    <div class="disclosure-section">
        <h2><i class="fas fa-sync"></i>System Maintenance & Updates</h2>

        <h3><i class="fas fa-tools" style="color: #00a884; margin-right: 8px;"></i>Scheduled Maintenance</h3>
        <p>Viomia may have scheduled maintenance periods during which the service is unavailable. We will provide advance notice when possible.</p>

        <h3>Emergency Maintenance</h3>
        <p>Critical security issues may require immediate server updates that temporarily disable the system.</p>

        <h3>Feature Updates</h3>
        <ul>
            <li>Software updates may change strategy behavior</li>
            <li>New filters or settings may be added automatically</li>
            <li>You are responsible for reviewing release notes</li>
            <li>Opt-in/opt-out features will be offered when available</li>
        </ul>

        <h3>Discontinued Service</h3>
        <p>While we aim to provide continuous service, Viomia may be discontinued. We will provide at least 30 days notice when possible.</p>
    </div>

    <!-- Final Disclaimer -->
    <div class="disclosure-section">
        <h2><i class="fas fa-warning"></i>Final Statement</h2>

        <div class="warning-box">
            <p>
                <strong>YOU ACKNOWLEDGE AND AGREE THAT:</strong><br><br>
                By using Viomia, you accept all risks associated with algorithmic trading. 
                You understand that past performance does not guarantee future results. 
                You assume full responsibility for trading decisions and financial outcomes. 
                The Viomia Team accept no liability for losses incurred through use of Viomia.
            </p>
        </div>

        <p>
            If you do not agree with these terms and understand the risks, <strong>DO NOT USE VIOMIA.</strong> 
            Trading should only be done with capital you can afford to lose completely.
        </p>

        <p style="margin-top: 30px; color: #999; font-size: 12px;">
            <strong>Last Updated:</strong> February 2026<br>
            <strong>Version:</strong> 1.0
        </p>
    </div>

    <!-- Call to Action Footer -->
    <div class="cta-footer">
        <h3>Questions? Need More Information?</h3>
        <p>If you have read and understood these risks, you're ready to explore Viomia.</p>
        <div class="cta-buttons">
            <a href="/" class="btn-secondary">
                <i class="fas fa-home"></i> Back to Home
            </a>
            <a href="/help" class="btn-secondary">
                <i class="fas fa-question-circle"></i> Help & Support
            </a>
            <a href="https://wa.me/0787373722" target="_blank" class="btn-primary">
                <i class="fas fa-comments"></i> Contact Support
            </a>
        </div>
    </div>
</div>

<style>
    footer {
        margin-top: 80px;
    }
</style>

@include('partials.cta')
@include('partials.footer')

@endsection
