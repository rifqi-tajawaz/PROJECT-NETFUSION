<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alert</title>
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
            color: #e65100;
        }

        .content p {
            font-size: 16px;
            color: #444;
            margin-bottom: 24px;
        }

        .details-box {
            background-color: #fff3e0;
            border: 1px solid #ffe0b2;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .details-item {
            margin-bottom: 10px;
        }

        .label {
            font-weight: 600;
            color: #e65100;
            display: inline-block;
            width: 120px;
        }

        .value {
            color: #111;
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
            <h1>Suspicious Activity Detected</h1>
            <p>Hello,</p>
            <p>
                Our security system has detected suspicious activity on your account.
            </p>

            <div class="details-box">
                @foreach($suspicious as $key => $value)
                <div class="details-item">
                    <span class="label">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                    <span class="value">
                        @if(is_array($value))
                            {{ json_encode($value) }}
                        @else
                            {{ $value }}
                        @endif
                    </span>
                </div>
                @endforeach
                <div class="details-item">
                    <span class="label">Time:</span>
                    <span class="value">{{ now()->toDateTimeString() }}</span>
                </div>
            </div>

            <p>
                If this wasn't you, please secure your account immediately.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Dashboard Tools Netara By Tajawaz Solutions. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
