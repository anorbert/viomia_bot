@extends('layouts.app')

@section('content')
<style>
    body, .help-page {
        background: #0f0f0f;
        color: #b0b0b0;
    }

    .help-header {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        padding: 40px 20px;
        text-align: center;
        margin-bottom: 30px;
    }

    .help-header h1 {
        font-size: 2.5em;
        color: white;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .help-header p {
        font-size: 1.1em;
        color: rgba(255, 255, 255, 0.85);
        max-width: 600px;
        margin: 0 auto;
    }

    .help-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px 40px;
    }

    .support-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 10px;
        padding: 30px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        display: flex;
        gap: 20px;
        align-items: flex-start;
    }

    .support-card:hover {
        border-color: #00a884;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.1);
    }

    .support-icon {
        font-size: 2.5em;
        color: #00a884;
        flex-shrink: 0;
    }

    .support-content h3 {
        color: white;
        font-size: 1.3em;
        margin: 0 0 10px;
        font-weight: 700;
    }

    .support-content p {
        color: #b0b0b0;
        font-size: 0.95em;
        line-height: 1.6;
        margin: 0 0 15px;
    }

    .support-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #00a884;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9em;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .support-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 168, 132, 0.3);
        color: white;
        text-decoration: none;
    }

    .support-button.whatsapp {
        background: #25d366;
    }

    .support-button.whatsapp:hover {
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    }

    .faq-links {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        border-radius: 10px;
        padding: 30px;
        margin-top: 40px;
        text-align: center;
    }

    .faq-links h2 {
        color: white;
        font-size: 1.4em;
        margin-bottom: 20px;
        font-weight: 700;
    }

    .faq-links p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.95em;
        margin-bottom: 20px;
    }

    .quick-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .quick-links a {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .quick-links a:hover {
        background: white;
        color: #006d5b;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .help-header h1 {
            font-size: 1.8em;
        }

        .support-card {
            flex-direction: column;
            text-align: center;
        }

        .support-icon {
            font-size: 2em;
        }

        .quick-links {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="help-page">
    <!-- Header -->
    <div class="help-header">
        <h1>🆘 Help & Support</h1>
        <p>We're here to help! Choose your preferred way to contact us.</p>
    </div>

    <div class="help-container">
        <!-- Support Options -->
        <div class="support-card">
            <div class="support-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="support-content">
                <h3>Email Support Form</h3>
                <p>Submit a detailed support request through our secure form. Our team will respond within 24 hours with a solution or next steps.</p>
                <p><strong>Best for:</strong> Technical issues, billing questions, account concerns, and detailed problems.</p>
                @auth
                    <a href="{{ route('support.create') }}" class="support-button">
                        <i class="far fa-paper-plane"></i> Submit Support Request
                    </a>
                @else
                    <a href="{{ route('login') }}" class="support-button">
                        <i class="fas fa-sign-in-alt"></i> Login to Submit Request
                    </a>
                @endauth
            </div>
        </div>

        <div class="support-card">
            <div class="support-icon">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div class="support-content">
                <h3>WhatsApp Chat Support</h3>
                <p>Get immediate help via WhatsApp! Chat directly with our support team for quick troubleshooting and urgent issues.</p>
                <p><strong>Best for:</strong> Urgent issues, quick questions, real-time assistance.</p>
                <a href="https://wa.me/0787373722?text=Hi%20Viomia%20support%2C%20I%20need%20help%20with..." target="_blank" class="support-button whatsapp">
                    <i class="fab fa-whatsapp"></i> Chat on WhatsApp: 0787373722
                </a>
            </div>
        </div>

        <div class="support-card">
            <div class="support-icon">
                <i class="fas fa-code"></i>
            </div>
            <div class="support-content">
                <h3>Technical Documentation</h3>
                <p>Explore our technical resources, API documentation, and detailed guides to solve problems yourself.</p>
                <p><strong>Best for:</strong> Learning how to use features, API integration, technical reference.</p>
                <a href="{{ route('technology') }}" class="support-button">
                    <i class="fas fa-book"></i> View Technology & Docs
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-links">
            <h2>Common Questions?</h2>
            <p>Most questions can be answered quickly through our knowledge base:</p>
            <div class="quick-links">
                <a href="javascript:void(0)" onclick="alert('Please use the support form above for account security questions.')">
                    <i class="fas fa-lock"></i> Account Security
                </a>
                <a href="javascript:void(0)" onclick="alert('Please use the support form above for billing questions.')">
                    <i class="fas fa-credit-card"></i> Billing & Payments
                </a>
                <a href="javascript:void(0)" onclick="alert('Please use the support form above for trading questions.')">
                    <i class="fas fa-chart-line"></i> Trading & Bots
                </a>
                <a href="javascript:void(0)" onclick="alert('Please use the support form above for technical issues.')">
                    <i class="fas fa-cogs"></i> Technical Issues
                </a>
            </div>
        </div>

        <!-- Additional Resources -->
        <div style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 30px; margin-top: 30px; text-align: center;">
            <h3 style="color: white; margin-top: 0;">Other Resources</h3>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('terms') }}" style="color: #00a884; text-decoration: none; font-weight: 600;">Terms of Service</a>
                <span style="color: #333;">|</span>
                <a href="{{ route('privacy') }}" style="color: #00a884; text-decoration: none; font-weight: 600;">Privacy Policy</a>
                <span style="color: #333;">|</span>
                <a href="{{ route('risk-disclosure') }}" style="color: #00a884; text-decoration: none; font-weight: 600;">Risk Disclosure</a>
                <span style="color: #333;">|</span>
                <a href="{{ route('technology') }}" style="color: #00a884; text-decoration: none; font-weight: 600;">Technology</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Optional: Auto-focus form on page load for logged-in users
    document.addEventListener('DOMContentLoaded', function() {
        // Page ready
    });
</script>

<style>
        background: #1a1a1a;
        transition: all 0.3s ease;
        user-select: none;
    }

    .faq-question:hover {
        background: #222;
        padding-left: 18px;
    }

    .faq-question-text {
        color: white;
        margin: 0;
        font-size: 0.95em;
        font-weight: 600;
        flex: 1;
    }

    .faq-toggle {
        color: #00a884;
        font-size: 1.1em;
        transition: transform 0.3s ease;
        margin-left: 10px;
        flex-shrink: 0;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(180deg);
    }

    .faq-answer {
        display: none;
        padding: 12px 15px;
        background: #0f0f0f;
        color: #b0b0b0;
        line-height: 1.6;
        border-top: 1px solid #333;
        font-size: 0.85em;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    .faq-answer ul, .faq-answer ol {
        margin: 10px 0 10px 15px;
        padding: 0;
    }

    .faq-answer li {
        margin-bottom: 5px;
    }

    .faq-answer strong {
        color: #00a884;
    }

    .faq-answer a {
        color: #00a884;
        text-decoration: none;
        font-weight: 600;
    }

    .faq-answer a:hover {
        text-decoration: underline;
    }

    .help-cta {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        padding: 30px 20px;
        border-radius: 8px;
        text-align: center;
        margin-top: 50px;
    }

    .help-cta h2 {
        color: white;
        font-size: 1.4em;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .help-cta p {
        color: rgba(255, 255, 255, 0.85);
        font-size: 0.9em;
        margin-bottom: 20px;
    }

    .help-cta-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .help-cta-btn {
        background: white;
        color: #006d5b;
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9em;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .help-cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 168, 132, 0.3);
    }

    .help-cta-btn.whatsapp {
        background: #25d366;
        color: white;
    }

    @media (max-width: 768px) {
        .help-header h1 {
            font-size: 1.5em;
        }

        .faq-question {
            padding: 10px 12px;
        }

        .faq-question-text {
            font-size: 0.9em;
        }

        .faq-answer {
            padding: 10px 12px;
            font-size: 0.8em;
        }

        .help-cta {
            padding: 20px 15px;
        }

        .help-cta-buttons {
            flex-direction: column;
            gap: 8px;
        }

        .help-cta-btn {
            width: 100%;
            justify-content: center;
            padding: 8px 15px;
        }
    }
</style>

<div class="help-page">
    <!-- Header -->
    <div class="help-header">
        <h1>Help & Support</h1>
        <p>Find answers to common questions about Viomia Trading Bot</p>
    </div>

    <div class="faq-container">
        <!-- Getting Started Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-rocket"></i>
                Getting Started
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What is Viomia Trading Bot?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia Trading Bot is an automated cryptocurrency trading platform that helps you execute trading strategies on exchanges like Binance. Our bots use advanced algorithms to monitor markets 24/7 and execute trades based on your configured strategies, without requiring you to be online.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How do I get started with Viomia?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Getting started is easy:
                    <ol>
                        <li>Create an account on our platform</li>
                        <li>Link your exchange account (API keys)</li>
                        <li>Choose a trading strategy or create your own</li>
                        <li>Set your parameters (risk level, trade size, etc.)</li>
                        <li>Start trading and monitor your bot</li>
                    </ol>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Is my account secure when using Viomia?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes, security is our top priority. We use industry-standard encryption for all data, API keys are stored securely in encrypted vaults, and we never have access to your exchange funds. We only execute trades on your behalf with the permissions you grant. We recommend using API keys with restricted trading permissions only.
                </div>
            </div>
        </div>

        <!-- Bot Management Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-robot"></i>
                Bot Management
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What trading strategies does Viomia support?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia supports multiple professional trading strategies:
                    <ul>
                        <li><strong>Grid Trading</strong> - Profit from price volatility in sideways markets</li>
                        <li><strong>DCA (Dollar Cost Averaging)</strong> - Accumulate assets over time at regular intervals</li>
                        <li><strong>Momentum Trading</strong> - Follow market trends and ride momentum waves</li>
                        <li><strong>Mean Reversion</strong> - Exploit price deviations from moving averages</li>
                        <li><strong>Custom Strategies</strong> - Create your own rules and conditions</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How do I configure bot settings?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Configure your bot by navigating to the bot settings page where you can customize:
                    <ul>
                        <li><strong>Risk Level</strong> - Conservative, Moderate, or Aggressive</li>
                        <li><strong>Trading Pairs</strong> - Select which symbols to trade</li>
                        <li><strong>Time Frames</strong> - 1M, 5M, 15M, 1H, 4H, Daily</li>
                        <li><strong>Position Size</strong> - Lot size and maximum exposure</li>
                        <li><strong>Stop Loss & Take Profit</strong> - Risk management levels</li>
                    </ul>
                    Click <strong>Save</strong> to apply changes. They take effect immediately.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Can I pause or stop the bot?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes! You have full control over your bots. You can:
                    <ul>
                        <li><strong>Pause Bot</strong> - Stop trading temporarily while keeping positions open</li>
                        <li><strong>Stop Bot</strong> - Close all positions and stop trading</li>
                        <li><strong>Resume Bot</strong> - Start trading again whenever you want</li>
                    </ul>
                    Changes take effect immediately. There's no penalty for pausing or stopping.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How do I monitor bot performance?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia provides comprehensive performance tracking and analytics:
                    <ul>
                        <li><strong>Real-time Dashboard</strong> - Monitor active bots and open positions</li>
                        <li><strong>Performance Charts</strong> - Visualize profit/loss over time</li>
                        <li><strong>Win Rate Statistics</strong> - See your bot's accuracy and success ratio</li>
                        <li><strong>Trade History</strong> - Detailed logs of every executed trade</li>
                        <li><strong>Risk Analysis</strong> - Maximum drawdown, Sharpe ratio, and other metrics</li>
                        <li><strong>Export Reports</strong> - Download performance data for records</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What is the minimum trading balance required?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    There's no mandatory minimum balance to start using Viomia. However, for best results we recommend:
                    <ul>
                        <li><strong>Minimum $100</strong> - To see meaningful results with most strategies</li>
                        <li><strong>Recommended $500+</strong> - Allows for better risk management</li>
                        <li><strong>Optimal $1000+</strong> - Maximum flexibility and strategy options</li>
                    </ul>
                    You can start with any amount and increase your trading capital later. Risk management tools adjust automatically.
                </div>
            </div>
        </div>

        <!-- Pricing & Plans Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-credit-card"></i>
                Pricing & Plans
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How much does it cost to use Viomia?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia offers flexible subscription plans to suit different traders:
                    <ul>
                        <li><strong>Starter Plan</strong> - Access to basic bot features and strategies</li>
                        <li><strong>Professional Plan</strong> - Advanced strategies and priority support</li>
                        <li><strong>Enterprise Plan</strong> - Unlimited bots, custom strategies, and dedicated support</li>
                    </ul>
                    All plans are monthly subscriptions with no hidden fees. Visit the <strong>Pricing section</strong> to see detailed features and current pricing.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Can I cancel my subscription anytime?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes, you can cancel your subscription at any time. Cancellation is effective immediately, and no refunds are issued for the current billing period. Your bots will stop once the subscription ends. You can reactivate anytime by choosing a new plan.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Is there a money-back guarantee?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes! We offer a <strong>30-day money-back guarantee</strong> on all subscription plans. If you're not satisfied with our service, contact us within 30 days of purchase for a full refund, no questions asked. Your satisfaction is our priority.
                </div>
            </div>
        </div>

        <!-- Technical Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-cogs"></i>
                Technical & Advanced
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Does Viomia have an API?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes! Viomia provides a comprehensive REST API for advanced users. You can:
                    <ul>
                        <li>Programmatically create and manage bots</li>
                        <li>Access historical trade data</li>
                        <li>Retrieve performance metrics and analytics</li>
                        <li>Integrate with your own applications</li>
                    </ul>
                    Visit <strong>Settings → API Documentation</strong> to get your API keys and explore available endpoints.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How long does it take for a bot to execute trades?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Trade execution speed depends on several factors:
                    <ul>
                        <li><strong>Signal Detection</strong> - Milliseconds (typically less than 100ms)</li>
                        <li><strong>API Latency</strong> - Depends on exchange and internet connection</li>
                        <li><strong>Market Conditions</strong> - Can affect slippage and execution price</li>
                        <li><strong>Average Execution Time</strong> - Most trades execute within 1-5 seconds</li>
                    </ul>
                    We use direct exchange APIs to minimize latency and ensure fast execution.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Is backtesting available?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes! Viomia includes a powerful backtesting feature that allows you to:
                    <ul>
                        <li>Test strategies using historical market data</li>
                        <li>See how your strategy would have performed in the past</li>
                        <li>Optimize parameters before live trading</li>
                        <li>Analyze win rates and risk metrics</li>
                        <li>Adjust strategies based on results</li>
                    </ul>
                    Access backtesting from <strong>Tools → Strategy Backtester</strong> for any date range.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What exchanges are supported?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia currently supports these major cryptocurrency exchanges:
                    <ul>
                        <li><strong>Binance</strong> - Spot trading and futures</li>
                        <li><strong>Coinbase Pro</strong> - Spot trading</li>
                        <li><strong>Kraken</strong> - Spot trading and margin</li>
                        <li><strong>Bybit</strong> - Futures trading</li>
                        <li><strong>Kucoin</strong> - Spot trading</li>
                    </ul>
                    We're constantly adding support for more exchanges. Check back regularly for updates.
                </div>
            </div>
        </div>

        <!-- Strategy & SMC Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-lightbulb"></i>
                Trading Strategy (SMC)
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What is Smart Money Concepts (SMC)?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Smart Money Concepts is an advanced trading methodology that focuses on institutional order flow and market structure rather than traditional retail indicators.
                    <p>The SMC strategy identifies how institutional traders (the "smart money") move the market through:</p>
                    <ul>
                        <li><strong>Market Structure Analysis</strong> - Identifying key support and resistance zones</li>
                        <li><strong>Liquidity Sweeps</strong> - Detecting when price breaks levels to trigger stop-loss orders</li>
                        <li><strong>Break of Structure (BOS)</strong> - Confirming shifts in market control</li>
                        <li><strong>Trend Confirmation</strong> - Ensuring trades align with the broader trend</li>
                    </ul>
                    This approach allows traders to enter positions <strong>after institutional manipulation</strong> rather than before it, leading to higher-probability trades.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What are liquidity sweeps and why do they matter?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    A liquidity sweep occurs when price briefly breaks below a support level or above a resistance level, triggering retail stop-loss orders, before reversing.
                    <p><strong>Why they matter:</strong></p>
                    <ul>
                        <li>Institutional traders use these sweeps to accumulate positions at better prices</li>
                        <li>Smart Money collects liquidity from panicked retail traders</li>
                        <li>After the sweep, price typically moves strongly in the opposite direction</li>
                        <li>Viomia detects these sweeps and enters trades <em>after</em> the reversal for better risk-reward</li>
                    </ul>
                    <p><strong>Example:</strong> Price drops to $50,000 (triggering sell stops), then reverses to $52,000. Viomia would enter a BUY after confirming the reversal.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What is Break of Structure (BOS)?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Break of Structure (BOS) is the confirmation that market control has shifted to the other side.
                    <p><strong>Definition:</strong> If the market was making lower lows (downtrend), a BOS would be an uptrend where price makes a higher low and breaks above the previous structure.</p>
                    <p><strong>Why it's important:</strong></p>
                    <ul>
                        <li>BOS confirms that the direction is changing</li>
                        <li>It provides high-conviction trade setup confirmation</li>
                        <li>Combined with other filters, it gives excellent risk-reward entries</li>
                        <li>Viomia uses BOS as a core entry signal</li>
                    </ul>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How does Viomia approach risk management?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Risk management is the foundation of the Viomia trading system. Multiple layers of protection are implemented:
                    <ul>
                        <li><strong>Fixed Risk Per Trade:</strong> Each trade risks only 1-2% of account equity</li>
                        <li><strong>Risk-Reward Ratio:</strong> Minimum 1:3 (you win $3 for every $1 risked)</li>
                        <li><strong>Maximum Daily Loss:</strong> Trading stops if daily losses exceed 30%</li>
                        <li><strong>Consecutive Loss Protection:</strong> Pauses after 3 consecutive losses</li>
                        <li><strong>Position Limits:</strong> Maximum 1 open trade at a time to prevent overexposure</li>
                        <li><strong>Cooldown Periods:</strong> 1-hour minimum between trades to avoid overtrading</li>
                    </ul>
                    <p>This structure means <strong>even with a 40-50% win rate, the system remains profitable</strong> due to the asymmetric risk-reward ratio.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What market protection filters does Viomia use?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia avoids trading during unfavorable market conditions using multiple filters:
                    <ul>
                        <li><strong>News Filter:</strong> Trading pauses 30 minutes before/after high-impact economic events</li>
                        <li><strong>Session Filter:</strong> Only trades during active market sessions (London, New York)</li>
                        <li><strong>Spread Filter:</strong> Rejects trades when spreads are too wide</li>
                        <li><strong>Correlation Filter:</strong> Prevents clustering of trades in the same direction</li>
                        <li><strong>Volatility Filter:</strong> Requires minimum volatility threshold for setups</li>
                    </ul>
                    <p>These filters protect your account from explosive volatility and slippage during unpredictable events.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What performance metrics should I track?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    To evaluate Viomia's performance, monitor these key metrics:
                    <ul>
                        <li><strong>Win Rate:</strong> Percentage of profitable trades (target: 40-55%)</li>
                        <li><strong>Profit Factor:</strong> Gross profit divided by gross loss (target: > 1.5)</li>
                        <li><strong>Risk-Reward Ratio:</strong> Average win size vs. average loss size (target: 1:3)</li>
                        <li><strong>Maximum Drawdown:</strong> Largest peak-to-trough decline (target: < 20%)</li>
                        <li><strong>Sharpe Ratio:</strong> Risk-adjusted returns (higher is better)</li>
                        <li><strong>Trade Frequency:</strong> Typically 5-15 trades per week (quality over quantity)</li>
                        <li><strong>Average Trade Duration:</strong> How long positions remain open</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Technology Stack Section -->
        <div class="faq-section">
            <h2 class="faq-section-title">
                <i class="fas fa-server"></i>
                Technology & Infrastructure
            </h2>
            <div class="faq-section-divider"></div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">What technology powers Viomia?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Viomia is built on a professional trading architecture combining:
                    <ul>
                        <li><strong>MetaTrader 5 (MT5):</strong> The trading execution platform</li>
                        <li><strong>MQL5:</strong> Advanced programming language for algorithmic strategies</li>
                        <li><strong>Laravel:</strong> Cloud backend for monitoring, analytics, and signal distribution</li>
                        <li><strong>REST APIs:</strong> Secure communication between trading platform and cloud infrastructure</li>
                    </ul>
                    <p>Learn more on our <a href="/technology" style="color: #00a884; font-weight: 600;">Technology page</a> or download the full <a href="#" style="color: #00a884; font-weight: 600;">technical whitepaper</a>.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">How does Viomia monitor my trading?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    The cloud infrastructure synchronizes with your trading bot in real-time, tracking:
                    <ul>
                        <li><strong>Trade Data:</strong> Every trade opened, closed, and position update</li>
                        <li><strong>Account Metrics:</strong> Balance, equity, margin, and open positions</li>
                        <li><strong>Performance Analytics:</strong> Daily profit, win rate, trade count</li>
                        <li><strong>System Alerts:</strong> Risk limit triggers, filter blocks, errors</li>
                    </ul>
                    <p>This enables you to monitor your bot 24/7 from the Viomia dashboard.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <h3 class="faq-question-text">Is the system secure?</h3>
                    <span class="faq-toggle">▼</span>
                </div>
                <div class="faq-answer">
                    Yes. Viomia implements institutional-grade security:
                    <ul>
                        <li><strong>API Key Authentication:</strong> Secure credential management</li>
                        <li><strong>Encrypted Communication:</strong> All data transmitted securely via REST APIs</li>
                        <li><strong>No Fund Access:</strong> Viomia never has access to your exchange funds</li>
                        <li><strong>Restricted Permissions:</strong> We recommend using API keys with trading-only permissions</li>
                        <li><strong>Retry Logic & Error Handling:</strong> Robust protection against network failures</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="faq-container">
        <div class="help-cta">
            <h2>Still have questions?</h2>
            <p>Our support team is here to help. Choose your preferred contact method below.</p>
            <div class="help-cta-buttons">
                <a href="https://wa.me/0787373722?text=I%20need%20help%20with%20Viomia" target="_blank" class="help-cta-btn whatsapp">
                    <i class="fas fa-comments"></i> WhatsApp Support
                </a>
                <a href="mailto:support@viomia.com" class="help-cta-btn">
                    <i class="fas fa-envelope"></i> Email Support
                </a>
                <a href="/" class="help-cta-btn">
                    <i class="fas fa-home"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFaq(element) {
        const faqItem = element.closest('.faq-item');
        faqItem.classList.toggle('active');
    }

    // Close other items when opening a new one (optional)
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
            const allItems = document.querySelectorAll('.faq-item');
            allItems.forEach(item => {
                if (item !== this.closest('.faq-item')) {
                    item.classList.remove('active');
                }
            });
        });
    });
</script>

@endsection
