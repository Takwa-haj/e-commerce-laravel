<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; text-align: center; padding: 50px; }
        .success { color: #28a745; font-size: 24px; margin-bottom: 20px; }
        .details { background: #f8f9fa; padding: 20px; border-radius: 8px; display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="success">✓ Email Configuration Test Successful!</div>
    <p>This test email confirms that your email configuration is working correctly.</p>

    <div class="details">
        <p><strong>Application:</strong> {{ $appName }}</p>
        <p><strong>Time Sent:</strong> {{ $time }}</p>
        <p><strong>Email Service:</strong> Brevo (Sendinblue)</p>
    </div>

    <p style="margin-top: 30px; color: #6c757d;">
        If you received this email, your Brevo SMTP configuration is working properly.
    </p>
</body>
</html>
