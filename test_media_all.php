<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$media = \Spatie\MediaLibrary\MediaCollections\Models\Media::all();
foreach($media as $m) {
    echo "ID: {$m->id} | Name: {$m->file_name} | Conversions: " . json_encode($m->generated_conversions) . "\n";
}
