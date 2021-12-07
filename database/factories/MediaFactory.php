<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Models\Media;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Media::class, function (Faker $faker) {
    static $id = 1;

    $dir = resource_path('demo_images');
    $filename = scandir($dir)[$id + 1];

    $imageFile = new \Illuminate\Http\File(resource_path('demo_images/'.$filename));
    Storage::disk('public')->putFileAs($id, $imageFile, $filename);

    return [
        'id' => $id++,
        'collection_name' => 'uploads',
        'name' => $filename,
        'file_name' => $filename,
        'model_type' => \App\Models\Post::class,
        'disk' => config('medialibrary.disk_name'),
        'mime_type' => 'image/'.$imageFile->getExtension(),
        'order_column' => $id,
        'size' => $faker->numberBetween(10, 10000),
        'manipulations' => [],
        'custom_properties' => [],
        'responsive_images' => [],
    ];
});
