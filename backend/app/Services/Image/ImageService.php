<?php

namespace App\Services\Image;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    public function upload(UploadedFile $file, string $path = 'products'): string
    {
        $fileName =
        Str::uuid()
        . '.'
        . $file->extension();
        $storedPath = $file->storeAs($path, $fileName, 'public');
        
        if (!$storedPath) {
            throw new \Exception('فایل ذخیره نشد!');
        }
        
        return $storedPath; // returns "products/1/filename.jpg"
    }

    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }
}