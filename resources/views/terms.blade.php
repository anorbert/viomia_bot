@extends('layouts.app')

@section('title', 'Terms and Conditions | Viomia')

@section('content')
<style>
    body {
        /*background: linear-gradient(135deg, #006d5b 0%, #002b24 100%);*/
        min-height: 100vh;
        padding: 50px 20px;
        font-family: 'Inter', sans-serif;
    }

    .terms-container {
        background: #ffffff;
        border-radius: 16px;
        /*box-shadow: 0 20px 40px rgba(0,0,0,0.3);*/
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
        padding: 50px;
        color: #2d3748;
    }

    .terms-header {
        border-bottom: 2px solid #f1f5f9;
        margin-bottom: 30px;
        padding-bottom: 20px;
    }

    .terms-header h1 {
        font-weight: 800;
        color: #1a202c;
        font-size: 28px;
    }

    .terms-content h5 {
        font-weight: 700;
        color: #00a884;
        margin-top: 25px;
    }

    .terms-content p {
        line-height: 1.7;
        font-size: 15px;
        color: #4a5568;
    }

    .back-btn {
        display: inline-block;
        margin-top: 30px;
        color: #718096;
        text-decoration: none;
        font-size: 14px;
        transition: color 0.2s;
    }

    .back-btn:hover {
        color: #00a884;
    }

    @media (max-width: 600px) {
        .terms-container { padding: 30px 20px; }
    }
</style>

<div class="terms-container">
    <div class="terms-header text-center">
        <img src="{{ asset('logo.png') }}" alt="Viomia" style="max-width: 80px; margin-bottom: 15px;">
        <h1>Terms and Conditions</h1>
        <p class="text-muted">Last Updated: {{ date('F d, Y') }}</p>
    </div>

    <div class="terms-content">
        <h5>1. Acceptance of Terms</h5>
        <p>By accessing and using the Viomia Trading Bot platform, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, you must not use our services.</p>

        <h5>2. Risk Disclosure</h5>
        <p>Trading in financial markets involves significant risk. Our bot is a tool to assist trading, but we do not guarantee profits. Users are responsible for their own investment decisions and should only trade with capital they can afford to lose.</p>

        <h5>3. Account Security</h5>
        <p>You are responsible for maintaining the confidentiality of your 4-digit security PIN. Any activity performed under your account is your sole responsibility.</p>

        <h5>4. Intellectual Property</h5>
        <p>All software, algorithms, and branding elements associated with Viomia Trading Technologies are the exclusive property of Viomia and protected by international copyright laws.</p>

        <h5>5. Limitation of Liability</h5>
        <p>Viomia Trading Technologies shall not be held liable for any financial losses, technical interruptions, or data breaches resulting from the use of the platform.</p>
    </div>

    <hr>

    <div class="text-center">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fa fa-arrow-left"></i> Return to Registration
        </a>
    </div>
</div>
@endsection