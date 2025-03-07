<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('NewPublicPhoto')) {
    function NewPublicPhoto($photo, $folder = 'photos'): string
    {
        $photoPath = $photo->store($folder, 'public');
        $photoPath = env('APP_URL').'/storage/'.$photoPath;

        return $photoPath;
    }
}
if (! function_exists('DeletePublicPhoto')) {
    function DeletePublicPhoto($path): void
    {
        $oldPhotoPath = str_replace('storage/', 'public/', $path);
        Storage::delete($oldPhotoPath);
    }

}
