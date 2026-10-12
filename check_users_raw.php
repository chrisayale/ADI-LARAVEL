<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$pdo = new PDO('sqlite:./database/database.sqlite');
$stmt = $pdo->query('SELECT id, sacco_id FROM users');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo $row['id'] . ' sacco_id: '.($row['sacco_id']??'null')."\n";
}
