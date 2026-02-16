{{-- resources/views/treasurer/savings/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $saving->receipt_number }}</title>
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
            width: 150px;
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
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .print-button button:hover {
            background-color: #2e59d9;
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
            <h2>CAPITAL INVESTMENT CLUB</h2>
            <h4>Savings Transaction Receipt</h4>
            <p>Your Trusted Financial Partner</p>
            <p>Tel: +256 XXX XXX XXX | Email: info@capitalinvestment.club</p>
        </div>

        <div class="receipt-title">
            @if($saving->transaction_type == 'deposit')
                DEPOSIT RECEIPT
            @else
                WITHDRAWAL RECEIPT
            @endif
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Receipt Number:</span>
                <span>{{ $saving->receipt_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Transaction #:</span>
                <span>{{ $saving->transaction_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span>{{ $saving->transaction_date->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span>{{ $saving->created_at->format('H:i:s') }}</span>
            </div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Member Name:</span>
                <span>{{ $saving->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Membership #:</span>
                <span>{{ $saving->user->membership_number ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span>{{ $saving->user->phone_number ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="details">
            <div class="amount-row">
                <span class="detail-label">Transaction Type:</span>
                <span style="text-transform: uppercase;">{{ $saving->transaction_type }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">Amount:</span>
                <span style="font-size: 20px;">UGX {{ number_format($saving->amount, 2) }}</span>
            </div>
            <div class="amount-row">
                <span class="detail-label">Balance After:</span>
                <span>UGX {{ number_format($saving->balance_after, 2) }}</span>
            </div>
        </div>

        @if($saving->notes)
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span>{{ $saving->notes }}</span>
            </div>
        </div>
        @endif

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Recorded By:</span>
                <span>{{ $saving->recorder->name }}</span>
            </div>
        </div>

        <div class="signature">
            <div style="text-align: center;">
                <div class="signature-line" style="margin: 0 auto;"></div>
                <p style="margin-top: 5px;">Treasurer's Signature</p>
            </div>
        </div>

        <div class="footer">
            <p>This is a computer generated receipt</p>
            <p>Thank you for banking with us!</p>
        </div>

        <div class="watermark">
            <p>Powered by Capital Investment Club Sacco System</p>
        </div>
    </div>

    <div class="print-button">
        <button onclick="window.print()">Print Receipt</button>
        <button onclick="window.close()">Close</button>
    </div>
</body>
</html>
