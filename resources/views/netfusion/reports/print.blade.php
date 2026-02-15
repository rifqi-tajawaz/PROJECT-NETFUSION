<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('netfusion.selling_report') }} - {{ $summary['period'] ?? __('netfusion.all_time') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }

        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .summary-item {
            text-align: center;
        }

        .summary-item .label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .summary-item .value.text-success {
            color: #198754;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: #333;
            color: white;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .text-end {
            text-align: right !important;
        }

        .fw-bold {
            font-weight: 600;
        }

        .text-success {
            color: #198754;
        }

        .text-muted {
            color: #6c757d;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: 600;
            background: #e9ecef;
            border-radius: 3px;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 11px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-container {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <div class="header">
            <h1>{{ __('netfusion.selling_report') }}</h1>
            <p>{{ $summary['period'] ?? __('netfusion.all_time') }}</p>
            <p class="text-muted">{{ __('netfusion.generated') }}: {{ now()->format('F d, Y - H:i') }}</p>
        </div>

        <div class="summary">
            <div class="summary-item">
                <div class="label">{{ __('netfusion.total_sales') }}</div>
                <div class="value">{{ $summary['total_count'] ?? 0 }}</div>
            </div>
            <div class="summary-item">
                <div class="label">{{ __('netfusion.total_income') }}</div>
                <div class="value text-success">Rp {{ number_format($summary['total_price'] ?? 0, 0, '', '.') }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ __('netfusion.date_and_time') }}</th>
                    <th>{{ __('netfusion.username') }}</th>
                    <th>{{ __('netfusion.profile') }}</th>
                    <th>{{ __('netfusion.price') }}</th>
                    <th>{{ __('netfusion.comment') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $index => $report)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold">{{ $report['date'] }}</div>
                            <div class="text-muted small">{{ $report['time'] }}</div>
                        </td>
                        <td>{{ $report['username'] }}</td>
                        <td>
                            @if($report['profile'])
                                <span class="badge">{{ $report['profile'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">Rp {{ number_format($report['price'], 0, '', '.') }}</td>
                        <td class="text-muted">{{ $report['comment'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>{{ __('netfusion.NetFusion_manager_system') }}</p>
            <p class="text-muted small">{{ __('netfusion.generated_by') }}</p>
        </div>
    </div>

    <script>
        // Auto print when loaded
        window.onload = function () {
            window.print();
        };
    </script>
</body>

</html>
