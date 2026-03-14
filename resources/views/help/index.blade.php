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

<!-- FAQ Section -->
<div class="faq-container">
    <h2 class="faq-title">Frequently Asked Questions</h2>
    <p class="faq-subtitle">Find answers to common questions about Viomia Trading Bot</p>
    
    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">What is Viomia Trading Bot?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            Viomia is an automated trading bot that uses advanced algorithms to execute trades on your behalf. It analyzes market conditions and executes trades 24/7 without requiring you to monitor the markets constantly.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">Is my money safe with Viomia?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            Yes, your funds remain in your exchange account. Viomia never has direct access to your money - it only has permission to place trades on your behalf. You maintain full control of your account and can revoke access at any time.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">How much does Viomia cost?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            <p>Viomia offers flexible pricing plans:</p>
            <ul>
                <li><strong>Free Plan</strong> - Basic bot with limited features</li>
                <li><strong>Starter Plan</strong> - $29/month for advanced features</li>
                <li><strong>Professional Plan</strong> - $99/month with priority support</li>
                <li><strong>Enterprise Plan</strong> - Custom pricing for teams</li>
            </ul>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">Can I use Viomia with my existing exchange account?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            Yes! Viomia works with all major exchanges including Binance, Kraken, Coinbase, and more. Simply connect your exchange API keys and you're ready to start trading. No need to transfer your funds - Viomia trades directly from your account.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">What are the risks involved?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            <p>Trading involves risk. While Viomia uses advanced algorithms to minimize losses, there is no guarantee of profits. Please review our <a href="{{ route('risk-disclosure') }}">Risk Disclosure</a> page for complete details. We always recommend:</p>
            <ul>
                <li>Start with small amounts if you're new to trading</li>
                <li>Understand the market before using automated trading</li>
                <li>Set appropriate risk limits and stop losses</li>
                <li>Never invest more than you can afford to lose</li>
            </ul>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">How do I get started?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            Getting started is easy:
            <ol>
                <li>Create a free account on Viomia</li>
                <li>Connect your exchange API keys</li>
                <li>Configure your trading preferences</li>
                <li>Start automated trading!</li>
            </ol>
            For detailed setup instructions, visit our <a href="{{ route('technology') }}">Technology & Docs</a> page.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">What customer support is available?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            We offer 24/7 support through multiple channels:
            <ul>
                <li><strong>Email Support</strong> - 24-hour response time</li>
                <li><strong>WhatsApp Chat</strong> - Instant messaging support</li>
                <li><strong>Documentation</strong> - Comprehensive guides and tutorials</li>
                <li><strong>Community Forum</strong> - Connect with other users</li>
            </ul>
            Click "Help & Support" above to reach us.
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            <p class="faq-question-text">Can I pause or stop the bot anytime?</p>
            <span class="faq-toggle">▼</span>
        </div>
        <div class="faq-answer">
            Absolutely! You have complete control. You can pause the bot in your dashboard at any time. You can also revoke API access to your exchange account at any time, and Viomia will immediately stop all trading activity.
        </div>
    </div>
</div>

<style>
    .faq-container {
        max-width: 900px;
        margin: 40px auto 0;
        padding: 40px 20px;
    }

    .faq-title {
        text-align: center;
        color: white;
        font-size: 2em;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .faq-subtitle {
        text-align: center;
        color: #b0b0b0;
        margin-bottom: 40px;
        font-size: 1em;
    }

    .faq-item {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }

    .faq-item:hover {
        border-color: #00a884;
        box-shadow: 0 4px 12px rgba(0, 168, 132, 0.1);
    }

    .faq-question {
        display: flex;
        align-items: center;
        padding: 15px;
        cursor: pointer;
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
        padding: 15px;
        background: #0f0f0f;
        color: #b0b0b0;
        line-height: 1.6;
        border-top: 1px solid #333;
        font-size: 0.9em;
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

    @media (max-width: 768px) {
        .faq-title {
            font-size: 1.5em;
        }

        .faq-subtitle {
            font-size: 0.9em;
        }

        .faq-question-text {
            font-size: 0.85em;
        }

        .faq-answer {
            font-size: 0.8em;
        }

        .faq-container {
            padding: 30px 20px;
        }
    }

    @media (max-width: 576px) {
        .faq-title {
            font-size: 1.3em;
            margin-bottom: 20px;
        }

        .faq-question {
            padding: 12px;
        }

        .faq-toggle {
            font-size: 1em;
        }

        .faq-question-text {
            font-size: 0.8em;
        }

        .faq-item {
            margin-bottom: 10px;
        }

        .faq-answer {
            padding: 10px 12px;
            font-size: 0.75em;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', function() {
                faqItems.forEach(other => {
                    if (other !== item) {
                        other.classList.remove('active');
                    }
                });
                item.classList.toggle('active');
            });
        });
    });
</script>

@include('partials.cta')
@include('partials.footer')

@endsection
