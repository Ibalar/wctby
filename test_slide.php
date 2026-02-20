<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Slide model...\n";

try {
    // Create a test slide
    $slide = new \App\Models\Slide();
    $slide->title = 'Test Slide';
    $slide->subtitle = 'Test Subtitle';
    $slide->link = 'https://example.com';
    $slide->link_text = 'Click Here';
    $slide->image = 'test-image.jpg';
    $slide->position = 1;
    $slide->active = true;
    $slide->save();

    echo "Slide created with ID: {$slide->id}\n";
    echo "Image field value: {$slide->image}\n";

    // Retrieve the slide
    $retrievedSlide = \App\Models\Slide::find($slide->id);
    echo "Retrieved slide image: {$retrievedSlide->image}\n";

    // Test the scopes
    $activeSlides = \App\Models\Slide::active()->count();
    echo "Active slides count: {$activeSlides}\n";

    $orderedSlides = \App\Models\Slide::ordered()->count();
    echo "Ordered slides count: {$orderedSlides}\n";

    // Clean up
    $slide->delete();
    echo "Slide deleted successfully.\n";

    echo "All tests passed!\n";
} catch (\Exception $e) {
    echo "Error: {$e->getMessage()}\n";
    echo "File: {$e->getFile()}\n";
    echo "Line: {$e->getLine()}\n";
}
