<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            color: white;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .content h2 {
            color: #333;
            margin-top: 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
        .social-links {
            margin: 20px 0;
            text-align: center;
        }
        .social-links a {
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
        }
        .newsletter-content {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .header, .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }} Newsletter</h1>
            <p>Stay updated with our latest news and offers</p>
        </div>

        <div class="content">
            <h2>Hello {{ $name }},</h2>

            <p>Thank you for subscribing to our newsletter! Here's what's new:</p>

            <div class="newsletter-content">
                {!! $content !!}
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('home') }}" class="button">
                    Visit Our Store
                </a>
            </div>

            <div class="social-links">
                <strong>Follow us:</strong><br>
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">Instagram</a> |
                <a href="#">LinkedIn</a>
            </div>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>
                You received this email because you subscribed to our newsletter.<br>
                If you no longer wish to receive these emails, please
                <a href="{{ $unsubscribeLink }}" style="color: #667eea;">unsubscribe here</a>.
            </p>
            <p>
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                {{ config('app.url') }}
            </p>
        </div>
    </div>
</body>
</html>
