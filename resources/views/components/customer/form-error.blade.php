@props([
    'message',
    'field' => null
])

<div id="{{ $field ? $field . '-error' : '' }}" class="mt-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-md p-2" role="alert">
    <div class="flex items-center gap-2">
        <i class="ri-alert-fill"></i>
        <span>{{ $message }}</span>
    </div>
</div>

@if($field)
<script>
    // Add validation handling for the field
    const input = document.getElementById('{{ $field }}');
    if (input) {
        input.addEventListener('input', function() {
            const errorDiv = document.getElementById('{{ $field }}-error');
            if (errorDiv) {
                if (this.validity.valid) {
                    errorDiv.classList.add('hidden');
                } else {
                    errorDiv.classList.remove('hidden');
                }
            }
        });
    }
</script>
@endif 