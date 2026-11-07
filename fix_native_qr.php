<?php
$file = 'resources/views/admin/loans/create.blade.php';
$content = file_get_contents($file);

// Find the QR scanner modal and script sections
$modalStart = strpos($content, '<!-- QR Code Scanner Modal -->');
$firstPush = strpos($content, "@push('scripts')", $modalStart);
$firstEndPush = strpos($content, '@endpush', $firstPush);
$endSection = strpos($content, '@endsection', $firstEndPush);

if ($modalStart === false || $firstPush === false || $firstEndPush === false || $endSection === false) {
    die("ERROR: Could not find markers\n");
}

$before = substr($content, 0, $modalStart);
$after = substr($content, $endSection);

$newModalAndScript = <<<'NEWMODAL'
<!-- QR Code Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" role="dialog" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode"></i> {{ 'scan_qr_code_title' }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <p class="text-muted">{{ 'place_qr_code' }}</p>
                    <video id="qr-video" style="width: 100%; max-width: 400px; margin: 0 auto; display: none; transform: rotateY(180deg);"></video>
                    <canvas id="qr-canvas" style="width: 100%; max-width: 400px; margin: 0 auto; display: none;"></canvas>
                    <div id="qr-placeholder" class="text-center p-4 border rounded bg-light" style="display: block;">
                        <i class="fas fa-camera fa-3x text-muted mb-2"></i>
                        <p class="text-muted">{{ 'camera_starting' }}</p>
                    </div>
                </div>
                <div id="qr-scanner-status" class="text-center"></div>
                <div class="mt-3">
                    <button type="button" class="btn btn-secondary" id="stopScannerBtn" style="display: none;">
                        <i class="fas fa-stop"></i> Arrêter le scanner
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="switchCameraBtn" style="display: none; margin-left: 5px;">
                        <i class="fas fa-exchange-alt"></i> Changer caméra
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // QR Scanner translations - use individual JSON encoding for each translation
    const qrScannerTranslations = {
        scanner_active: @json(__('scanner_active')),
        scanner_error: @json(__('scanner_error')),
        searching_member: @json(__('searching_member')),
        member_found: @json(__('member_found')),
        member_not_found: @json(__('member_not_found')),
        manual_qr_input: @json(__('manual_qr_input')),
        camera_starting: @json(__('camera_starting')),
        camera_denied: @json(__('camera_denied')),
        camera_not_supported: @json(__('camera_not_supported')),
        barcode_api_unavailable: @json(__('barcode_api_unavailable')),
        switch_camera: @json(__('switch_camera')),
    };

    let videoStream = null;
    let barcodeDetector = null;
    let isScanning = false;
    let scanInterval = null;
    let currentFacingMode = 'environment';

    $('#openQrScannerBtn').on('click', function() {
        $('#qrScannerModal').modal('show');
    });

    $('#qrScannerModal').on('shown.bs.modal', function() {
        startQrScanner();
    });

    $('#qrScannerModal').on('hidden.bs.modal', function() {
        stopQrScanner();
    });

    $('#stopScannerBtn').on('click', function() {
        stopQrScanner();
    });

    $('#switchCameraBtn').on('click', function() {
        currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
        if (isScanning) {
            stopQrScanner();
            startQrScanner();
        }
    });

    async function startQrScanner() {
        if (isScanning) return;

        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const placeholder = document.getElementById('qr-placeholder');
        const statusDiv = document.getElementById('qr-scanner-status');

        if (!('BarcodeDetector' in window)) {
            statusDiv.html('<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> ' + qrScannerTranslations.barcode_api_unavailable + '</span>');
            placeholder.html(
                '<i class="fas fa-keyboard fa-3x text-muted mb-2"></i>' +
                '<p class="text-muted">' + qrScannerTranslations.manual_qr_input + '</p>' +
                '<button type="button" class="btn btn-primary" onclick="promptManualQrInput()">' +
                '<i class="fas fa-edit"></i> Saisir manuellement</button>'
            );
            return;
        }

        barcodeDetector = new BarcodeDetector({
            formats: ['qr_code', 'code_128', 'code_39', 'ean_13', 'ean_8', 'upc_a', 'upc_e']
        });

        placeholder.style.display = 'none';
        video.style.display = 'block';
        statusDiv.html('<span class="text-info"><i class="fas fa-spinner fa-spin"></i> ' + qrScannerTranslations.camera_starting + '</span>');

        try {
            const constraints = {
                video: {
                    facingMode: currentFacingMode,
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            };

            videoStream = await navigator.mediaDevices.getUserMedia(constraints);
            video.srcObject = videoStream;

            await new Promise((resolve) => {
                video.onloadedmetadata = () => {
                    video.play().then(resolve).catch(resolve);
                };
            });

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            isScanning = true;
            $('#stopScannerBtn').show();
            $('#switchCameraBtn').show();
            statusDiv.html('<span class="text-success"><i class="fas fa-camera"></i> ' + qrScannerTranslations.scanner_active + '</span>');

            scanInterval = setInterval(async () => {
                if (!isScanning || video.readyState !== video.HAVE_ENOUGH_DATA) return;

                try {
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const barcodes = await barcodeDetector.detect(canvas);

                    if (barcodes.length > 0) {
                        stopQrScanner();
                        $('#qrScannerModal').modal('hide');
                        processQrCode(barcodes[0].rawValue);
                    }
                } catch (err) {
                    console.debug('Barcode detection:', err);
                }
            }, 200);

        } catch (err) {
            console.error("Camera access error:", err);
            let errorMsg = qrScannerTranslations.camera_denied;
            if (err.name === 'NotAllowedError') {
                errorMsg = qrScannerTranslations.camera_denied;
            } else if (err.name === 'NotFoundError' || err.name === 'NotReadableError') {
                errorMsg = qrScannerTranslations.camera_not_supported;
            }
            statusDiv.html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ' + errorMsg + ': ' + err.message + '</span>');
            video.style.display = 'none';
            placeholder.style.display = 'block';
            placeholder.html(
                '<i class="fas fa-exclamation-circle fa-3x text-danger mb-2"></i>' +
                '<p class="text-danger">' + errorMsg + '</p>' +
                '<button type="button" class="btn btn-primary" onclick="promptManualQrInput()">' +
                '<i class="fas fa-edit"></i> ' + qrScannerTranslations.manual_qr_input + '</button>'
            );
        }
    }

    function stopQrScanner() {
        if (!isScanning && !videoStream) return;

        isScanning = false;

        if (scanInterval) {
            clearInterval(scanInterval);
            scanInterval = null;
        }

        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
            videoStream = null;
        }

        const video = document.getElementById('qr-video');
        const canvas = document.getElementById('qr-canvas');
        const placeholder = document.getElementById('qr-placeholder');
        const statusDiv = document.getElementById('qr-scanner-status');

        if (video) {
            video.srcObject = null;
            video.style.display = 'none';
        }
        if (canvas) canvas.style.display = 'none';
        if (placeholder) {
            placeholder.style.display = 'block';
            placeholder.html(
                '<i class="fas fa-camera fa-3x text-muted mb-2"></i>' +
                '<p class="text-muted">' + qrScannerTranslations.camera_starting + '</p>'
            );
        }
        if (statusDiv) statusDiv.html('');

        $('#stopScannerBtn').hide();
        $('#switchCameraBtn').hide();
    }

    window.promptManualQrInput = function() {
        const qrCode = prompt(qrScannerTranslations.manual_qr_input);
        if (qrCode && qrCode.trim()) {
            processQrCode(qrCode.trim());
        }
    }

    function processQrCode(qrCode) {
        $('#qr-scanner-status').html('<span class="text-info"><i class="fas fa-spinner fa-spin"></i> ' + qrScannerTranslations.searching_member + '</span>');

        $.ajax({
            url: '{{ route("admin.loans.find-member-qr") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                qr_code: qrCode
            },
            success: function(response) {
                if (response.success) {
                    $('#user_id').val(response.member.id).trigger('change');
                    toastr.success(qrScannerTranslations.member_found + ': ' + response.member.name + ' (' + response.member.membership_number + ')');
                    if ($('#selectedMemberInfo').length) {
                        $('#selectedMemberInfo').html(
                            '<strong>' + response.member.name + '</strong><br>' +
                            'Numéro: ' + response.member.membership_number + '<br>' +
                            'Email: ' + response.member.email + '<br>' +
                            'Téléphone: ' + response.member.phone_number
                        ).show();
                    }
                } else {
                    toastr.error(response.message || qrScannerTranslations.member_not_found);
                }
            },
            error: function(xhr) {
                let message = 'Erreur lors de la recherche du membre';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastr.error(message);
            }
        });
    }

    $(document).on('click', '#manualQrInputBtn', function() {
        promptManualQrInput();
    });
</script>
@endpush
NEWMODAL;

$content = $before . $newModalAndScript . $after;
file_put_contents($file, $content);
echo "Done - Replaced with native QR scanner\n";