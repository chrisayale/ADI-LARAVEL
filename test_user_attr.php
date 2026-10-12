<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$user = App\Models\User::find(1);
echo "sacco_id via getAttribute: ".($user->getAttribute('sacco_id')??'null')."\n";
echo "sacco_id via attribute: ".($user->sacco_id??'null')."\n";
echo "toArray: "; print_r($user->toArray());
