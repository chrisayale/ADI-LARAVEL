{{-- resources/views/admin/members/id-card.blade.php --}}
{{-- PVC Membership ID Card (CR80 Portrait: 53.98mm x 85.6mm) --}}
{{-- Precision positioning: only 4 dynamic values over background image --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('membership_card') }} - {{ $user->membership_number }}</title>
    <!-- html2canvas for PNG/JPG download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

        /* Screen styles - center the card on the preview page */
        html, body {
            width: 100%;
            height: 100vh;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .card-container {
            position: relative;
            width: 53.98mm;
            height: 85.6mm;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-wrapper {
            position: relative;
            width: 53.98mm;
            height: 85.6mm;
            transform-origin: center center;
            transition: transform 0.1s ease-out;
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

        /* Print styles - strict single page, no centering for PDF */
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
                background-color: transparent;
                display: block;
            }
            .card-container {
                width: 53.98mm;
                height: 85.6mm;
                transform: none !important;
            }
            .card-wrapper {
                transform: none !important;
            }
            * {
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
            }
        }

        /* Zoom controls */
        .zoom-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding: 8px 16px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .zoom-controls label {
            font-size: 13px;
            font-weight: 500;
            color: #374151;
            white-space: nowrap;
        }

        .zoom-controls input[type="range"] {
            width: 200px;
            height: 6px;
            -webkit-appearance: none;
            appearance: none;
            background: #e5e7eb;
            border-radius: 3px;
            outline: none;
            cursor: pointer;
        }

        .zoom-controls input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 18px;
            height: 18px;
            background: #3b82f6;
            border-radius: 50%;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .zoom-controls input[type="range"]::-webkit-slider-thumb:hover {
            background: #2563eb;
            transform: scale(1.1);
        }

        .zoom-controls input[type="range"]::-moz-range-thumb {
            width: 18px;
            height: 18px;
            background: #3b82f6;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }

        .zoom-controls .zoom-value {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            min-width: 50px;
            text-align: right;
        }

        .zoom-controls button {
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .zoom-controls button:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .zoom-controls button:active {
            background: #d1d5db;
        }

        .zoom-controls .download-btn {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .zoom-controls .download-btn:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .zoom-controls .download-btn:active {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .zoom-controls .pdf-btn {
            background: #059669;
            color: white;
            border-color: #059669;
        }

        .zoom-controls .pdf-btn:hover {
            background: #047857;
            border-color: #047857;
            color: white;
        }

        .zoom-controls .pdf-btn:active {
            background: #065f46;
            border-color: #065f46;
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

        /* QR Frame - Centered at top: 25mm (moved down 2mm), size extended horizontally */
        .qr-frame {
            top: 25mm;
            left: 50%;
            transform: translateX(-50%);
            width: 25mm;  /* Increased from 22mm to 25mm (extended 3mm total: 2mm left + 1mm right) */
            height: 22mm;
            background: #ffffff;
            border: 3px solid #f59e0b;
            border-radius: 2.5mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5mm 3.5mm 1.5mm 2.5mm;  /* Asymmetric padding: left=3.5mm (was 1.5mm+2mm), right=2.5mm (was 1.5mm+1mm) */
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
            left: 75%;
            transform: translateX(-50%);
            font-size: 1.8mm;
            font-weight: 700;
            color: #000000;
            letter-spacing: 0.08mm;
            font-family: 'SF Mono', 'Monaco', 'Courier New', monospace;
            text-align: center;
            white-space: nowrap;
        }

        /* Expiry Value - Centered in YELLOW/WHITE SLOT (line "Expire le :") in BLACK */
        .member-expiry-value {
            top: 62.5mm; /* Aligned with "Expire le :" label in white oval slot */
            left: 71%;
            transform: translateX(-50%);
            font-size: 2.2mm;
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
    <!-- Zoom Controls -->
    <div class="zoom-controls" id="zoomControls">
        <label for="zoomSlider">{{ __('zoom') }}:</label>
        <input type="range" id="zoomSlider" min="0.5" max="3" step="0.1" value="1" aria-label="{{ __('zoom_level') }}">
        <span class="zoom-value" id="zoomValue">100%</span>
        <button type="button" id="zoomIn" aria-label="{{ __('zoom_in') }}">+</button>
        <button type="button" id="zoomOut" aria-label="{{ __('zoom_out') }}">-</button>
        <button type="button" id="zoomReset" aria-label="{{ __('zoom_reset') }}">{{ __('reset') }}</button>
        <button type="button" id="downloadPng" aria-label="{{ __('download_png_label') }}" class="download-btn">{{ __('download_png_label') }}</button>
        <button type="button" id="downloadPdf" aria-label="{{ __('download_pdf') }}" class="download-btn pdf-btn">{{ __('download_pdf') }}</button>
    </div>

    <!-- Hidden form for PDF download via html2canvas -->
    <form id="pdfDownloadForm" method="POST" action="{{ route('admin.users.download-pvc-card', $user) }}" style="display: none;">
        @csrf
        <input type="hidden" name="card_image" id="cardImageInput">
    </form>

    <!-- Card Container with Zoom -->
    <div class="card-container" id="cardContainer">
        <div class="card-wrapper" id="cardWrapper">
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
                    <div class="member-name">{{ strtoupper($displayName ?? $user->name) }}</div>

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

        // Zoom functionality
        (function() {
            const slider = document.getElementById('zoomSlider');
            const valueDisplay = document.getElementById('zoomValue');
            const cardWrapper = document.getElementById('cardWrapper');
            const zoomInBtn = document.getElementById('zoomIn');
            const zoomOutBtn = document.getElementById('zoomOut');
            const zoomResetBtn = document.getElementById('zoomReset');

            function updateZoom(scale) {
                cardWrapper.style.transform = 'scale(' + scale + ')';
                valueDisplay.textContent = Math.round(scale * 100) + '%';
                slider.value = scale;
            }

            slider.addEventListener('input', function() {
                updateZoom(parseFloat(this.value));
            });

            zoomInBtn.addEventListener('click', function() {
                const current = parseFloat(slider.value);
                const next = Math.min(3, current + 0.1);
                updateZoom(next);
            });

            zoomOutBtn.addEventListener('click', function() {
                const current = parseFloat(slider.value);
                const next = Math.max(0.5, current - 0.1);
                updateZoom(next);
            });

            zoomResetBtn.addEventListener('click', function() {
                updateZoom(1);
            });

            // Keyboard shortcuts for zoom
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + Plus/Minus for zoom
                if ((e.ctrlKey || e.metaKey) && (e.key === '+' || e.key === '=')) {
                    e.preventDefault();
                    const current = parseFloat(slider.value);
                    const next = Math.min(3, current + 0.1);
                    updateZoom(next);
                } else if ((e.ctrlKey || e.metaKey) && e.key === '-') {
                    e.preventDefault();
                    const current = parseFloat(slider.value);
                    const next = Math.max(0.5, current - 0.1);
                    updateZoom(next);
                } else if ((e.ctrlKey || e.metaKey) && e.key === '0') {
                    e.preventDefault();
                    updateZoom(1);
                }
            });
        })();

        // Download PNG functionality using html2canvas
        (function() {
            const downloadBtn = document.getElementById('downloadPng');
            const cardWrapper = document.getElementById('cardWrapper');
            const cardContainer = document.getElementById('cardContainer');

            if (downloadBtn && cardWrapper) {
                downloadBtn.addEventListener('click', async function() {
                    // Disable button during capture
                    downloadBtn.disabled = true;
                    downloadBtn.textContent = '{{ __('downloading') }}...';

                    try {
                        // Store current transform
                        const currentTransform = cardWrapper.style.transform;

                        // Temporarily reset transform for accurate capture
                        cardWrapper.style.transform = 'none';

                        // Capture the card wrapper at high resolution
                        const canvas = await html2canvas(cardWrapper, {
                            scale: 4, // High resolution for crisp output
                            useCORS: true,
                            logging: false,
                            backgroundColor: null, // Transparent background
                            width: cardWrapper.offsetWidth,
                            height: cardWrapper.offsetHeight,
                            windowWidth: cardWrapper.offsetWidth,
                            windowHeight: cardWrapper.offsetHeight,
                        });

                        // Restore transform
                        cardWrapper.style.transform = currentTransform;

                        // Generate filename
                        const filename = 'carte-membre-{{ $user->membership_number }}.png';

                        // Trigger download
                        const link = document.createElement('a');
                        link.download = filename;
                        link.href = canvas.toDataURL('image/png');
                        link.click();

                    } catch (error) {
                        console.error('Error generating PNG:', error);
                        alert('{{ __('download_error') }}');
                    } finally {
                        // Re-enable button
                        downloadBtn.disabled = false;
                        downloadBtn.textContent = '{{ __('download_png') }}';
                    }
                });
            }
        })();

        // Download PDF functionality using html2canvas + server-side PDF generation
        (function() {
            const downloadPdfBtn = document.getElementById('downloadPdf');
            const cardWrapper = document.getElementById('cardWrapper');
            const pdfForm = document.getElementById('pdfDownloadForm');
            const cardImageInput = document.getElementById('cardImageInput');

            if (downloadPdfBtn && cardWrapper && pdfForm && cardImageInput) {
                downloadPdfBtn.addEventListener('click', async function() {
                    // Disable button during capture
                    downloadPdfBtn.disabled = true;
                    downloadPdfBtn.textContent = '{{ __('generating_pdf') }}...';

                    try {
                        // Store current transform
                        const currentTransform = cardWrapper.style.transform;

                        // Temporarily reset transform for accurate capture
                        cardWrapper.style.transform = 'none';

                        // Capture the card wrapper at high resolution
                        const canvas = await html2canvas(cardWrapper, {
                            scale: 4, // High resolution for crisp output
                            useCORS: true,
                            logging: false,
                            backgroundColor: null,
                            width: cardWrapper.offsetWidth,
                            height: cardWrapper.offsetHeight,
                            windowWidth: cardWrapper.offsetWidth,
                            windowHeight: cardWrapper.offsetHeight,
                        });

                        // Restore transform
                        cardWrapper.style.transform = currentTransform;

                        // Convert to base64 and submit form
                        const base64Image = canvas.toDataURL('image/png');
                        cardImageInput.value = base64Image;
                        pdfForm.submit();

                    } catch (error) {
                        console.error('Error generating PDF:', error);
                        alert('{{ __('download_error') }}');
                    } finally {
                        // Re-enable button
                        downloadPdfBtn.disabled = false;
                        downloadPdfBtn.textContent = '{{ __('download_pdf') }}';
                    }
                });
            }
        })();
    </script>
</body>
</html>