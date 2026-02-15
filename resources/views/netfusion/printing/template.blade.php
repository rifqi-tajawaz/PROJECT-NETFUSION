<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Vouchers - {{ ucfirst($mode ?? 'default') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Courier+Prime&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #eee;
        }

        /* Control Bar */
        .no-print {
            position: sticky;
            top: 0;
            background: #2c3e50;
            color: white;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            margin-left: 10px;
        }

        /* General Container */
        .preview-container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        /* --- STANDARD MODE (Credit Card) --- */
        .mode-default .voucher {
            width: 220px;
            height: 140px;
            background: white;
            border: 1px solid #ddd;
            padding: 12px;
            box-sizing: border-box;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            page-break-inside: avoid;
        }

        .mode-default .voucher-header {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            text-align: center;
            color: #2c3e50;
        }

        .mode-default .voucher-body {
            text-align: center;
        }

        .mode-default .code-box {
            font-family: 'Courier Prime', monospace;
            background: #f8f9fa;
            border: 1px dashed #95a5a6;
            padding: 4px;
            margin: 4px 0;
            font-weight: bold;
            font-size: 16px;
        }

        .mode-default .voucher-footer {
            font-size: 10px;
            text-align: center;
            color: #7f8c8d;
        }

        /* --- THERMAL MODE (Roll) --- */
        .mode-thermal .preview-container {
            display: block;
            width: 300px;
            /* Force approximate thermal width on screen */
            margin: 0 auto;
        }

        .mode-thermal .voucher {
            width: 100%;
            border-bottom: 1px dashed black;
            padding: 15px 0;
            background: white;
            text-align: center;
            page-break-inside: avoid;
        }

        .mode-thermal .voucher-header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .mode-thermal .code-box {
            font-family: 'Courier Prime', monospace;
            font-size: 20px;
            font-weight: bold;
            margin: 5px 0;
            padding: 5px;
            border: 2px solid #000;
        }

        /* --- PRINT OVERRIDES --- */
        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .preview-container {
                padding: 0;
                width: 100% !important;
                margin: 0;
            }

            /* Standard Grid Reset */
            .mode-default .voucher {
                border: 1px solid #000;
                /* High contrast for print */
                box-shadow: none;
                float: left;
                margin: 5px;
            }

            /* Thermal Specifics */
            .mode-thermal .preview-container {
                display: block;
            }

            .mode-thermal .voucher {
                width: 100%;
                border: none;
                border-bottom: 1px dashed black;
                margin: 0;
                padding: 10px 0;
            }
        }
    </style>
</head>

<body class="mode-{{ $mode ?? 'default' }}">

    <!-- Control Bar -->
    <div class="no-print">
        <div>
            <strong>Preview: {{ ucfirst($mode ?? 'Default') }} Mode</strong>
            @if(($mode ?? 'default') == 'thermal')
                <small style="opacity: 0.8; margin-left: 10px;">For 58mm/80mm thermal printers</small>
            @endif
        </div>
        <div>
            <button onclick="window.print()" class="btn btn-primary">🖨️ Print</button>
            <button onclick="window.close()" class="btn btn-secondary">Close</button>
        </div>
    </div>

    <div class="preview-container">
        @foreach($users as $user)
            <div class="voucher">
                <div class="voucher-header">
                    {{ config('app.name', 'NetFusion') }}
                    <span
                        style="font-size: 10px; font-weight: normal; display: block; margin-top: 2px;">{{ $user['profile'] ?? 'User' }}</span>
                </div>

                <div class="voucher-body">
                    <div style="font-size: 10px; color: #7f8c8d; margin-bottom: 2px;">Voucher Code</div>
                    <div class="code-box">
                        {{ $user['name'] }}
                    </div>
                    @if(($user['name'] ?? '') !== ($user['password'] ?? ''))
                        <div style="font-size: 10px; color: #7f8c8d; margin-bottom: 2px; margin-top: 5px;">Password</div>
                        <div class="code-box">{{ $user['password'] }}</div>
                    @endif
                </div>

                <div class="voucher-footer">
                    Login: http://{{ $dnsName ?? 'hotspot.local' }}<br>
                    @if(isset($user['limit-uptime']))
                        Valid: {{ $user['limit-uptime'] }}
                    @endif
                    @if(isset($user['limit-bytes-total']))
                        | Limit: {{ \App\Helpers\Format::bytes($user['limit-bytes-total']) }}
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    @if(($mode ?? 'default') == 'thermal')
        <div style="text-align: center; font-size: 10px; padding: 20px;" class="no-print text-muted">
            --- End of Preview ---
        </div>
    @endif

</body>

</html>
