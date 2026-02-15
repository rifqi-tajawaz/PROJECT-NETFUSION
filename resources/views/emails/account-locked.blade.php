<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Locked</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 580px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .app-name {
            font-size: 18px;
            font-weight: 700;
            color: #000;
            text-decoration: none;
            letter-spacing: -0.5px;
        }

        .content h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #d32f2f;
        }

        .content p {
            font-size: 16px;
            color: #444;
            margin-bottom: 24px;
        }

        .alert-box {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 24px;
            color: #b71c1c;
        }

        .btn {
            display: inline-block;
            background-color: #d32f2f;
            color: #ffffff !important;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0 30px 0;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #eaeaea;
            padding-top: 20px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <span class="app-name">Dashboard Tools Netara</span>
        </div>

        <div class="content">
            <h1>Account Locked</h1>
            <p>Hello {{ $user->name }},</p>
            <p>
                Your account has been temporarily locked due to security concerns.
            </p>

            <div class="alert-box">
                <strong>Reason:</strong> {{ $lockReason ?? 'Suspicious activity detected' }}<br>
                <strong>Locked At:</strong> {{ \Carbon\Carbon::parse($lockedAt)->format('d M Y H:i:s') }}
            </div>

            <p>
                To unlock your account, please contact our support team or follow the instructions below to verify your identity.
            </p>

            <a href="{{ route('password.request') }}" class="btn">Reset Password</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Dashboard Tools Netara By Tajawaz Solutions. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
