@extends('app')

@section('content')
<div class="container mt-5 pb-5">
    <!-- Page Header -->
    <div class="text-center mb-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 60px 20px; border-radius: 10px; margin: -20px -12px 40px;">
        <h1 class="display-4 font-weight-bold mb-2">Help & Support</h1>
        <p class="lead mb-0">Get answers to your questions and resolve issues quickly</p>
    </div>

    <!-- Search Bar -->
    <div class="row mb-5">
        <div class="col-lg-8 offset-lg-2">
            <form method="GET" action="{{ route('help') }}" class="mb-4">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="q" class="form-control border-0 rounded-lg" 
                           placeholder="Search help articles..." 
                           value="{{ request('q') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary rounded-right" type="submit">
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
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-light font-weight-bold">
                    <i class="fa fa-list mr-2"></i> Categories
                </div>
                <div class="list-group list-group-flush">
                    <a href="#getting-started" class="list-group-item list-group-item-action">
                        <i class="fa fa-rocket mr-2 text-primary"></i> Getting Started
                    </a>
                    <a href="#account-help" class="list-group-item list-group-item-action">
                        <i class="fa fa-user mr-2 text-success"></i> Account Management
                    </a>
                    <a href="#trading-help" class="list-group-item list-group-item-action">
                        <i class="fa fa-exchange mr-2 text-info"></i> Trading Guide
                    </a>
                    <a href="#payment-help" class="list-group-item list-group-item-action">
                        <i class="fa fa-credit-card mr-2 text-warning"></i> Payments & Billing
                    </a>
                    <a href="#bot-help" class="list-group-item list-group-item-action">
                        <i class="fa fa-cogs mr-2 text-danger"></i> Bot Management
                    </a>
                    <a href="#troubleshoot" class="list-group-item list-group-item-action">
                        <i class="fa fa-exclamation-triangle mr-2 text-secondary"></i> Troubleshooting
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
            <!-- Getting Started -->
            <section id="getting-started" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-rocket text-primary mr-2"></i> Getting Started
                </h3>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 h-100 help-card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-book text-primary mr-2"></i> First Steps
                                </h5>
                                <p class="card-text text-muted">Learn how to create an account and get started with the platform.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">Read Guide</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow-sm border-0 h-100 help-card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fa fa-video-camera text-primary mr-2"></i> Video Tutorials
                                </h5>
                                <p class="card-text text-muted">Watch step-by-step tutorials to master the platform features.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">View Videos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Account Management -->
            <section id="account-help" class="mb-5">
                <h3 class="mb-3 font-weight-bold d-flex align-items-center">
                    <i class="fa fa-user text-success mr-2"></i> Account Management
                </h3>
                
                <div class="accordion" id="accountAccordion">
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
