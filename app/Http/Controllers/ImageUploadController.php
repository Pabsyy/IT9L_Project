<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
                // Get the file from the request
                $image = $request->file('image');
                
                // Create a unique filename
                $filename = time() . '_' . $image->getClientOriginalName();
                
                // Move the file to public/images directory
                $image->move(public_path('images'), $filename);
                
                return response()->json([
                    'success' => true,
                    'filename' => $filename,
                    'message' => 'Image uploaded successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No image file found'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ]);
        }
    }
} 