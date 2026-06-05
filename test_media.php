<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$media = \Spatie\MediaLibrary\MediaCollections\Models\Media::latest()->first();
if ($media) {
    echo json_encode([
        'id' => $media->id,
        'model_type' => $media->model_type,
        'collection_name' => $media->collection_name,
        'file_name' => $media->file_name,
        'generated_conversions' => $media->generated_conversions,
        'path' => $media->getPath(),
        'url' => $media->getUrl(),
        'hero_url' => $media->hasGeneratedConversion('hero') ? $media->getUrl('hero') : 'not generated',
        'card_url' => $media->hasGeneratedConversion('card') ? $media->getUrl('card') : 'not generated'
    ], JSON_PRETTY_PRINT);
} else {
    echo "No media found";
}
