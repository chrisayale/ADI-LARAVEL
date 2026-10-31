{{-- resources/views/admin/members/id-card.blade.php --}}
{{-- PVC Membership ID Card (CR80 Portrait: 53.98mm x 85.6mm) --}}
{{-- Precision positioning: only 4 dynamic values over background image --}}
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

        html, body {
            width: 53.98mm;
            height: 85.6mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            page-break-after: avoid;
            page-break-inside: avoid;
            page-break-before: avoid;
        }

        .card {
            position: relative;
            width: 53.98mm;
            height: 85.6mm;
            overflow: hidden;
            border-radius: 2.5mm;
            page-break-inside: avoid;
            page-break-after: avoid;
            page-break-before: avoid;
        }

        /* Print styles - strict single page */
        @media print {
            @page {
                size: 53.98mm 85.6mm;
                margin: 0;
                padding: 0;
            }
            html, body {
                width: 53.98mm;
                height: 85.6mm;
                margin: 0;
                padding: 0;
                overflow: hidden;
            }
            * {
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
            }
        }

        /* Background image as <img> tag - z-index 1 */
        .card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 53.98mm;
            height: 85.6mm;
            object-fit: cover;
            z-index: 1;
        }

        /* Dynamic content elements - z-index 2, absolute positioning only */
        .qr-frame,
        .member-name,
        .member-code-value,
        .member-expiry-value {
            position: absolute;
            z-index: 2;
        }

        /* QR Frame - Centered at top: 23mm, size 22mm */
        .qr-frame {
            top: 23mm;
            left: 50%;
            transform: translateX(-50%);
            width: 22mm;
            height: 22mm;
            background: #ffffff;
            border: 3px solid #f59e0b;
            border-radius: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5mm;
            box-shadow:
                0 4px 16px rgba(0,0,0,0.15),
                0 0 0 1px rgba(245,158,11,0.3),
                inset 0 0 0 1px rgba(255,255,255,0.8);
        }

        .qr-frame img {
            width: 100%;
            height: 100%;
            display: block;
        }

        /* Member Name - Centered in BLUE CENTRAL BAND */
        .member-name {
            top: 50mm; /* Adjusted to center vertically in yellow badge */
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            font-size: 3.2mm;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.04mm;
            line-height: 1.1;
            text-transform: uppercase;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
            word-break: break-word;
        }

        /* Member Code Value - Centered in YELLOW/WHITE SLOT (line "Code membre :") */
        .member-code-value {
            top: 56.5mm; /* Centered vertically in white oval slot */
            left: 71%;
            transform: translateX(-50%);
            font-size: 2mm;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.08mm;
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
            text-align: center;
            white-space: nowrap;
        }

        /* Expiry Value - Centered in YELLOW/WHITE SLOT (line "Expire le :") in BLACK */
        .member-expiry-value {
            top: 73.5mm; /* Adjusted to center in the yellow/white slot */
            left: 50%;
            transform: translateX(-50%);
            font-size: 3.0mm;
            font-weight: 700;
            color: #000000; /* Black as requested */
            letter-spacing: 0.08mm;
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
            text-align: center;
            white-space: nowrap;
        }

        /* Side indicator */
        .side-indicator {
            position: absolute;
            bottom: 1mm;
            right: 1.5mm;
            z-index: 2;
            font-size: 1.1mm;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05mm;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        /* Back side (minimal) */
        .card-back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f8fafc;
            padding: 3.5mm;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 3mm;
        }

        .back-qr-frame {
            width: 35mm;
            height: 35mm;
            background: #ffffff;
            border: 1.5px solid #d1d5db;
            border-radius: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2mm;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            margin: 0 auto;
        }

        .back-qr-frame img {
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
            font-size: 1.5mm;
            color: #9ca3af;
            line-height: 1.4;
            max-width: 42mm;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Background Image as <img> with Base64 data URI for universal rendering -->
        <img src="{!! $bgBase64 ?? '' !!}" class="card-bg" alt="Background">

        <!-- FRONT SIDE - Vertical Portrait -->
        <div class="card-front">
            <!-- QR Code - Centered at 18mm -->
            <div class="qr-frame">
                <img src="{!! $qrCodeSvg ?? '' !!}" alt="{{ __('member_code') }}" />
            </div>

            <!-- Member Name - Centered in Blue Band -->
            <div class="member-name">{{ strtoupper($user->name) }}</div>

            <!-- Member Code Value Only - Centered in Yellow/White Slot -->
            <div class="member-code-value">{{ $user->membership_number }}</div>

            <!-- Expiry Value Only - Centered in Yellow/White Slot (Black) -->
            <div class="member-expiry-value">{{ $expiryDate }}</div>

            <div class="side-indicator">{{ __('front') }}</div>
        </div>

        <!-- BACK SIDE - Minimal with QR -->
        <div class="card-back hidden">
            <div class="back-qr-frame">
                <img src="{!! $qrCodeSvg ?? '' !!}" alt="{{ __('member_code') }}" />
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