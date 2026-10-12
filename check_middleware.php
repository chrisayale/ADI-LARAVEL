<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$middleware = $app->make(Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
print_r($middleware->except);
