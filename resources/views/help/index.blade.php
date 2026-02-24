@extends('layouts.general')

@section('content')
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 40px 20px;">
    <div class="container" style="max-width: 1200px;">
        
        <!-- Page Header -->
        <div class="text-center mb-5">
            <h1 class="display-4 font-weight-bold text-white mb-3" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <i class="fa fa-life-ring mr-3"></i> Help & Support
            </h1>
            <p class="lead text-white-50 mb-4">Get answers to your questions and resolve issues quickly</p>
        </div>

        <!-- Quick Support Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center support-card h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-book text-white" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2">Documentation</h5>
                        <p class="text-muted small mb-3">Comprehensive guides and articles</p>
                        <a href="#faq-section" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">Browse</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center support-card h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-envelope text-white" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2">Email Support</h5>
                        <p class="text-muted small mb-3">24-hour response time</p>
                        <a href="mailto:support@viomiabot.com" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;">Contact</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center support-card h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-question-circle text-white" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2">FAQ</h5>
                        <p class="text-muted small mb-3">Popular questions answered</p>
                        <a href="#faq-section" class="btn btn-sm btn-outline-info" style="border-radius: 6px;">View</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0 text-center support-card h-100" style="border-radius: 12px; transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-video-camera text-white" style="font-size: 24px;"></i>
                        </div>
                        <h5 class="font-weight-bold mb-2">Tutorials</h5>
                        <p class="text-muted small mb-3">Video guides & walkthroughs</p>
                        <button class="btn btn-sm btn-outline-warning" style="border-radius: 6px;" onclick="alert('Video tutorials coming soon!')">Watch</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mb-5">
            <div class="col-lg-8 offset-lg-2">
                <form method="GET" action="{{ route('help') }}" class="mb-4">
                    <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                        <input type="text" name="q" class="form-control border-0" 
                               placeholder="Search help articles..." 
                               value="{{ request('q') }}"
                               style="padding: 12px 16px; font-size: 15px;">
                        <div class="input-group-append">
                            <button class="btn" type="submit" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 0 20px; font-weight: 600;">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="card shadow-lg border-0" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header text-white font-weight-bold p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fa fa-list mr-2"></i> Categories
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="#getting-started" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-rocket mr-2" style="color: #667eea;"></i> Getting Started
                        </a>
                        <a href="#account-help" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-user mr-2" style="color: #28a745;"></i> Account Management
                        </a>
                        <a href="#trading-help" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-exchange mr-2" style="color: #17a2b8;"></i> Trading Guide
                        </a>
                        <a href="#payment-help" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-credit-card mr-2" style="color: #ffc107;"></i> Payments & Billing
                        </a>
                        <a href="#bot-help" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-robot mr-2" style="color: #dc3545;"></i> Bot Management
                        </a>
                        <a href="#troubleshoot" class="list-group-item list-group-item-action" style="border: none; padding: 12px 16px; transition: all 0.2s ease;">
                            <i class="fa fa-wrench mr-2" style="color: #6c757d;"></i> Troubleshooting
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <div id="faq-section">
                <!-- Getting Started -->
                <section id="getting-started" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-rocket mr-3" style="font-size: 24px;"></i> Getting Started
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 2px; margin-top: 10px;"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
                                <div class="card-body p-4">
                                    <h5 class="card-title font-weight-bold mb-3">
                                        <i class="fa fa-book text-primary mr-2"></i> Platform Overview
                                    </h5>
                                    <p class="card-text text-muted">Understand the core features and functionality of our trading platform.</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary" style="border-radius: 6px;">Read Guide</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
                                <div class="card-body p-4">
                                    <h5 class="card-title font-weight-bold mb-3">
                                        <i class="fa fa-check-square text-success mr-2"></i> Setup Checklist
                                    </h5>
                                    <p class="card-text text-muted">Follow our step-by-step checklist to set up your account properly.</p>
                                    <a href="#" class="btn btn-sm btn-outline-success" style="border-radius: 6px;">Get Started</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Account Management -->
                <section id="account-help" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-user mr-3" style="font-size: 24px;"></i> Account Management
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #28a745, #20c997); border-radius: 2px; margin-top: 10px;"></div>
                    </div>
                    
                    <div class="accordion" id="accountAccordion">
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading1">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse1" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I update my profile?
                                </button>
                            </div>
                            <div id="collapse1" class="collapse" data-parent="#accountAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>To update your profile, navigate to <strong>My Account → Profile</strong> from the sidebar menu. You can change your name, email, phone number, and upload a profile photo. All changes are saved immediately.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading2">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse2" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I change my password?
                                </button>
                            </div>
                            <div id="collapse2" class="collapse" data-parent="#accountAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Go to <strong>My Account → Change Password</strong>. Enter your current password, then your new password (minimum 8 characters). We recommend using a strong password with uppercase, lowercase, numbers, and special characters.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading3">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse3" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I enable two-factor authentication?
                                </button>
                            </div>
                            <div id="collapse3" class="collapse" data-parent="#accountAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Two-factor authentication adds an extra layer of security to your account. Go to <strong>Security Settings</strong> and enable 2FA using an authenticator app like Google Authenticator or Authy.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Trading Guide -->
                <section id="trading-help" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-exchange mr-3" style="font-size: 24px;"></i> Trading Guide
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #17a2b8, #138496); border-radius: 2px; margin-top: 10px;"></div>
                    </div>
                    
                    <div class="accordion" id="tradingAccordion">
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading4">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse4" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I connect a trading account?
                                </button>
                            </div>
                            <div id="collapse4" class="collapse" data-parent="#tradingAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>To connect a trading account, navigate to <strong>My Accounts</strong> and click <strong>Connect Account</strong>. Enter your broker credentials:</p>
                                    <ul>
                                        <li>Select the trading platform (MT4, MT5, cTrader)</li>
                                        <li>Enter server address and account login</li>
                                        <li>Enter your password (encrypted for security)</li>
                                        <li>Select account type (Real/Demo)</li>
                                        <li>Click Connect</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading5">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse5" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> What are trading signals?
                                </button>
                            </div>
                            <div id="collapse5" class="collapse" data-parent="#tradingAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Trading signals are automated recommendations generated by our AI algorithms. They analyze market conditions and indicate optimal entry and exit points for specific currency pairs. Each signal includes:</p>
                                    <ul>
                                        <li>Currency pair (e.g., EUR/USD)</li>
                                        <li>Signal type (Buy/Sell)</li>
                                        <li>Entry price and levels</li>
                                        <li>Risk/Reward ratio</li>
                                        <li>Confidence score</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading6">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse6" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I track my trades?
                                </button>
                            </div>
                            <div id="collapse6" class="collapse" data-parent="#tradingAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Visit <strong>Trading Activity → Trade History</strong> to see all your past trades. You can:</p>
                                    <ul>
                                        <li>Filter by account, symbol, or date range</li>
                                        <li>View detailed trade analytics</li>
                                        <li>See profit/loss for each trade</li>
                                        <li>Export trade data as CSV</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Payments & Billing -->
                <section id="payment-help" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-credit-card mr-3" style="font-size: 24px;"></i> Payments & Billing
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #ffc107, #ff9800); border-radius: 2px; margin-top: 10px;"></div>
                    </div>
                    
                    <div class="accordion" id="paymentAccordion">
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading7">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse7" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> What payment methods are accepted?
                                </button>
                            </div>
                            <div id="collapse7" class="collapse" data-parent="#paymentAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>We accept multiple secure payment methods:</p>
                                    <ul>
                                        <li><strong>Credit/Debit Cards</strong> - Visa, Mastercard, American Express</li>
                                        <li><strong>Bank Transfers</strong> - Direct wire transfers</li>
                                        <li><strong>E-wallets</strong> - PayPal, Skrill, Neteller</li>
                                        <li><strong>Cryptocurrency</strong> - Bitcoin, Ethereum</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading8">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse8" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I manage my subscription?
                                </button>
                            </div>
                            <div id="collapse8" class="collapse" data-parent="#paymentAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Go to <strong>Billing → My Subscription</strong> to manage your plan:</p>
                                    <ul>
                                        <li>View your current plan and billing cycle</li>
                                        <li>Upgrade to a higher tier</li>
                                        <li>Cancel anytime without penalties</li>
                                        <li>Access invoices and payment history</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading9">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse9" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> Is there a money-back guarantee?
                                </button>
                            </div>
                            <div id="collapse9" class="collapse" data-parent="#paymentAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Yes! We offer a <strong>30-day money-back guarantee</strong> on all subscription plans. If you're not satisfied with our service, contact us within 30 days of purchase for a full refund, no questions asked.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Bot Management -->
                <section id="bot-help" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-robot mr-3" style="font-size: 24px;"></i> Bot Management
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #dc3545, #c82333); border-radius: 2px; margin-top: 10px;"></div>
                    </div>
                    
                    <div class="accordion" id="botAccordion">
                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading10">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse10" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> What is an automated trading bot?
                                </button>
                            </div>
                            <div id="collapse10" class="collapse" data-parent="#botAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>An automated trading bot is a software program that executes trades based on predefined rules and market conditions. Our bots work 24/7 to:</p>
                                    <ul>
                                        <li>Monitor market conditions continuously</li>
                                        <li>Generate trading signals based on AI algorithms</li>
                                        <li>Execute trades on your behalf</li>
                                        <li>Manage risk and position sizing</li>
                                        <li>Track performance and profitability</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading11">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse11" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> How do I configure bot settings?
                                </button>
                            </div>
                            <div id="collapse11" class="collapse" data-parent="#botAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Configure your bot by navigating to <strong>Settings</strong> where you can customize:</p>
                                    <ul>
                                        <li><strong>Risk Level</strong> - Conservative, Moderate, or Aggressive</li>
                                        <li><strong>Trading Pairs</strong> - Select which symbols to trade</li>
                                        <li><strong>Time Frames</strong> - 1M, 5M, 15M, 1H, 4H, Daily</li>
                                        <li><strong>Position Size</strong> - Lot size and maximum exposure</li>
                                        <li><strong>Stop Loss & Take Profit</strong> - Risk management levels</li>
                                    </ul>
                                    <p>Click <strong>Save</strong> to apply changes. They take effect immediately.</p>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3" style="border-radius: 10px; overflow: hidden;">
                            <div class="card-header bg-light p-0" id="heading12">
                                <button class="btn btn-link btn-block text-left p-4 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse12" style="text-decoration: none; color: #2c3e50; transition: all 0.2s ease;">
                                    <i class="fa fa-question-circle mr-2"></i> Can I pause or stop the bot?
                                </button>
                            </div>
                            <div id="collapse12" class="collapse" data-parent="#botAccordion">
                                <div class="card-body p-4 bg-white">
                                    <p>Yes! You have full control. From the <strong>My Accounts</strong> page, you can:</p>
                                    <ul>
                                        <li><strong>Pause Bot</strong> - Stop trading temporarily while keeping positions open</li>
                                        <li><strong>Stop Bot</strong> - Close all positions and stop trading</li>
                                        <li><strong>Resume Bot</strong> - Start trading again whenever you want</li>
                                    </ul>
                                    <p>Changes take effect immediately. There's no penalty for pausing or stopping.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Troubleshooting -->
                <section id="troubleshoot" class="mb-5">
                    <div class="mb-4">
                        <h3 class="font-weight-bold d-flex align-items-center text-white">
                            <i class="fa fa-wrench mr-3" style="font-size: 24px;"></i> Troubleshooting
                        </h3>
                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, #6c757d, #495057); border-radius: 2px; margin-top: 10px;"></div>
                    </div>

                    <div class="alert alert-info alert-dismissible fade show shadow-sm" style="border-radius: 10px; border: none; background: #e7f3ff; color: #004085;">
                        <button type="button" class="close" data-dismiss="alert" style="color: #004085;">×</button>
                        <strong><i class="fa fa-info-circle mr-2"></i> Can't find what you're looking for?</strong>
                        <p class="mb-0 mt-2">Our support team is here to help. Get in touch using one of the options below.</p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-envelope text-white" style="font-size: 28px;"></i>
                                    </div>
                                    <h5 class="font-weight-bold mb-2">Email Support</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="fa fa-clock-o mr-1"></i> Response within 24 hours
                                    </p>
                                    <a href="mailto:support@viomiabot.com" class="btn btn-sm btn-danger" style="border-radius: 6px; font-weight: 600;">
                                        <i class="fa fa-envelope mr-1"></i> Email Us
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden; transition: all 0.3s ease;">
                                <div class="card-body text-center p-4">
                                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-comments text-white" style="font-size: 28px;"></i>
                                    </div>
                                    <h5 class="font-weight-bold mb-2">Live Chat</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="fa fa-clock-o mr-1"></i> Mon-Fri, 9 AM - 6 PM EST
                                    </p>
                                    <button class="btn btn-sm btn-info" style="border-radius: 6px; font-weight: 600;" onclick="alert('Live chat feature coming soon!')">
                                        <i class="fa fa-comments mr-1"></i> Start Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .support-card {
        transition: all 0.3s ease;
    }
    
    .support-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
    }

    .list-group-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent !important;
    }
    
    .list-group-item:hover {
        background-color: #f0f4ff !important;
        border-left-color: #667eea !important;
    }
    
    .accordion .card-header button {
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.2s ease;
    }
    
    .accordion .card-header button:hover {
        color: #667eea;
    }

    .accordion .card-header {
        background: #fcfcfc !important;
        border: none !important;
    }

    .card {
        border: none !important;
    }

    /* Collapse animation */
    .collapse.show {
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
</style>
@endsection
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading1">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse1">
                                <i class="fa fa-question-circle text-success mr-2"></i> How do I update my profile?
                            </button>
                        </div>
                        <div id="collapse1" class="collapse" data-parent="#accountAccordion">
                            <div class="card-body">
                                <p>To update your profile, navigate to <strong>Profile Settings</strong> from the dropdown menu. You can change your name, email, phone number, and upload a profile photo.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading2">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse2">
                                <i class="fa fa-question-circle text-success mr-2"></i> How do I change my password?
                            </button>
                        </div>
                        <div id="collapse2" class="collapse" data-parent="#accountAccordion">
                            <div class="card-body">
                                <p>Go to your profile and click on <strong>Change Password</strong>. Enter your current password, then your new password. Your password must be at least 8 characters long.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading3">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse3">
                                <i class="fa fa-question-circle text-success mr-2"></i> How do I delete my account?
                            </button>
                        </div>
                        <div id="collapse3" class="collapse" data-parent="#accountAccordion">
                            <div class="card-body">
                                <p>Account deletion is permanent. To delete your account, please contact our support team with a request. We'll verify your identity and process the deletion within 24 hours.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Trading Guide -->
            <section id="trading-help" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-exchange text-info mr-2"></i> Trading Guide
                </h3>
                
                <div class="accordion" id="tradingAccordion">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading4">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse4">
                                <i class="fa fa-question-circle text-info mr-2"></i> How do I link a trading account?
                            </button>
                        </div>
                        <div id="collapse4" class="collapse" data-parent="#tradingAccordion">
                            <div class="card-body">
                                <p>To link a trading account, go to <strong>Trading Accounts</strong> and click <strong>Add New Account</strong>. Enter your trading platform credentials and select the bot that will manage this account.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading5">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse5">
                                <i class="fa fa-question-circle text-info mr-2"></i> What are trading signals?
                            </button>
                        </div>
                        <div id="collapse5" class="collapse" data-parent="#tradingAccordion">
                            <div class="card-body">
                                <p>Trading signals are automated recommendations generated by our AI algorithms. They indicate when to buy or sell specific currency pairs based on market analysis.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading6">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse6">
                                <i class="fa fa-question-circle text-info mr-2"></i> How do I track my trades?
                            </button>
                        </div>
                        <div id="collapse6" class="collapse" data-parent="#tradingAccordion">
                            <div class="card-body">
                                <p>Visit the <strong>Trade History</strong> page to see all your past trades. You can filter by account, symbol, or date range. Click on individual trades to view detailed analytics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Payments & Billing -->
            <section id="payment-help" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-credit-card text-warning mr-2"></i> Payments & Billing
                </h3>
                
                <div class="accordion" id="paymentAccordion">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading7">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse7">
                                <i class="fa fa-question-circle text-warning mr-2"></i> What payment methods do you accept?
                            </button>
                        </div>
                        <div id="collapse7" class="collapse" data-parent="#paymentAccordion">
                            <div class="card-body">
                                <p>We accept multiple payment methods including:</p>
                                <ul>
                                    <li>Credit/Debit Cards (Visa, Mastercard, American Express)</li>
                                    <li>Bank Transfers</li>
                                    <li>E-wallets (PayPal, Skrill, Neteller)</li>
                                    <li>Cryptocurrency (Bitcoin, Ethereum)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading8">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse8">
                                <i class="fa fa-question-circle text-warning mr-2"></i> How do I upgrade my subscription?
                            </button>
                        </div>
                        <div id="collapse8" class="collapse" data-parent="#paymentAccordion">
                            <div class="card-body">
                                <p>Go to <strong>Plans & Subscription</strong> and select your desired plan. Click <strong>Upgrade</strong> and follow the payment process. Your plan will be activated immediately.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading9">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse9">
                                <i class="fa fa-question-circle text-warning mr-2"></i> Can I get a refund?
                            </button>
                        </div>
                        <div id="collapse9" class="collapse" data-parent="#paymentAccordion">
                            <div class="card-body">
                                <p>We offer a 14-day money-back guarantee on all subscription plans. If you're not satisfied, contact us within 14 days of purchase for a full refund.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bot Management -->
            <section id="bot-help" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-cogs text-danger mr-2"></i> Bot Management
                </h3>
                
                <div class="accordion" id="botAccordion">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading10">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse10">
                                <i class="fa fa-question-circle text-danger mr-2"></i> What is a bot?
                            </button>
                        </div>
                        <div id="collapse10" class="collapse" data-parent="#botAccordion">
                            <div class="card-body">
                                <p>A bot is an automated trading system that executes trades based on predefined rules and market conditions. Our bots work 24/7 to generate trading signals and manage your accounts.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading11">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse11">
                                <i class="fa fa-question-circle text-danger mr-2"></i> How do I configure a bot?
                            </button>
                        </div>
                        <div id="collapse11" class="collapse" data-parent="#botAccordion">
                            <div class="card-body">
                                <p>Go to <strong>Bot Settings</strong> to customize your bot's behavior. You can set risk levels, trading pairs, time frames, and more. Click <strong>Save</strong> to apply changes.</p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-light p-0" id="heading12">
                            <button class="btn btn-link btn-block text-left p-3 font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse12">
                                <i class="fa fa-question-circle text-danger mr-2"></i> Can I stop the bot anytime?
                            </button>
                        </div>
                        <div id="collapse12" class="collapse" data-parent="#botAccordion">
                            <div class="card-body">
                                <p>Yes! You can stop, pause, or restart your bot anytime from the Bot Management panel. Changes take effect immediately.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Troubleshooting -->
            <section id="troubleshoot" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-exclamation-triangle text-secondary mr-2"></i> Troubleshooting
                </h3>
                
                <div class="alert alert-info alert-dismissible fade show">
                    <strong>Still need help?</strong>
                    <p class="mb-0 mt-2">Can't find the answer? Our support team is ready to assist you.</p>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 help-card">
                            <div class="card-body text-center">
                                <i class="fa fa-envelope fa-2x text-secondary mb-3"></i>
                                <h5>Email Support</h5>
                                <p class="text-muted small">Response time: 24 hours</p>
                                <a href="mailto:support@trading-bot.com" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa fa-envelope mr-2"></i> Email Us
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 help-card">
                            <div class="card-body text-center">
                                <i class="fa fa-headphones fa-2x text-secondary mb-3"></i>
                                <h5>Live Chat</h5>
                                <p class="text-muted small">Available 9 AM - 6 PM EST</p>
                                <button class="btn btn-sm btn-outline-secondary" onclick="alert('Chat feature coming soon!')">
                                    <i class="fa fa-comments mr-2"></i> Start Chat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
    .help-card {
        transition: all 0.3s ease;
    }
    
    .help-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .rounded-lg {
        border-radius: 8px;
    }
    
    .list-group-item {
        transition: all 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left: 4px solid #667eea;
        padding-left: calc(0.75rem - 1px);
    }
    
    .accordion .card-header button {
        text-decoration: none;
        color: #2c3e50;
        transition: all 0.2s ease;
    }
    
    .accordion .card-header button:hover {
        color: #667eea;
    }
    
    .accordion .card-header button.collapsed::after {
        content: "▼";
        float: right;
        transition: transform 0.3s ease;
    }
    
    .accordion .card-header button::after {
        content: "▲";
        float: right;
        transition: transform 0.3s ease;
    }
</style>
@endsection
