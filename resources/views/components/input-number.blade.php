@php
    $finalClass = $class . ' bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';
    $isRequired = isset($attributes['required']) !== false;
@endphp

@if($label)
    <label for="{{ $id }}" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">
        {{ $label }}
        @if($isRequired)
            <span class="text-red-500">*</span>
        @endif
    </label>
@endif

@if($value || $name ) <!-- Check if the label or value exists -->
    <input 
        type="number" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        class="{{ $finalClass }}" 
        placeholder="{{ $placeholder }}" 
        value="{{ $value }}" 
        {{ $attributes }}
        step="0.01"
    />
@endif

<x-input-error :messages="$errors->get($name)" class="mt-1" />
