<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-critical {
            background-color: #fee;
            border-left: 4px solid #c33;
            color: #c33;
        }
        .alert-high {
            background-color: #fef3cd;
            border-left: 4px solid #f57c00;
            color: #856404;
        }
        .alert-medium {
            background-color: #d1ecf1;
            border-left: 4px solid #17a2b8;
            color: #0c5460;
        }
        .info-box {
            background-color: #f8f9fa;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box h3 {
            margin-top: 0;
            color: #667eea;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #5568d3;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .security-tip {
            background-color: #e7f3ff;
            border-left: 3px solid #2196f3;
            padding: 15px;
            margin: 15px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Password Expiration Reminder</h1>
        </div>

        <div class="content">
            @if($urgencyLevel === 'critical')
                <div class="alert alert-critical">
                    <strong>⚠️ URGENT:</strong> Your password expires tomorrow!
                </div>
            @elseif($urgencyLevel === 'high')
                <div class="alert alert-high">
                    <strong>⏰ Attention:</strong> Your password is expiring very soon.
                </div>
            @else
                <div class="alert alert-medium">
                    <strong>ℹ️ Reminder:</strong> Your password will expire soon.
                </div>
            @endif

            <p>Hi {{ Auth::user()->name }},</p>

            <p>This is a friendly reminder that your account password will expire in <strong>{{ $daysRemaining }} day{{ $daysRemaining > 1 ? 's' : '' }}</strong> on <strong>{{ $expiryDate }}</strong>.</p>

            <div class="info-box">
                <h3>Why Change Your Password?</h3>
                <p>Regular password changes help protect your account from unauthorized access and maintain the security of your data.</p>
            </div>

            @if($urgencyLevel === 'critical')
                <div class="security-tip">
                    <strong>🚨 Action Required:</strong> Please change your password today to avoid losing access to your account!
                </div>
            @endif

            <p style="text-align: center;">
                <a href="{{ route('password.change') }}" class="button">Change Password Now</a>
            </p>

            <div class="security-tip">
                <strong>💡 Security Tips:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Use at least 8 characters with uppercase, lowercase, numbers, and symbols</li>
                    <li>Avoid using personal information (birthdays, names, etc.)</li>
                    <li>Don't reuse passwords from other accounts</li>
                    <li>Consider using a password manager</li>
                </ul>
            </div>

            <hr style="border: none; border-top: 1px solid #dee2e6; margin: 20px 0;">

            <p style="font-size: 14px; color: #6c757d;">
                If you didn't request this reminder or believe this is an error, you can safely ignore this email.
            </p>
        </div>

        <div class="footer">
            <p>
                <strong>{{ config('app.name') }}</strong><br>
                This is an automated email. Please do not reply.
            </p>
            <p style="margin: 10px 0 0 0;">
                If you have questions, contact our support team.
            </p>
        </div>
    </div>
</body>
</html>
