@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', sans-serif;
        background: #0f0f0f;
        color: #e8e8e8;
        overflow-x: hidden;
    }

    .page-wrapper {
        width: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .section-container {
        max-width: 1280px;
        margin: 0 auto;
        width: 100%;
        padding: 0 20px;
    }

    /* ===== HERO SECTION ===== */
    .hero-section {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        padding: 100px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(0, 168, 132, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 900px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-size: 64px;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        line-height: 1.1;
    }

    .hero-section .highlight {
        background: linear-gradient(90deg, #00d9a3 0%, #00a884 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-section p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 16px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
        border: 2px solid white;
        padding: 14px 36px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 168, 132, 0.3);
    }

    .btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid white;
        padding: 12px 34px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(20px); }
    }

    /* ===== WHAT IS SECTION ===== */
    .what-is-section {
        padding: 80px 20px;
        background: #1a1a1a;
    }

    .section-title {
        font-size: 48px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 50px;
        text-align: center;
    }

    .section-title .highlight {
        color: #006d5b;
    }

    .what-is-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .what-card {
        background: #222222;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .what-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 25px rgba(0, 168, 132, 0.15);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin: 0 auto 20px;
    }

    .what-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .what-card p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
    }

    /* ===== HOW IT WORKS ===== */
    .how-works-section {
        padding: 80px 20px;
        background: #0f0f0f;
    }

    .steps-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .step-item {
        text-align: center;
    }

    .step-number {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        margin: 0 auto 20px;
    }

    .step-item h3 {
        font-size: 18px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 12px;
    }

    .step-item p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
    }

    /* ===== FEATURES ===== */
    .features-section {
        padding: 80px 20px;
        background: #1a1a1a;
    }

    .features-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }

    .features-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .feature-item {
        display: flex;
        gap: 20px;
    }

    .feature-check {
        width: 24px;
        height: 24px;
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 14px;
    }

    .feature-text h4 {
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 6px;
    }

    .feature-text p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.5;
    }

    .features-image {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, rgba(0, 109, 91, 0.1) 0%, rgba(0, 42, 36, 0.1) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #5e5e5e;
    }

    /* ===== BOT TYPES ===== */
    .bots-section {
        padding: 80px 20px;
        background: #0f0f0f;
    }

    .bots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
    }

    .bot-card {
        background: #222222;
        border: 2px solid #333333;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .bot-card:hover {
        border-color: #006d5b;
        box-shadow: 0 8px 25px rgba(0, 109, 91, 0.25);
    }

    .bot-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .bot-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .bot-card p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .bot-card .btn-primary {
        font-size: 14px;
        padding: 10px 24px;
    }

    /* ===== BENEFITS ===== */
    .benefits-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
    }

    .benefits-section .section-title {
        color: white;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 40px;
    }

    .benefit-card {
        text-align: center;
    }

    .benefit-icon {
        font-size: 40px;
        margin-bottom: 20px;
    }

    .benefit-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 12px;
    }

    .benefit-card p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
    }

    /* ===== STRATEGY SECTION ===== */
    .strategy-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, rgba(0, 109, 91, 0.15) 0%, rgba(0, 43, 36, 0.15) 100%), #1a1a1a;
        color: white;
    }

    .strategy-section .section-title {
        color: white;
    }

    .section-subtitle {
        text-align: center;
        color: #b0b0b0;
        font-size: 16px;
        margin-bottom: 50px;
    }

    .strategy-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }

    .strategy-card {
        padding: 30px;
        background: rgba(34, 34, 34, 0.8);
        border-left: 4px solid #006d5b;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .strategy-card:hover {
        transform: translateY(-5px);
        background: rgba(34, 34, 34, 1);
        border-left-color: #00a884;
    }

    .strategy-icon {
        font-size: 36px;
        color: #00a884;
        margin-bottom: 15px;
    }

    .strategy-card h4 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 12px;
    }

    .strategy-card p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.6;
    }

    /* ===== CTA SECTION ===== */
    .cta-section {
        padding: 80px 20px;
        background: #1a1a1a;
        text-align: center;
    }

    /* ===== PRICING SECTION ===== */
    .pricing-section {
        padding: 80px 20px;
        background: #0f0f0f;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .pricing-card {
        background: #222222;
        border: 2px solid #333333;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .pricing-card.featured {
        border-color: #006d5b;
        background: rgba(0, 109, 91, 0.08);
        transform: scale(1.05);
    }

    .pricing-card:hover {
        border-color: #006d5b;
        box-shadow: 0 8px 25px rgba(0, 109, 91, 0.25);
    }

    .pricing-card .badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .pricing-card h3 {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 20px 0 10px;
    }

    .pricing-card .price {
        margin: 20px 0;
    }

    .pricing-card .amount {
        font-size: 36px;
        font-weight: 700;
        color: #00a884;
    }

    .pricing-card .period {
        font-size: 14px;
        color: #b0b0b0;
        margin-left: 8px;
    }

    .pricing-card .description {
        color: #b0b0b0;
        margin-bottom: 25px;
    }

    .pricing-features {
        list-style: none;
        padding: 0;
        margin: 25px 0;
        text-align: left;
    }

    .pricing-features li {
        padding: 10px 0;
        color: #b0b0b0;
        border-bottom: 1px solid #333333;
    }

    .pricing-features li:last-child {
        border-bottom: none;
    }

    /* ===== SUPPORT SECTION ===== */
    .support-section {
        padding: 80px 20px;
        background: #1a1a1a;
    }

    .support-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .support-card {
        background: #222222;
        border: 2px solid #333333;
        border-radius: 12px;
        padding: 40px 30px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .support-card:hover {
        border-color: #006d5b;
        box-shadow: 0 8px 25px rgba(0, 109, 91, 0.25);
        transform: translateY(-5px);
    }

    .support-icon {
        font-size: 48px;
        margin-bottom: 20px;
    }

    .support-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .support-card p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .support-card a {
        color: #00a884;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s;
    }

    .support-card a:hover {
        color: #00d9a3;
    }

    /* ===== CONTACT SECTION ===== */
    .contact-section {
        padding: 80px 20px;
        background: #0f0f0f;
    }

    .contact-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: flex-start;
    }

    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    .info-item {
        display: flex;
        gap: 20px;
    }

    .info-icon {
        font-size: 32px;
        min-width: 50px;
    }

    .info-text h4 {
        font-size: 18px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 8px;
    }

    .info-text p {
        font-size: 14px;
        color: #b0b0b0;
        line-height: 1.6;
        margin: 0;
    }

    .contact-form {
        background: #222222;
        border: 2px solid #333333;
        border-radius: 12px;
        padding: 40px 30px;
    }

    .contact-form h3 {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 25px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        background: #1a1a1a;
        border: 2px solid #333333;
        border-radius: 8px;
        color: #ffffff;
        font-family: inherit;
        font-size: 14px;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #006d5b;
        box-shadow: 0 0 0 3px rgba(0, 109, 91, 0.1);
        outline: none;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: #666666;
    }

    /* ===== CTA SECTION ===== */
    .page-footer {
        background: #0a0a0a;
        color: rgba(255, 255, 255, 0.5);
        padding: 40px 20px;
        text-align: center;
        font-size: 14px;
        border-top: 1px solid rgba(0, 168, 132, 0.2);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 36px;
        }

        .hero-section p {
            font-size: 16px;
        }

        .hero-buttons {
            flex-direction: column;
            align-items: center;
        }

        .btn-primary, .btn-secondary {
            width: 100%;
            max-width: 300px;
        }

        .section-title {
            font-size: 28px;
        }

        .features-content {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .features-image {
            height: 250px;
        }

        .cta-box {
            padding: 40px 20px;
        }

        .cta-box h2 {
            font-size: 24px;
        }

        .pricing-grid {
            grid-template-columns: 1fr;
        }

        .pricing-card.featured {
            transform: scale(1);
        }

        .contact-content {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .contact-form {
            margin-top: 30px;
        }

        .support-grid {
            grid-template-columns: 1fr;
        }

        .bots-grid {
            grid-template-columns: 1fr;
        }

        .strategy-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 1024px) and (min-width: 769px) {
        .support-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .bots-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .strategy-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="page-wrapper">
    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="section-container">
            <div class="hero-content">
                <h1>Automated <span class="highlight">Trading Bot</span></h1>
                <p>Trade smarter, not harder. Our AI-powered trading bot works 24/7 to execute trades and maximize your returns with minimal effort.</p>
                <div class="hero-buttons">
                    <a href="{{ route('user_register') }}" class="btn-primary">Get Started</a>
                    <a href="{{ route('login') }}" class="btn-secondary">Sign In</a>
                </div>
            </div>
        </div>
    </section>

    <!-- WHAT IS TRADING BOT -->
    <section class="what-is-section" id="features">
        <div class="section-container">
            <h2 class="section-title">What is <span class="highlight">Trading Bot</span>?</h2>
            <div class="what-is-grid">
                <div class="what-card">
                    <div class="card-icon"><i class="fa fa-robot"></i></div>
                    <h3>Intelligent Automation</h3>
                    <p>Our advanced algorithms analyze market conditions and execute trades automatically 24/7 without human intervention.</p>
                </div>
                <div class="what-card">
                    <div class="card-icon"><i class="fa fa-eye"></i></div>
                    <h3>Real-Time Monitoring</h3>
                    <p>Track every trade, monitor performance metrics, and receive instant notifications on significant market movements.</p>
                </div>
                <div class="what-card">
                    <div class="card-icon"><i class="fa fa-shield"></i></div>
                    <h3>Secure & Reliable</h3>
                    <p>Bank-level security with encryption, API authentication, and multi-layer protection for your funds and data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how-works-section" id="how-works">
        <div class="section-container">
            <h2 class="section-title">How It <span class="highlight">Works</span></h2>
            <div class="steps-grid">
                <div class="step-item">
                    <div class="step-number">1</div>
                    <h3>Connect Account</h3>
                    <p>Link your trading account via secure API connection. Your funds stay in your control.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">2</div>
                    <h3>Configure Strategy</h3>
                    <p>Choose from preset strategies or customize parameters like risk level, trade size, and stop loss.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">3</div>
                    <h3>Bot Executes Trades</h3>
                    <p>Our bot monitors the market 24/7 and automatically executes trades based on your strategy.</p>
                </div>
                <div class="step-item">
                    <div class="step-number">4</div>
                    <h3>Analyze Performance</h3>
                    <p>Track returns, view detailed analytics, and optimize your strategy based on real data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KEY FEATURES -->
    <section class="features-section">
        <div class="section-container">
            <h2 class="section-title"><span class="highlight">Key</span> Features</h2>
            <div class="features-content">
                <div>
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>24/7 Automated Trading</h4>
                                <p>Never miss trades. Your bot works around the clock while you sleep or attend to other tasks.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>Multiple Trading Strategies</h4>
                                <p>Choose from grid trading, DCA, momentum trading, or create custom strategies tailored to your goals.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>Real-Time Analytics</h4>
                                <p>Detailed performance metrics, ROI tracking, and AI-powered insights to optimize results.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>Risk Management Tools</h4>
                                <p>Set stop loss, take profit, and maximum drawdown limits to protect your investment.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>Mobile & WhatsApp Alerts</h4>
                                <p>Get instant notifications on important events via push notifications or WhatsApp.</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-check">✓</div>
                            <div class="feature-text">
                                <h4>Expert Support</h4>
                                <p>Access dedicated customer support 24/7 via chat, phone, or email to help optimize your trading.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="features-image">
                    📊 Dashboard Preview
                </div>
            </div>
        </div>
    </section>

    <!-- BOT TYPES -->
    <section class="bots-section">
        <div class="section-container">
            <h2 class="section-title">Available <span class="highlight">Trading Strategies</span></h2>
            <div class="bots-grid">
                <div class="bot-card">
                    <div class="bot-icon"><i class="fa fa-chart-line"></i></div>
                    <h3>Grid Trading</h3>
                    <p>Automatically buy at support levels and sell at resistance. Perfect for ranging markets.</p>
                    <a href="{{ route('user_register') }}" class="btn-primary">Learn More</a>
                </div>
                <div class="bot-card">
                    <div class="bot-icon"><i class="fa fa-money-bill"></i></div>
                    <h3>Dollar Cost Averaging</h3>
                    <p>Invest fixed amounts at regular intervals to reduce timing risk and maximize long-term gains.</p>
                    <a href="{{ route('user_register') }}" class="btn-primary">Learn More</a>
                </div>
                <div class="bot-card">
                    <div class="bot-icon"><i class="fa fa-rocket"></i></div>
                    <h3>Momentum Trading</h3>
                    <p>Ride the waves of market momentum. Automatically enter and exit based on technical indicators.</p>
                    <a href="{{ route('user_register') }}" class="btn-primary">Learn More</a>
                </div>
                <div class="bot-card">
                    <div class="bot-icon"><i class="fa fa-cogs"></i></div>
                    <h3>Custom Strategy</h3>
                    <p>Create your own trading rules and parameters. Full customization for advanced traders.</p>
                    <a href="{{ route('user_register') }}" class="btn-primary">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- BENEFITS -->
    <section class="benefits-section">
        <div class="section-container">
            <h2 class="section-title">Why Choose <span class="highlight">Viomia</span>?</h2>
            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa fa-star"></i></div>
                    <h4>Proven Performance</h4>
                    <p>Consistent returns backed by advanced algorithms and real-time market analysis.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa fa-lock"></i></div>
                    <h4>Maximum Security</h4>
                    <p>Military-grade encryption, API-only access, and zero custody of your funds.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa fa-mobile"></i></div>
                    <h4>Easy to Use</h4>
                    <p>Intuitive interface designed for both beginners and professional traders.</p>
                </div>
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa fa-headset"></i></div>
                    <h4>24/7 Support</h4>
                    <p>Dedicated support team available via chat, email, and phone anytime you need help.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW OUR ALGORITHM WORKS - STRATEGY SECTION -->
    <section class="strategy-section">
        <div class="section-container">
            <h2 class="section-title">How Our <span class="highlight">Algorithm</span> Works</h2>
            <p class="section-subtitle">Advanced Smart Money Concepts (SMC) Trading Methodology</p>
            <div class="strategy-grid">
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-lightbulb"></i></div>
                    <h4>Smart Money Detection</h4>
                    <p>We identify institutional trading patterns by analyzing market structure, identifying where "smart money" accumulates positions, and entering after their moves rather than before.</p>
                </div>
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-line-chart"></i></div>
                    <h4>Market Structure Analysis</h4>
                    <p>Our algorithm recognizes higher highs/lows and lower highs/lows to identify trend direction, support/resistance zones, and optimal entry points based on institutional levels.</p>
                </div>
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-droplet"></i></div>
                    <h4>Liquidity Sweep Detection</h4>
                    <p>When price briefly breaks support/resistance to trigger retail stop-losses, then reverses, we detect these "sweeps" and trade the reversal with high probability setups.</p>
                </div>
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-check-circle"></i></div>
                    <h4>Break of Structure (BOS)</h4>
                    <p>We confirm trading direction only after a clear "break of structure" - when market control shifts. This provides high-confidence entry signals with favorable risk-reward ratios.</p>
                </div>
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-shield"></i></div>
                    <h4>Multi-Layer Risk Management</h4>
                    <p>Every trade is protected by: position sizing (1-2% risk), minimum risk-reward (1:3), daily loss limits (30% max), cooldown periods, and consecutive loss protection.</p>
                </div>
                <div class="strategy-card">
                    <div class="strategy-icon"><i class="fa fa-filter"></i></div>
                    <h4>Advanced Filters</h4>
                    <p>We avoid dangerous conditions using news filters (avoid major events), session filters (trade only during liquid hours), spread filters, and correlation analysis.</p>
                </div>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <a href="/help#strategy-faq" class="btn-primary">Learn More About Our Strategy</a>
                <p style="color: #b0b0b0; margin-top: 20px; font-size: 14px;">Read our comprehensive help section for detailed technical explanations of each component.</p>
            </div>
        </div>
    </section>

    <!-- PRICING SECTION -->
    <section class="pricing-section" id="pricing">
        <div class="section-container">
            <h2 class="section-title">Transparent <span class="highlight">Pricing</span></h2>
            <div class="pricing-grid">
                @forelse($subscriptionPlans as $index => $plan)
                    <div class="pricing-card @if($index === 1) featured @endif">
                        @if($index === 1)
                            <div class="badge">MOST POPULAR</div>
                        @endif
                        <h3>{{ $plan->name }}</h3>
                        <div class="price">
                            <span class="amount">
                                @if($plan->price == 0 || $plan->price == null)
                                    0$
                                @else
                                    {{ $plan->currency ?? '$' }}{{ number_format($plan->price, 0) }}
                                @endif
                            </span>
                            <span class="period">
                                @if($plan->price == 0 || $plan->price == null)
                                    pricing
                                @else
                                    /{{ $plan->billing_interval ?? 'month' }}
                                @endif
                            </span>
                        </div>
                        <p class="description">{{ $plan->description }}</p>
                        <ul class="pricing-features">
                            @if(is_array($plan->features) && count($plan->features) > 0)
                                @foreach($plan->features as $feature)
                                    <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>{{ $feature }}</li>
                                @endforeach
                            @else
                                <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Trading bot access</li>
                                <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Real-time monitoring</li>
                                <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Support</li>
                            @endif
                        </ul>
                        @if($plan->price == 0 || $plan->price == null)
                            <a href="#contact" class="btn-primary">Contact Sales</a>
                        @else
                            <a href="{{ route('user_register') }}" class="btn-primary">Get Started</a>
                        @endif
                    </div>
                @empty
                    <div class="pricing-card">
                        <h3>Starter</h3>
                        <div class="price">
                            <span class="amount">$9</span>
                            <span class="period">/month</span>
                        </div>
                        <p class="description">Perfect for beginners</p>
                        <ul class="pricing-features">
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Basic trading bot</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>1 trading account</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Email support</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Real-time alerts</li>
                        </ul>
                        <a href="{{ route('user_register') }}" class="btn-primary">Get Started</a>
                    </div>
                    <div class="pricing-card featured">
                        <div class="badge">MOST POPULAR</div>
                        <h3>Professional</h3>
                        <div class="price">
                            <span class="amount">$29</span>
                            <span class="period">/month</span>
                        </div>
                        <p class="description">For active traders</p>
                        <ul class="pricing-features">
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Advanced trading bot</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>5 trading accounts</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Priority support</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Real-time alerts</li>
                        </ul>
                        <a href="{{ route('user_register') }}" class="btn-primary">Get Started</a>
                    </div>
                    <div class="pricing-card">
                        <h3>Enterprise</h3>
                        <div class="price">
                            <span class="amount">Custom</span>
                            <span class="period">pricing</span>
                        </div>
                        <p class="description">For institutions</p>
                        <ul class="pricing-features">
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Premium trading bot</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>Unlimited accounts</li>
                            <li><i class="fa fa-check" style="color: #00a884; margin-right: 8px;"></i>24/7 support</li>
                        </ul>
                        <a href="#contact" class="btn-primary">Contact Sales</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- HELP & SUPPORT SECTION -->
    <section class="support-section" id="support">
        <div class="section-container">
            <h2 class="section-title">Help & <span class="highlight">Support</span></h2>
            <div class="support-grid">
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-book"></i></div>
                    <h3>Documentation</h3>
                    <p>Comprehensive guides and API documentation to help you get the most out of our trading bot.</p>
                    <a href="https://docs.viomia.com/" target="_blank">Read Docs →</a>
                </div>
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-graduation-cap"></i></div>
                    <h3>Tutorials</h3>
                    <p>Step-by-step tutorials and video guides to help you set up and optimize your trading strategy.</p>
                    <a href="https://youtube.com/viomia" target="_blank">Watch Tutorials →</a>
                </div>
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-comments"></i></div>
                    <h3>WhatsApp Support</h3>
                    <p>Chat with our support team on WhatsApp. Available 24/7 to answer any questions you have.</p>
                    <a href="https://wa.me/0787373722?text=Hello%20Viomia%20Support" target="_blank">Start Chat →</a>
                </div>
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-envelope"></i></div>
                    <h3>Email Support</h3>
                    <p>Send us an email and our support team will get back to you within 24 hours.</p>
                    <a href="mailto:support@viomia.com">Email Us →</a>
                </div>
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-question-circle"></i></div>
                    <h3>FAQ</h3>
                    <p>Find answers to commonly asked questions about our trading bot and platform.</p>
                    <a href="/help#faq-section">View FAQ →</a>
                </div>
                <div class="support-card">
                    <div class="support-icon"><i class="fa fa-bug"></i></div>
                    <h3>Report Issues</h3>
                    <p>Found a bug or have a feature request? Let us know and we'll look into it.</p>
                    <a href="https://wa.me/0787373722?text=I%20found%20an%20issue" target="_blank">Report →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section" id="contact">
        <div class="section-container">
            <h2 class="section-title">Get In <span class="highlight">Touch</span></h2>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="info-item">
                        <div class="info-icon">📍</div>
                        <div class="info-text">
                            <h4>Office Address</h4>
                            <p>Viomia Trading Technologies<br>123 Finance Street<br>New York, NY 10001</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📞</div>
                        <div class="info-text">
                            <h4>Phone</h4>
                            <p><a href="tel:0787373722" style="color: #00a884; text-decoration: none; font-weight: 600;">0787373722</a><br>Available 24/7</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">💬</div>
                        <div class="info-text">
                            <h4>WhatsApp</h4>
                            <p><a href="https://wa.me/0787373722?text=Hello%20Viomia%20Support" target="_blank" style="color: #00a884; text-decoration: none; font-weight: 600;">Message on WhatsApp</a><br>Fast & Reliable</p>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-icon">📧</div>
                        <div class="info-text">
                            <h4>Email</h4>
                            <p><a href="mailto:support@viomia.com" style="color: #00a884; text-decoration: none;">support@viomia.com</a><br><a href="mailto:sales@viomia.com" style="color: #00a884; text-decoration: none;">sales@viomia.com</a></p>
                        </div>
                    </div>
                </div>
                <div class="contact-form">
                    <h3>Send us a Message</h3>
                    <form action="{{ route('contact.store') ?? '#' }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <select name="subject" required style="background: #1a1a1a; border: 1px solid #333; color: #b0b0b0; padding: 10px; border-radius: 4px; width: 100%;">
                                <option value="">Select Subject</option>
                                <option value="Support">General Support</option>
                                <option value="Sales">Sales Inquiry</option>
                                <option value="Partnership">Partnership Opportunity</option>
                                <option value="Feedback">Feedback</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
                        </div>
                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <button type="submit" class="btn-primary" style="flex: 1;">Send Message</button>
                            <a href="https://wa.me/0787373722?text=I%20want%20to%20contact%20Viomia" target="_blank" style="flex: 1; background: #25d366; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center;">📱 WhatsApp</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @include('partials.cta')
    @include('partials.footer')
</div>

<script>
    // Smooth scrolling for navigation
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    });
</script>

@endsection
