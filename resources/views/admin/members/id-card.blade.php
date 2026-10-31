{{-- resources/views/admin/members/id-card.blade.php --}}
{{-- PVC Membership ID Card (CR80: 85.6mm x 53.98mm) --}}
{{-- Professional international standard design --}}
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
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .card {
            width: 100%;
            height: 100%;
            border-radius: 3mm;
            overflow: hidden;
            position: relative;
            background: #ffffff;
        }

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

        /* ========================================
           FRONT SIDE - Portrait layout
           ======================================== */
        .card-front {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: linear-gradient(135deg, #fafbfc 0%, #f0f2f5 100%);
        }

        /* Top Brand Bar */
        .brand-bar {
            background: linear-gradient(135deg, #0f3d2e 0%, #146c43 100%);
            color: #ffffff;
            padding: 2.5mm 4mm;
            display: flex;
            align-items: center;
            gap: 3mm;
            border-radius: 3mm 3mm 0 0;
        }

        .brand-logo {
            width: 14mm;
            height: 14mm;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.25);
            flex-shrink: 0;
        }

        .brand-logo i {
            font-size: 7mm;
            color: #146c43;
        }

        .brand-text {
            flex: 1;
            min-width: 0;
        }

        .brand-title {
            font-size: 4.2mm;
            font-weight: 700;
            letter-spacing: 0.05mm;
            line-height: 1.15;
            text-transform: uppercase;
        }

        .brand-subtitle {
            font-size: 2mm;
            font-weight: 400;
            opacity: 0.85;
            line-height: 1.2;
            margin-top: 0.3mm;
        }

        /* Main Content Area */
        .front-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 3.5mm 4mm;
            gap: 3mm;
            min-height: 0;
        }

        /* Member Photo Section */
        .photo-section {
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .photo-frame {
            width: 24mm;
            height: 30mm;
            border-radius: 2mm;
            overflow: hidden;
            background: #e8ebef;
            border: 1.5px solid #d0d5dd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1.5mm;
            color: #98a2b3;
            background: linear-gradient(135deg, #f2f4f7 0%, #e8ebef 100%);
        }

        .photo-placeholder i {
            font-size: 10mm;
            opacity: 0.5;
        }

        .photo-placeholder span {
            font-size: 1.8mm;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.1mm;
        }

        /* Member Identity Section */
        .identity-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
            gap: 2mm;
        }

        .member-name {
            font-size: 5mm;
            font-weight: 700;
            color: #101828;
            letter-spacing: 0.02mm;
            line-height: 1.15;
            text-transform: uppercase;
            word-break: break-word;
        }

        .member-meta {
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
            align-items: center;
        }

        .meta-row {
            display: flex;
            align-items: center;
            gap: 1.5mm;
            font-size: 2.1mm;
            font-weight: 500;
            color: #344054;
        }

        .meta-label {
            color: #667085;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05mm;
            font-size: 1.9mm;
        }

        .meta-value {
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
            font-weight: 600;
            color: #101828;
            letter-spacing: 0.1mm;
        }

        .expiry-row {
            color: #b42318;
        }

        .expiry-row .meta-value {
            color: #b42318;
            font-weight: 700;
        }

        /* NFC / Contactless indicator */
        .contactless-indicator {
            position: absolute;
            top: 2mm;
            right: 3mm;
            background: rgba(255,255,255,0.15);
            padding: 0.8mm 1.8mm;
            border-radius: 1.5mm;
            font-size: 1.5mm;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.8mm;
            letter-spacing: 0.05mm;
            backdrop-filter: blur(4px);
        }

        .contactless-indicator i {
            font-size: 1.8mm;
        }

        /* Bottom Bar */
        .bottom-bar {
            background: #f2f4f7;
            border-top: 1px solid #e4e7ec;
            padding: 2mm 4mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.2mm;
        }

        .slogan {
            font-size: 1.9mm;
            font-weight: 500;
            color: #146c43;
            font-style: italic;
            text-align: center;
            letter-spacing: 0.02mm;
        }

        .sdg-row {
            display: flex;
            gap: 1.5mm;
            justify-content: center;
        }

        .sdg-badge {
            width: 6.5mm;
            height: 6.5mm;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5mm;
            font-weight: 700;
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.15);
        }

        .sdg-1 { background: #e5243b; }
        .sdg-2 { background: #dda63a; }
        .sdg-8 { background: #a21942; }

        /* ========================================
           BACK SIDE - Landscape with QR code
           ======================================== */
        .card-back {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #ffffff;
        }

        .back-header {
            background: linear-gradient(135deg, #0f3d2e 0%, #146c43 100%);
            color: #ffffff;
            padding: 2.5mm 4mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 3mm 3mm 0 0;
        }

        .back-brand {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .back-brand .brand-logo {
            width: 12mm;
            height: 12mm;
        }

        .back-brand .brand-logo i {
            font-size: 6mm;
        }

        .back-brand-text .brand-title {
            font-size: 3.5mm;
        }

        .back-brand-text .brand-subtitle {
            font-size: 1.8mm;
        }

        .back-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 4mm;
            gap: 3mm;
        }

        /* QR Code Section - Prominent on back */
        .qr-section-back {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2.5mm;
        }

        .qr-code-frame {
            width: 36mm;
            height: 36mm;
            background: #ffffff;
            border: 1px solid #e4e7ec;
            border-radius: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5mm;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .qr-code-frame svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .qr-caption {
            font-size: 1.8mm;
            font-weight: 500;
            color: #667085;
            text-transform: uppercase;
            letter-spacing: 0.08mm;
        }

        /* Legal / Info Section */
        .legal-section {
            border-top: 1px solid #e4e7ec;
            padding-top: 2.5mm;
        }

        .legal-title {
            font-size: 2.2mm;
            font-weight: 600;
            color: #101828;
            text-transform: uppercase;
            letter-spacing: 0.05mm;
            margin-bottom: 1.5mm;
            text-align: center;
        }

        .legal-text {
            font-size: 1.7mm;
            color: #667085;
            line-height: 1.5;
            text-align: center;
            margin-bottom: 2mm;
        }

        .contact-info {
            font-size: 1.6mm;
            color: #98a2b3;
            text-align: center;
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
        }

        .back-footer {
            background: #f2f4f7;
            border-top: 1px solid #e4e7ec;
            padding: 1.5mm 4mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8mm;
            border-radius: 0 0 3mm 3mm;
        }

        .back-slogan {
            font-size: 1.7mm;
            font-weight: 500;
            color: #146c43;
            font-style: italic;
        }

        .back-sdg-row {
            display: flex;
            gap: 1.2mm;
            justify-content: center;
        }

        .back-sdg-badge {
            width: 5.5mm;
            height: 5.5mm;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3mm;
            font-weight: 700;
            color: #ffffff;
        }

        /* Side indicators */
        .side-indicator {
            position: absolute;
            bottom: 1.5mm;
            right: 2.5mm;
            font-size: 1.4mm;
            color: #98a2b3;
            font-weight: 600;
            text-transform: uppercase;
        }

        /* Utility */
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- FRONT SIDE -->
        <div class="card-front">
            <div class="brand-bar">
                <div class="contactless-indicator">
                    <i class="fas fa-wifi"></i>
                    {{ __('contactless_nfc') }}
                </div>
                <div class="brand-logo">
                    <i class="fas fa-handshake"></i>
                </div>
                <div class="brand-text">
                    <div class="brand-title">{{ __('adi_with_digital') }}</div>
                    <div class="brand-subtitle">{{ __('associations_villageoises') }}</div>
                </div>
            </div>

            <div class="front-content">
                <!-- Photo -->
                <div class="photo-section">
                    <div class="photo-frame">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="{{ $user->name }}">
                        @else
                            <div class="photo-placeholder">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ __('photo') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Identity -->
                <div class="identity-section">
                    <div class="member-name">{{ strtoupper($user->name) }}</div>

                    <div class="member-meta">
                        <div class="meta-row">
                            <span class="meta-label">{{ __('member_code') }}:</span>
                            <span class="meta-value">{{ $user->membership_number }}</span>
                        </div>
                        <div class="meta-row expiry-row">
                            <span class="meta-label">{{ __('expires_on') }}:</span>
                            <span class="meta-value">{{ $expiryDate }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bottom-bar">
                <div class="slogan">{{ __('together_for_inclusive_finance') }}</div>
                <div class="sdg-row">
                    <div class="sdg-badge sdg-1" title="{{ __('sdg_1_no_poverty') }}">1</div>
                    <div class="sdg-badge sdg-2" title="{{ __('sdg_2_zero_hunger') }}">2</div>
                    <div class="sdg-badge sdg-8" title="{{ __('sdg_8_decent_work') }}">8</div>
                </div>
            </div>

            <div class="side-indicator">{{ __('front') }}</div>
        </div>

        <!-- BACK SIDE -->
        <div class="card-back hidden">
            <div class="back-header">
                <div class="back-brand">
                    <div class="brand-logo">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="back-brand-text">
                        <div class="brand-title">{{ __('adi_with_digital') }}</div>
                        <div class="brand-subtitle">{{ __('associations_villageoises') }}</div>
                    </div>
                </div>
            </div>

            <div class="back-content">
                <!-- QR Code - Main feature on back -->
                <div class="qr-section-back">
                    <div class="qr-code-frame">
                        {!! $qrCodeSvg ?? '' !!}
                    </div>
                    <div class="qr-caption">{{ __('member_code') }}</div>
                </div>

                <!-- Legal / Contact Info -->
                <div class="legal-section">
                    <div class="legal-title">{{ __('card_back_title') }}</div>
                    <div class="legal-text">{{ __('card_back_text') }}</div>
                    <div class="contact-info">{{ __('card_back_contact') }}</div>
                </div>
            </div>

            <div class="back-footer">
                <div class="back-slogan">{{ __('together_for_inclusive_finance') }}</div>
                <div class="back-sdg-row">
                    <div class="back-sdg-badge sdg-1" title="{{ __('sdg_1_no_poverty') }}">1</div>
                    <div class="back-sdg-badge sdg-2" title="{{ __('sdg_2_zero_hunger') }}">2</div>
                    <div class="back-sdg-badge sdg-8" title="{{ __('sdg_8_decent_work') }}">8</div>
                </div>
            </div>

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