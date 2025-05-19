<div class="bg-white rounded shadow p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-gray-500 text-sm font-medium">{{ $title }}</h3>
        <div class="w-10 h-10 rounded-full {{ $iconBg }} flex items-center justify-center">
            <i class="{{ $icon }} {{ $iconColor }} ri-lg"></i>
        </div>
    </div>
    <p class="text-3xl font-bold text-gray-800">{{ $value }}</p>
    <p class="text-sm {{ $changeType === 'increase' ? 'text-green-500' : 'text-red-500' }} mt-2 flex items-center">
        <div class="w-4 h-4 flex items-center justify-center mr-1">
            <i class="ri-arrow-{{ $changeType === 'increase' ? 'up' : 'down' }}-line"></i>
        </div>
        <span>{{ $change }} {{ isset($changePeriod) ? $changePeriod : 'from last month' }}</span>
    </p>
</div> 