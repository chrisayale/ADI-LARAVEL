{{-- resources/views/admin/members/id-card.blade.php --}}
{{-- PVC Membership ID Card (CR80 Portrait: 53.98mm x 85.6mm) --}}
{{-- Professional minimalist vertical design --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('membership_card') }} - {{ $user->membership_number }}</title>
    <style>
        @page {
            size: 53.98mm 85.6mm;
            margin: 0;
            padding: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #1a1a1a;
            background: #ffffff;
            width: 53.98mm;
            height: 85.6mm;
            overflow: hidden;
            position: relative;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .card {
            width: 100%;
            height: 100%;
            border-radius: 2.5mm;
            overflow: hidden;
            position: relative;
            background: #ffffff;
            border: 1px solid #e8e8e8;
        }

        /* Print styles */
        @media print {
            @page {
                size: 53.98mm 85.6mm;
                margin: 0;
            }
            body {
                width: 53.98mm;
                height: 85.6mm;
            }
            .no-print {
                display: none !important;
            }
        }

        /* ========================================
           VERTICAL PORTRAIT LAYOUT
           ======================================== */
        .card-front {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #ffffff;
            padding: 6mm 5mm;
            justify-content: center;
            align-items: center;
            gap: 5mm;
        }

        /* QR Code Section - Central focal point */
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .qr-frame {
            width: 38mm;
            height: 38mm;
            background: #ffffff;
            border: 2px solid #2d3748;
            border-radius: 3mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3mm;
            box-shadow:
                0 2px 8px rgba(0,0,0,0.06),
                0 0 0 1px rgba(45,55,72,0.1),
                inset 0 0 0 1px rgba(255,255,255,0.5);
        }

        .qr-frame svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Member Details Section */
        .details-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3mm;
            text-align: center;
            width: 100%;
            max-width: 44mm;
        }

        .member-name {
            font-size: 4.8mm;
            font-weight: 700;
            color: #111827;
            letter-spacing: 0.02mm;
            line-height: 1.2;
            text-transform: uppercase;
            word-break: break-word;
        }

        .divider {
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, #d1d5db, transparent);
            margin: 1mm 0;
        }

        .member-code {
            font-size: 2.4mm;
            font-weight: 500;
            color: #374151;
            letter-spacing: 0.01mm;
        }

        .member-code strong {
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
            font-weight: 600;
            color: #111827;
            letter-spacing: 0.08mm;
        }

        .expiry-date {
            font-size: 2.2mm;
            font-weight: 600;
            color: #b91c1c;
            letter-spacing: 0.02mm;
            padding-top: 1mm;
            border-top: 1px solid #e5e7eb;
            width: 100%;
        }

        .expiry-date span {
            color: #ef4444;
            font-weight: 700;
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
        }

        /* Side indicators */
        .side-indicator {
            position: absolute;
            bottom: 2mm;
            right: 2.5mm;
            font-size: 1.3mm;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05mm;
        }

        /* Back side (minimal) */
        .card-back {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #ffffff;
            padding: 5mm;
            justify-content: center;
            align-items: center;
            gap: 4mm;
        }

        .back-qr-frame {
            width: 34mm;
            height: 34mm;
            background: #ffffff;
            border: 1.5px solid #d1d5db;
            border-radius: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5mm;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }

        .back-qr-frame svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .back-caption {
            font-size: 1.8mm;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.08mm;
        }

        .back-legal {
            font-size: 1.6mm;
            color: #9ca3af;
            line-height: 1.5;
            text-align: center;
            max-width: 42mm;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- FRONT SIDE - Vertical Portrait -->
        <div class="card-front">
            <!-- QR Code - Central Element -->
            <div class="qr-section">
                <div class="qr-frame">
                    {!! $qrCodeSvg ?? '' !!}
                </div>
            </div>

            <!-- Member Details -->
            <div class="details-section">
                <div class="member-name">{{ strtoupper($user->name) }}</div>

                <div class="divider"></div>

                <div class="member-code">
                    {{ __('member_code') }}: <strong>{{ $user->membership_number }}</strong>
                </div>

                <div class="expiry-date">
                    {{ __('expires_on') }}: <span>{{ $expiryDate }}</span>
                </div>
            </div>

            <div class="side-indicator">{{ __('front') }}</div>
        </div>

        <!-- BACK SIDE - Minimal with QR -->
        <div class="card-back hidden">
            <div class="back-qr-frame">
                {!! $qrCodeSvg ?? '' !!}
            </div>
            <div class="back-caption">{{ __('member_code') }}</div>
            <div class="back-legal">{{ __('card_back_text') }}</div>
            <div class="side-indicator">{{ __('back') }}</div>
        </div>
    </div>

    <script>
        // Toggle front/back view for preview (press 'B' key)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'b' || e.key === 'B') {
                const front = document.querySelector('.card-front');
                const back = document.querySelector('.card-back');
                if (front.classList.contains('hidden')) {
                    front.classList.remove('hidden');
                    back.classList.add('hidden');
                } else {
                    front.classList.add('hidden');
                    back.classList.remove('hidden');
                }
            }
        });
    </script>
</body>
</html>