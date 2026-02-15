<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Device Login Alert</title>
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
            color: #111;
        }

        .content p {
            font-size: 16px;
            color: #444;
            margin-bottom: 24px;
        }

        .details-box {
            background-color: #f9f9f9;
            border: 1px solid #eaeaea;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .details-item {
            margin-bottom: 10px;
        }

        .details-item:last-child {
            margin-bottom: 0;
        }

        .label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 100px;
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
            <h1>New Device Detected</h1>
            <p>Hello,</p>
            <p>
                We noticed a login to your account from a new device. If this was you, you can ignore this email.
            </p>

            <div class="details-box">
                <div class="details-item">
                    <span class="label">Device:</span>
                    <span class="value">{{ $device->device_name ?? 'Unknown Device' }}</span>
                </div>
                <div class="details-item">
                    <span class="label">IP Address:</span>
                    <span class="value">{{ $ip }}</span>
                </div>
                <div class="details-item">
                    <span class="label">Browser:</span>
                    <span class="value">{{ $userAgent }}</span>
                </div>
                @if(isset($location['city']) || isset($location['country']))
                <div class="details-item">
                    <span class="label">Location:</span>
                    <span class="value">
                        {{ $location['city'] ?? '' }}{{ isset($location['city']) && isset($location['country']) ? ', ' : '' }}{{ $location['country'] ?? '' }}
                    </span>
                </div>
                @endif
                <div class="details-item">
                    <span class="label">Time:</span>
                    <span class="value">{{ now()->toDateTimeString() }}</span>
                </div>
            </div>

            <p>
                If you did not authorize this login, please change your password immediately and contact support.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Dashboard Tools Netara By Tajawaz Solutions. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
