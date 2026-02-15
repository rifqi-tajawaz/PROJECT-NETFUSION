<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
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

        /* Black Button */
        .btn {
            display: inline-block;
            background-color: #000000;
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

        .footer a {
            color: #666;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo Text Only -->
        <div class="header">
            <span class="app-name">Dashboard Tools Netara By Tajawaz Solutions</span>
        </div>

        <div class="content">
            <h1>Verifikasi Alamat Email Anda</h1>
            <p>Halo {{ $user->name }},</p>
            <p>
                Anda baru saja mendaftar akun di Dashboard Tools Netara By Tajawaz Solutions.
                Untuk menjaga keamanan akun Anda, mohon verifikasi bahwa ini benar email Anda.
            </p>

            <a href="{{ $verificationUrl }}" class="btn">Verifikasi Email</a>

            <p style="font-size: 14px; color: #666;">
                Tautan verifikasi ini akan kedaluwarsa dalam 60 menit.
                Jika Anda tidak merasa mendaftar, silakan abaikan pesan ini.
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Dashboard Tools Netara By Tajawaz Solutions. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
