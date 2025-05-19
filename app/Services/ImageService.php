<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * Store a product image and return the path
     */
    public function storeProductImage(UploadedFile $image, string $type = 'main'): string
    {
        // Generate a unique filename
        $filename = $type . '_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        
        // Create an Intervention Image instance
        $img = Image::make($image);
        
        // Resize the image while maintaining aspect ratio
        $img->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        // Create the storage path
        $path = "products/{$filename}";
        
        // Store the processed image
        Storage::put("public/{$path}", $img->encode());
        
        return $path;
    }

    /**
     * Delete a product image
     */
    public function deleteProductImage(?string $path): void
    {
        if ($path && Storage::exists("public/{$path}")) {
            Storage::delete("public/{$path}");
        }
    }

    /**
     * Update a product image
     */
    public function updateProductImage(?UploadedFile $newImage, ?string $oldImagePath, string $type = 'main'): ?string
    {
        if (!$newImage) {
            return $oldImagePath;
        }

        // Delete old image if it exists
        $this->deleteProductImage($oldImagePath);

        // Store and return path to new image
        return $this->storeProductImage($newImage, $type);
    }

    /**
     * Get the full URL for an image
     */
    public function getImageUrl(?string $path): string
    {
        if (empty($path)) {
            return asset('images/placeholder.png');
        }

        return Storage::url($path);
    }
} 