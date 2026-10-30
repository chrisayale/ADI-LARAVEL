{{-- resources/views/admin/payments/receipt.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ __('payment_receipt') }} - {{ $transaction->receipt_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 20px;
            font-size: 14px;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px dashed #ccc;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #146c43;
        }
        .header h4 {
            margin: 5px 0;
        }
        .details {
            margin-bottom: 20px;
        }
        .details table {
            width: 100%;
        }
        .details td {
            padding: 5px 0;
        }
        .amount {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #146c43;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h2>{{ config('app.name', 'CAPITAL INVESTMENT CLUB') }}</h2>
            <h4>{{ __('payment_receipt') }}</h4>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td><strong>{{ __('receipt_number') }}:</strong></td>
                    <td class="text-right">{{ $transaction->receipt_number }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('date') }}:</strong></td>
                    <td class="text-right">{{ $transaction->payment_date->format('d/m/Y H:i') }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('member') }}:</strong></td>
                    <td class="text-right">{{ $transaction->user->name }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('membership_number') }}:</strong></td>
                    <td class="text-right">{{ $transaction->user->membership_number }}</td>
                </tr>
                @if($transaction->loanRepayment)
                <tr>
                    <td><strong>{{ __('loan') }} #:</strong></td>
                    <td class="text-right">{{ $transaction->loanRepayment->loanApplication->application_number }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('installment') }}:</strong></td>
                    <td class="text-right">{{ $transaction->loanRepayment->installment_number }}</td>
                </tr>
                @endif
                <tr>
                    <td><strong>{{ __('payment_method') }}:</strong></td>
                    <td class="text-right">
                        @if($transaction->payment_method == 'cash')
                            {{ __('cash') }}
                        @elseif($transaction->payment_method == 'bank_transfer')
                            {{ __('bank_transfer') }}
                        @elseif($transaction->payment_method == 'mobile_money')
                            {{ __('mobile_money') }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                        @endif
                    </td>
                </tr>
                @if($transaction->reference_number)
                <tr>
                    <td><strong>{{ __('reference_number') }}:</strong></td>
                    <td class="text-right">{{ $transaction->reference_number }}</td>
                </tr>
                @endif
            </table>
        </div>

        <div class="line"></div>

        <div class="amount">
            {{ __('amount_paid') }}: CDF {{ number_format($transaction->amount, 2) }}
        </div>

        <div class="line"></div>

        @if($transaction->notes)
        <div class="details">
            <p><strong>{{ __('notes') }}:</strong> {{ $transaction->notes }}</p>
        </div>
        @endif

        <div class="details">
            <table>
                <tr>
                    <td><strong>{{ __('received_by') }}:</strong></td>
                    <td class="text-right">{{ $transaction->recorder->name }}</td>
                </tr>
                <tr>
                    <td><strong>{{ __('signature') }}:</strong></td>
                    <td class="text-right">_________________</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>{{ __('computer_generated_receipt_no_signature') }}</p>
            <p>{{ __('thank_you_for_payment') }}</p>
        </div>
    </div>

    <div class="text-center no-print my-4">
    <button type="button" onclick="window.print()" class="btn btn-success me-2">
        <i class="fas fa-print me-2"></i>{{ __('print_receipt') }}
    </button>
    <button type="button" onclick="goBack()" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-2"></i>{{ __('go_back') }}
    </button>
</div>


    <script>
        window.onload = function() {
            // Auto print? Uncomment if you want
            // window.print();
        }


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