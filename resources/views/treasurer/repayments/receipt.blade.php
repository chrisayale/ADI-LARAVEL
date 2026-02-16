{{-- resources/views/treasurer/repayments/receipt.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $transaction->receipt_number }}</title>
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
            <h4>Loan Repayment Receipt</h4>
            <p>Your Trusted Financial Partner</p>
            <p>Tel: +256 XXX XXX XXX | Email: info@capitalinvestment.club</p>
        </div>

        <div class="receipt-title">
            PAYMENT RECEIPT
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Receipt Number:</span>
                <span>{{ $transaction->receipt_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Transaction #:</span>
                <span>{{ $transaction->transaction_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span>{{ $transaction->payment_date->format('d/m/Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span>{{ $transaction->created_at->format('H:i:s') }}</span>
            </div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Member Name:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Membership #:</span>
                <span>{{ $transaction->user->membership_number ?? 'N/A' }}</span>
            </div>
            @if($transaction->loanRepayment && $transaction->loanRepayment->loanApplication)
            <div class="detail-row">
                <span class="detail-label">Loan #:</span>
                <span>{{ $transaction->loanRepayment->loanApplication->application_number }}</span>
            </div>
            @endif
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span>{{ str_replace('_', ' ', ucfirst($transaction->payment_method)) }}</span>
            </div>
            @if($transaction->reference_number)
            <div class="detail-row">
                <span class="detail-label">Reference:</span>
                <span>{{ $transaction->reference_number }}</span>
            </div>
            @endif
            <div class="amount-row">
                <span class="detail-label">Amount Paid:</span>
                <span style="font-size: 20px;">UGX {{ number_format($transaction->amount, 2) }}</span>
            </div>
        </div>

        @if($transaction->notes)
        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span>{{ $transaction->notes }}</span>
            </div>
        </div>
        @endif

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Recorded By:</span>
                <span>{{ $transaction->recorder->name }}</span>
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
            <p>Thank you for your payment!</p>
        </div>

        <div class="watermark">
            <p>Powered by Capital Investment Club Sacco System</p>
        </div>
    </div>

    <div class="text-center no-print my-4">
    <button type="button" onclick="window.print()" class="btn btn-success me-2">
        <i class="fas fa-print me-2"></i>Print Receipt
    </button>
    <button type="button" onclick="goBack()" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>Go Back
    </button>



    <script>
        function goBack() {
    // Try to go back to previous page
    if (document.referrer) {
        window.location.href = document.referrer;
    } else {
        // If no referrer, go to home page
        window.location.href = '{{ url("/") }}';
    }
}
    </script>
</body>
</html>
