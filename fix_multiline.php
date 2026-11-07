<?php
$file = 'resources/views/admin/loans/create.blade.php';
$content = file_get_contents($file);

// Fix multiline placeholder.innerHTML assignments
$old1 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-keyboard fa-3x text-muted mb-2\"></i>' +
                '<p class=\"text-muted\">' + qrScannerTranslations.manual_qr_input + '</p>' +
                '<button type=\"button\" class=\"btn btn-primary\" onclick=\"promptManualQrInput()\">' +
                '<i class=\"fas fa-edit\"></i> Saisir manuellement</button>'
            );";

$new1 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-keyboard fa-3x text-muted mb-2\"></i>' +
                '<p class=\"text-muted\">' + qrScannerTranslations.manual_qr_input + '</p>' +
                '<button type=\"button\" class=\"btn btn-primary\" onclick=\"promptManualQrInput()\">' +
                '<i class=\"fas fa-edit\"></i> Saisir manuellement</button>';";

$old2 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-exclamation-circle fa-3x text-danger mb-2\"></i>' +
                '<p class=\"text-danger\">' + errorMsg + '</p>' +
                '<button type=\"button\" class=\"btn btn-primary\" onclick=\"promptManualQrInput()\">' +
                '<i class=\"fas fa-edit\"></i> ' + qrScannerTranslations.manual_qr_input + '</button>'
            );";

$new2 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-exclamation-circle fa-3x text-danger mb-2\"></i>' +
                '<p class=\"text-danger\">' + errorMsg + '</p>' +
                '<button type=\"button\" class=\"btn btn-primary\" onclick=\"promptManualQrInput()\">' +
                '<i class=\"fas fa-edit\"></i> ' + qrScannerTranslations.manual_qr_input + '</button>';";

$old3 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-camera fa-3x text-muted mb-2\"></i>' +
                '<p class=\"text-muted\">' + qrScannerTranslations.camera_starting + '</p>'
            );";

$new3 = "            placeholder.innerHTML = 
                '<i class=\"fas fa-camera fa-3x text-muted mb-2\"></i>' +
                '<p class=\"text-muted\">' + qrScannerTranslations.camera_starting + '</p>';";

$content = str_replace($old1, $new1, $content);
$content = str_replace($old2, $new2, $content);
$content = str_replace($old3, $new3, $content);

file_put_contents($file, $content);
echo "Done - Fixed multiline assignments\n";
