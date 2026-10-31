{{-- resources/views/admin/members/id-card.blade.php --}}
{{-- PVC Membership ID Card (CR80: 85.6mm x 53.98mm) --}}
{{-- Optimized for PDF generation with DomPDF --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('membership_card') }} - {{ $user->membership_number }}</title>
    <style>
        @page {
            size: 85.6mm 53.98mm;
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
            line-height: 1.2;
            color: #1a1a1a;
            background: white;
            width: 85.6mm;
            height: 53.98mm;
            overflow: hidden;
            position: relative;
        }

        .card {
            width: 100%;
            height: 100%;
            border: 1px solid #e0e0e0;
            border-radius: 3mm;
            overflow: hidden;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }

        /* Header Section */
        .card-header {
            background: linear-gradient(135deg, #146c43 0%, #0d5030 100%);
            color: white;
            padding: 3mm 4mm;
            text-align: center;
            position: relative;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 2mm;
            margin-bottom: 1.5mm;
            position: relative;
            z-index: 1;
        }

        .logo-icon {
            width: 12mm;
            height: 12mm;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        .logo-icon i {
            font-size: 6mm;
            color: #146c43;
        }

        .header-text {
            text-align: left;
            position: relative;
            z-index: 1;
        }

        .header-title {
            font-size: 4.5mm;
            font-weight: 700;
            letter-spacing: 0.1mm;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 2.2mm;
            font-weight: 400;
            opacity: 0.9;
            line-height: 1.2;
            margin-top: 0.5mm;
        }

        .nfc-badge {
            position: absolute;
            top: 2mm;
            right: 3mm;
            background: rgba(255,255,255,0.2);
            padding: 1mm 2mm;
            border-radius: 2mm;
            font-size: 1.6mm;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 1mm;
            backdrop-filter: blur(4px);
        }

        .nfc-badge i {
            font-size: 2mm;
        }

        /* Main Content */
        .card-body {
            padding: 3mm 4mm;
            display: flex;
            flex-direction: column;
            height: calc(100% - 18.5mm);
            justify-content: center;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 3mm;
        }

        .qr-code {
            width: 30mm;
            height: 30mm;
            margin: 0 auto;
            background: white;
            border: 2px solid #e0e0e0;
            border-radius: 2mm;
            padding: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .qr-code img {
            width: 100%;
            height: 100%;
        }

        .qr-label {
            font-size: 1.8mm;
            font-weight: 500;
            color: #666;
            margin-top: 1.5mm;
            text-transform: uppercase;
            letter-spacing: 0.1mm;
        }

        .member-info {
            text-align: center;
        }

        .member-name {
            font-size: 5.5mm;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.15mm;
            text-transform: uppercase;
            margin-bottom: 1mm;
            line-height: 1.1;
            word-break: break-word;
        }

        .member-code-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px solid #146c43;
            border-radius: 2mm;
            padding: 2mm 3mm;
            margin: 2mm auto;
            max-width: 70mm;
            box-shadow: 0 2px 6px rgba(20, 108, 67, 0.15);
        }

        .member-code-label {
            font-size: 2mm;
            font-weight: 600;
            color: #146c43;
            text-transform: uppercase;
            letter-spacing: 0.1mm;
            margin-bottom: 0.5mm;
        }

        .member-code-value {
            font-family: 'Courier New', monospace;
            font-size: 4mm;
            font-weight: 700;
            color: #1a1a1a;
            letter-spacing: 0.3mm;
        }

        .expiry-date {
            font-size: 2.2mm;
            font-weight: 500;
            color: #666;
            margin-top: 1.5mm;
        }

        .expiry-date strong {
            color: #dc3545;
            font-weight: 700;
        }

        /* Footer Section */
        .card-footer {
            background: #f8f9fa;
            border-top: 1px solid #e0e0e0;
            padding: 2mm 3mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1mm;
        }

        .slogan {
            font-size: 2mm;
            font-weight: 500;
            color: #146c43;
            font-style: italic;
            text-align: center;
        }

        .sdg-icons {
            display: flex;
            gap: 2mm;
            justify-content: center;
        }

        .sdg-icon {
            width: 7mm;
            height: 7mm;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6mm;
            font-weight: 700;
            color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            position: relative;
        }

        .sdg-icon::before {
            content: attr(data-label);
            position: absolute;
            bottom: -4mm;
            left: 50%;
            transform: translateX(-50%);
            font-size: 1.2mm;
            color: #666;
            white-space: nowrap;
        }

        .sdg-1 { background: #e5243b; }
        .sdg-2 { background: #dda63a; }
        .sdg-8 { background: #a21942; }

        /* Print styles */
        @media print {
            @page {
                size: 85.6mm 53.98mm;
                margin: 0;
            }
            body {
                width: 85.6mm;
                height: 53.98mm;
            }
            .no-print {
                display: none !important;
            }
        }

        /* Front/Back indicator for duplex printing */
        .card-side-indicator {
            position: absolute;
            bottom: 1mm;
            right: 2mm;
            font-size: 1.5mm;
            color: #999;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- FRONT SIDE -->
        <div class="card-front">
            <!-- Header -->
            <div class="card-header">
                <div class="nfc-badge">
                    <i class="fas fa-wifi"></i>
                    {{ __('contactless_nfc') }}
                </div>
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="header-text">
                        <div class="header-title">{{ __('adi_with_digital') }}</div>
                        <div class="header-subtitle">{{ __('associations_villageoises') }}</div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body">
                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-code">
                        {!! $qrCodeSvg ?? '' !!}
                    </div>
                    <div class="qr-label">{{ __('member_code') }}</div>
                </div>

                <!-- Member Info -->
                <div class="member-info">
                    <div class="member-name">{{ strtoupper($user->name) }}</div>
                    <div class="member-code-container">
                        <div class="member-code-label">{{ __('member_code') }}</div>
                        <div class="member-code-value">{{ $user->membership_number }}</div>
                    </div>
                    <div class="expiry-date">
                        {{ __('expires_on') }}: <strong>{{ $expiryDate }}</strong>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer">
                <div class="slogan">{{ __('together_for_inclusive_finance') }}</div>
                <div class="sdg-icons">
                    <div class="sdg-icon sdg-1" data-label="{{ __('sdg_1_no_poverty') }}">1</div>
                    <div class="sdg-icon sdg-2" data-label="{{ __('sdg_2_zero_hunger') }}">2</div>
                    <div class="sdg-icon sdg-8" data-label="{{ __('sdg_8_decent_work') }}">8</div>
                </div>
            </div>

            <div class="card-side-indicator">{{ __('front') }}</div>
        </div>

        <!-- BACK SIDE (for duplex printing) -->
        <div class="card-back" style="display: none;">
            <div class="card-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="header-text">
                        <div class="header-title">{{ __('adi_with_digital') }}</div>
                        <div class="header-subtitle">{{ __('associations_villageoises') }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body" style="display: flex; flex-direction: column; justify-content: center; padding: 4mm;">
                <div style="text-align: center;">
                    <div style="font-size: 3mm; font-weight: 600; color: #146c43; margin-bottom: 3mm; text-transform: uppercase;">
                        {{ __('card_back_title') }}
                    </div>
                    <div style="font-size: 2mm; color: #666; line-height: 1.6; margin-bottom: 4mm;">
                        {{ __('card_back_text') }}
                    </div>
                    <div style="border-top: 1px solid #ddd; padding-top: 3mm;">
                        <div style="font-size: 1.8mm; color: #999;">
                            {{ __('card_back_contact') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="slogan">{{ __('together_for_inclusive_finance') }}</div>
                <div class="sdg-icons">
                    <div class="sdg-icon sdg-1" data-label="{{ __('sdg_1_no_poverty') }}">1</div>
                    <div class="sdg-icon sdg-2" data-label="{{ __('sdg_2_zero_hunger') }}">2</div>
                    <div class="sdg-icon sdg-8" data-label="{{ __('sdg_8_decent_work') }}">8</div>
                </div>
            </div>
            <div class="card-side-indicator">{{ __('back') }}</div>
        </div>
    </div>

    <script>
        // Toggle front/back view for preview
        document.addEventListener('keydown', function(e) {
            if (e.key === 'b' || e.key === 'B') {
                const front = document.querySelector('.card-front');
                const back = document.querySelector('.card-back');
                if (front.style.display === 'none') {
                    front.style.display = 'block';
                    back.style.display = 'none';
                } else {
                    front.style.display = 'none';
                    back.style.display = 'block';
                }
            }
        });
    </script>
</body>
</html>