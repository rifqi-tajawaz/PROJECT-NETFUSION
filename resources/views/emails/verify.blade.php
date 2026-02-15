<!DOCTYPE html>
<html>

<head>
    <title>Verifikasi Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 120px;
        }

        .content {
            color: #333333;
            line-height: 1.6;
            font-size: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin-top: 20px;
            box-shadow: 0 2px 4px rgba(0, 123, 255, 0.3);
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }

        .link-text {
            word-break: break-all;
            color: #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if(isset($logo))
                <img src="{{ $logo }}" alt="Logo" class="logo">
            @else
                <h2>{{ config('app.name') }}</h2>
            @endif
        </div>
        <div class="content">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">Halo, {{ $notifiable->name }}!</h2>
            <p>Terima kasih telah mendaftar. Untuk mulai menggunakan akun Anda, mohon verifikasi alamat email Anda
                dengan mengklik tombol di bawah ini:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="btn">Verifikasi Email Saya</a>
            </div>

            <p>Jika Anda tidak membuat akun ini, Anda dapat mengabaikan email ini.</p>

            <p style="margin-top: 30px;">Salam,<br>Tim {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut di browser Anda:<br>
                <a href="{{ $url }}" class="link-text">{{ $url }}</a>
            </p>
        </div>
    </div>
</body>

</html>
