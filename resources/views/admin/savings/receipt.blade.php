<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('receipt') }} - {{ $saving->receipt_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header h4 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }
        .receipt-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0;
            text-transform: uppercase;
        }
        .details {
            margin: 15px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        .detail-label {
            font-weight: bold;
        }
        .amount-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }
        .signature {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin-top: 5px;
        }
        .watermark {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            color: #999;
        }
        .print-button {
            text-align: center;
            margin-top: 20px;
        }
        .print-button button {
            padding: 10px 30px;
            font-size: 16px;
            cursor: pointer;
        }
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>{{ config('app.name', 'CAPITAL INVESTMENT CLUB') }}</h2>
            <h4>{{ __('savings_transaction_receipt') }}</h4>
            <p>{{ __('your_trusted_financial_partner') }}</p>
            <p>{{ __('contact_info') }}</p>
        </div>

        <div class="receipt-title">
            @if($saving->transaction_type == 'deposit')
                {{ __('deposit_receipt') }}
            @else
                {{ __('withdrawal_receipt') }}
            @endif
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">{{ __('receipt_number') }}:</span>
                <span>{{ $saving->receipt_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('transaction_number') }}:</span>
                <span>{{ $saving->transaction_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('date') }}:</span>
                <span>{{ $saving->transaction_date->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('time') }}:</span>
                <span>{{ $saving->created_at->format('H:i:s') }}</span>
            </div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">{{ __('member_name') }}:</span>
                <span>{{ $saving->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('membership_number') }}:</span>
                <span>{{ $saving->user->membership_number ?? __('n_a') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('phone') }}:</span>
                <span>{{ $saving->user->phone_number ?? __('n_a') }}</span>
            </div>
        </div>

        <div class="details">
            <div class="amount-row">
                <span class="detail-label">{{ __('transaction_type') }}:</span>
                <span style="text-transform: uppercase;">{{ $saving->transaction_type == 'deposit' ? __('deposit') : __('withdrawal') }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">{{ __('amount') }}:</span>
                <span style="font-size: 20px;">UGX {{ number_format($saving->amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">{{ __('balance_after') }}:</span>
                <span>UGX {{ number_format($saving->balance_after, 2) }}</span>
            </div>
        </div>

        @if($saving->notes)
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">{{ __('notes') }}:</span>
                <span>{{ $saving->notes }}</span>
            </div>
        </div>
        @endif

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">{{ __('recorded_by') }}:</span>
                <span>{{ $saving->recorder->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ __('approved_by') }}:</span>
                <span>{{ $saving->approver->name ?? __('system') }}</span>
            </div>
        </div>

        <div class="signature">
            <div style="text-align: center;">
                <div class="signature-line" style="margin: 0 auto;"></div>
                <p style="margin-top: 5px;">{{ __('authorized_signature') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>{{ __('computer_generated_receipt') }}</p>
            <p>{{ __('thank_you_for_banking') }}</p>
        </div>

        <div class="watermark">
            <p>{{ __('powered_by_sacco_system') }}</p>
        </div>
    </div>

    <div class="print-button">
        <button onclick="window.print()">{{ __('print_receipt') }}</button>
        <button onclick="window.close()">{{ __('close') }}</button>
    </div>
</body>
</html>