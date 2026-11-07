<?php
$file = 'resources/views/admin/loans/create.blade.php';
$content = file_get_contents($file);

// Fix all statusDiv.html() -> statusDiv.innerHTML
$content = str_replace('statusDiv.html(', 'statusDiv.innerHTML = ', $content);

// Fix all placeholder.html() -> placeholder.innerHTML
$content = str_replace('placeholder.html(', 'placeholder.innerHTML = ', $content);

file_put_contents($file, $content);
echo "Done - Fixed .html() calls\n";
