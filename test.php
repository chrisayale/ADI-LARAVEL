<?php
require __DIR__.'/bootstrap/app.php';
$r = new App\Models\RequestSacco();
$r->name = 'Test';
$r->phone_number = '123';
$r->province = 'Test';
$r->territory = 'Test';
$r->city_village = 'Test';
$r->applicant_name = 'Test';
$r->applicant_role = 'Test';
$r->applicant_phone = '123';
$r->status = 'pending';
$r->save();
echo $r->id;
