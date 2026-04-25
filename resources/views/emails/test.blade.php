<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test Email</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success">
            <h2>✅ Test Email Réussi</h2>
            <p><strong>Application:</strong> {{ $appName }}</p>
            <p><strong>Date/Heure:</strong> {{ $time }}</p>
            <p>Votre configuration email fonctionne correctement !</p>
        </div>
    </div>
</body>
</html>
