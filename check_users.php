<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::with('roles')->get();
foreach($users as $u){
    echo $u->id . ' ' . $u->name . ' sacco_id: ' . ($u->sacco_id ?? 'null') . ' roles: ';
    foreach($u->roles as $r){
        echo $r->name . ' ';
    }
    echo "\n";
}
