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
        try {
            // Generate a unique filename with year and month for better organization
            $year = date('Y');
            $month = date('m');
            $filename = $type . '_' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            // Create the storage path with year/month structure
            $path = "{$year}/{$month}/{$filename}";
            
            // Ensure the directory exists
            $fullPath = Storage::disk('products')->path($path);
            $directory = dirname($fullPath);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Check if GD is available
            if (extension_loaded('gd')) {
                // Create an Intervention Image instance and resize
                $img = Image::make($image);
                $img->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                // Store the processed image
                Storage::disk('products')->put($path, $img->encode());
            } else {
                // If GD is not available, store the original image
                Storage::disk('products')->putFileAs(
                    dirname($path),
                    $image,
                    basename($path)
                );
            }
            
            return $path;
        } catch (\Exception $e) {
            \Log::error('Error storing product image: ' . $e->getMessage());
            throw new \Exception('Failed to store image: ' . $e->getMessage());
        }
    }

    /**
     * Delete a product image
     */
    public function deleteProductImage(?string $path): void
    {
        if ($path && Storage::disk('products')->exists($path)) {
            Storage::disk('products')->delete($path);
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
            return asset('images/products/default.jpg');
        }

        // Remove any duplicate storage/products prefix if it exists
        $path = preg_replace('#^storage/products/#', '', $path);
        
        // Ensure the path is properly formatted
        $path = trim($path, '/');
        
        // Use the products disk URL
        return asset('storage/products/' . $path);
    }
} 