<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 20px; }
        .card { background: #ffffff; padding: 30px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .btn { background-color: #0d6efd; color: #ffffff !important; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <div class="card">
        <h2 style="color: #0d6efd; margin-top: 0;">Confirm Your Account</h2>
        <p>Hello {{ $notifiable->name ?? 'Alumni' }},</p>
        <p>Welcome! Click the button below to complete your registration and verify your email address.</p>
        <div style="text-align: center; margin: 25px 0;">
            <a href="{{ $url }}" class="btn">Confirm Email Address</a>
        </div>
        <p style="font-size: 12px; color: #6c757d;">Or copy this link into your browser:<br><a href="{{ $url }}" style="color: #0d6efd; word-break: break-all;">{{ $url }}</a></p>
        <p style="font-size: 12px; color: #6c757d; margin-top: 20px;">If you did not create an account, no further action is required.</p>
    </div>
</body>
</html>