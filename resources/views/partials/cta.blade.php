<style>
    .cta-section {
        padding: 80px 20px;
        background: #1a1a1a;
        text-align: center;
    }

    .cta-box {
        max-width: 800px;
        margin: 0 auto;
        background: rgba(26, 26, 26, 0.5);
        padding: 60px 40px;
        border-radius: 12px;
        border: 1px solid rgba(0, 168, 132, 0.2);
    }

    .cta-box h2 {
        font-size: 40px;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .cta-box p {
        font-size: 16px;
        color: #b0b0b0;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .cta-section {
            padding: 60px 20px;
        }

        .cta-box {
            padding: 40px 20px;
        }

        .cta-box h2 {
            font-size: 24px;
        }

        .cta-box p {
            font-size: 14px;
        }
    }
</style>

<!-- CTA -->
<section class="cta-section">
    <div class="section-container">
        <div class="cta-box">
            <h2>Ready to Start Trading Smarter?</h2>
            <p>Join hundreds of traders using Viomia to automate their trading and generate consistent profits. Get started in minutes with our simple setup process.</p>
            <a href="{{ route('user_register') }}" class="btn-primary">Create Free Account</a>
        </div>
    </div>
</section>
