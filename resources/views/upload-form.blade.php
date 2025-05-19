@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold mb-6">Upload Image</h2>
        
        <form action="{{ route('image.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                    Choose Image
                </label>
                <input type="file" 
                       name="image" 
                       id="image" 
                       accept="image/*"
                       class="w-full border border-gray-300 rounded p-2">
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Upload Image
            </button>
        </form>

        <!-- Preview Image -->
        <div class="mt-4 hidden" id="imagePreview">
            <h3 class="text-lg font-semibold mb-2">Preview:</h3>
            <img id="preview" src="#" alt="Preview" class="max-w-full h-auto rounded">
        </div>
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('image').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
    
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.classList.remove('hidden');
        }
        
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endsection 