<style>
    .page-footer {
        background: #0a0a0a;
        color: rgba(255, 255, 255, 0.5);
        padding: 40px 20px;
        text-align: center;
        font-size: 14px;
        border-top: 1px solid rgba(0, 168, 132, 0.2);
        margin-top: 80px;
    }

    .page-footer p {
        margin: 0;
    }

    .page-footer a {
        color: #00a884;
        text-decoration: none;
        transition: color 0.3s;
    }

    .page-footer a:hover {
        color: #00d9a3;
    }

    @media (max-width: 768px) {
        .page-footer {
            padding: 30px 20px;
            font-size: 12px;
        }
    }
</style>

<!-- FOOTER -->
<footer class="page-footer">
    <div class="section-container">
        <p>&copy; {{ date('Y') }} Viomia Trading Bot. All rights reserved. | Secure • Automated • Transparent</p>
        <p style="font-size: 12px; margin-top: 10px; color: #999;">
            <a href="/terms" style="color: #00a884; text-decoration: none;">Terms of Service</a> | 
            <a href="/risk-disclosure" style="color: #00a884; text-decoration: none;">Risk Disclosure</a> | 
            <a href="/technology" style="color: #00a884; text-decoration: none;">Technology</a> | 
            <a href="/help" style="color: #00a884; text-decoration: none;">Help</a>
        </p>
    </div>
</footer>
