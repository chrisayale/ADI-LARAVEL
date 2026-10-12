<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$users = App\Models\User::all();
foreach($users as $u){
    echo $u->id . ' sacco_id: '. ($u->sacco_id ?? 'null') . ' name: '.$u->name."\n";
}
