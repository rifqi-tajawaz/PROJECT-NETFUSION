<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Kode Verifikasi NetFusion</title>
    <style>
        /* Base */
        body {
            background-color: #f6f9fc;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* Container */
        .container {
            margin: 0 auto !important;
            max-width: 580px;
            padding: 0;
            width: 580px;
        }

        /* Content */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }

        /* Card */
        .wrapper {
            box-sizing: border-box;
            padding: 20px;
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e9eff5;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        /* Typography */
        h1 {
            color: #1e2022;
            font-family: sans-serif;
            font-weight: 700;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 20px;
            font-size: 22px;
            text-align: center;
        }

        p {
            font-family: sans-serif;
            font-size: 15px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
            color: #5c6b7f;
            line-height: 1.6;
        }

        /* OTP Box */
        .otp-box {
            display: block;
            width: 100%;
            text-align: center;
            margin: 30px 0;
        }

        .otp-code {
            display: inline-block;
            background: #f3f0ff;
            color: #5f2ded;
            font-size: 36px;
            font-weight: 800;
            letter-spacing: 8px;
            padding: 20px 40px;
            border-radius: 12px;
            border: 2px dashed #d5ccff;
        }

        /* Footer */
        .footer {
            clear: both;
            margin-top: 20px;
            text-align: center;
            width: 100%;
        }

        .footer p,
        .footer a {
            color: #99acc2;
            font-size: 12px;
            text-align: center;
        }

        /* Mobile */
        @media only screen and (max-width: 620px) {
            table[class=body] h1 {
                font-size: 24px !important;
                margin-bottom: 10px !important;
            }

            table[class=body] p,
            table[class=body] ul,
            table[class=body] ol,
            table[class=body] td,
            table[class=body] span,
            table[class=body] a {
                font-size: 16px !important;
            }

            table[class=body] .wrapper,
            table[class=body] .article {
                padding: 15px !important;
            }

            table[class=body] .content {
                padding: 0 !important;
            }

            table[class=body] .container {
                padding: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body"
        style="border-collapse:separate; mso-table-lspace:0pt; mso-table-rspace:0pt; background-color:#f6f9fc; width:100%;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
            <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                <div class="content">

                    <!-- START CENTERED WHITE CONTAINER -->
                    <table role="presentation" class="main"
                        style="border-collapse:separate; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%;">

                        <!-- Logo Area -->
                        <tr>
                            <td class="wrapper"
                                style="font-family: sans-serif; font-size: 14px; vertical-align: top; text-align: center; background-color: transparent; border: none; box-shadow: none; padding-bottom: 0;">
                                <!-- You can uncomment this if you have a logo URL -->
                                <!-- <img src="logo_url_here" alt="NetFusion" width="60" style="border:0;"> -->
                                <div style="font-size: 20px; font-weight: 800; color: #5f2ded;">NetFusion</div>
                            </td>
                        </tr>

                        <tr>
                            <td class="wrapper"
                                style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box;">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                    style="border-collapse:separate; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%;">
                                    <tr>
                                        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                            <h1>Kode Verifikasi Anda</h1>
                                            <p style="text-align: center;">Halo, <strong>{{ $user->name }}</strong></p>
                                            <p style="text-align: center;">Untuk mengamankan akun NetFusion Anda, harap
                                                masukkan kode verifikasi berikut ke dalam aplikasi:</p>

                                            <!-- OTP Code -->
                                            <div class="otp-box">
                                                <span class="otp-code">{{ $otp }}</span>
                                            </div>

                                            <p style="text-align: center; font-size: 13px; color: #8898aa;">
                                                Kode ini akan kedaluwarsa dalam 15 menit.<br>
                                                Jangan berikan kode ini kepada siapa pun.
                                            </p>

                                            <div style="border-top: 1px solid #e9eff5; margin: 25px 0 15px 0;"></div>

                                            <p
                                                style="text-align: center; font-size: 12px; color: #8898aa; margin-bottom: 0;">
                                                Jika Anda tidak melakukan permintaan ini, abaikan email ini.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- END MAIN CONTENT AREA -->
                    </table>

                    <!-- START FOOTER -->
                    <div class="footer">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0"
                            style="border-collapse:separate; mso-table-lspace:0pt; mso-table-rspace:0pt; width:100%;">
                            <tr>
                                <td class="content-block"
                                    style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px;">
                                    <span class="apple-link"
                                        style="color: #99acc2; font-size: 12px; text-align: center;">
                                        &copy; {{ date('Y') }} NetFusion Dashboard. All rights reserved.
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- END FOOTER -->

                </div>
            </td>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
        </tr>
    </table>
</body>

</html>
