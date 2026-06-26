@props(['name' => 'amount', 'id' => null, 'value' => null, 'required' => false, 'placeholder' => '0'])

@php
    $id = $id ?? $name;
    $formattedValue = null;
    if ($value !== null && $value !== '') {
        $formattedValue = number_format((float) str_replace(',', '.', $value), 0, ',', '.');
    }
@endphp

<div x-data="{ 
    raw: '{{ $formattedValue }}',
    hiddenVal: '{{ $value ?? '' }}',
    format(e) {
        let digits = e.target.value.replace(/\D/g, '');
        if (digits === '') {
            this.raw = '';
            this.hiddenVal = '';
            return;
        }
        this.hiddenVal = parseInt(digits).toString();
        this.raw = parseInt(digits).toLocaleString('id-ID');
    }
}">
    <div class="mt-1 relative rounded-md shadow-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">Rp</span>
        </div>
        <input type="text" 
            x-model="raw" 
            @input="format($event)" 
            class="block w-full pl-10 border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
            placeholder="{{ $placeholder }}"
            @if($required)required @endif
            inputmode="numeric">
    </div>
    <input type="hidden" name="{{ $name }}" :value="hiddenVal">
</div>
