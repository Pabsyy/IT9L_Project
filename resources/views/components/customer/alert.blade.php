@props([
    'type' => 'info', // info, success, error, warning
    'message',
    'title' => null,
    'dismissible' => false
])

@php
    $bgColors = [
        'info' => 'bg-blue-50 border-blue-100',
        'success' => 'bg-green-50 border-green-100',
        'error' => 'bg-red-50 border-red-100',
        'warning' => 'bg-yellow-50 border-yellow-100'
    ];

    $textColors = [
        'info' => 'text-blue-800',
        'success' => 'text-green-800',
        'error' => 'text-red-800',
        'warning' => 'text-yellow-800'
    ];

    $iconColors = [
        'info' => 'text-blue-400',
        'success' => 'text-green-400',
        'error' => 'text-red-400',
        'warning' => 'text-yellow-400'
    ];

    $icons = [
        'info' => 'ri-information-line',
        'success' => 'ri-checkbox-circle-line',
        'error' => 'ri-error-warning-line',
        'warning' => 'ri-alert-line'
    ];
@endphp

<div class="mb-4 rounded-lg border p-4 {{ $bgColors[$type] }}" role="alert">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $icons[$type] }} {{ $iconColors[$type] }} text-lg mt-0.5"></i>
        </div>
        <div class="ml-3 w-full">
            @if($title)
                <h3 class="text-sm font-medium {{ $textColors[$type] }}">{{ $title }}</h3>
            @endif
            <div class="mt-1 text-sm {{ $textColors[$type] }}">
                {{ $message }}
            </div>
        </div>
        @if($dismissible)
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex h-8 w-8 {{ $textColors[$type] }} hover:bg-opacity-20 hover:bg-gray-500 focus:outline-none" data-dismiss-target="alert">
                <span class="sr-only">Dismiss</span>
                <i class="ri-close-line w-5 h-5"></i>
            </button>
        @endif
    </div>
</div>

@if($dismissible)
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-dismiss-target="alert"]').forEach(button => {
        button.addEventListener('click', () => {
            const alert = button.closest('[role="alert"]');
            alert.remove();
        });
    });
});
</script>
@endif 