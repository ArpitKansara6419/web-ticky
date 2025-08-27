@php
    $finalClass = $class . " " . 'block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500';
    $isRequired = isset($attributes['required']) !== false;
@endphp

@if($label)
    <label for="{{ $id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
        {{ $label }} 
        @if($isRequired)
            <span class="text-red-500">*</span>
        @endif
    </label>
@endif

<textarea 
    id="{{ $id }}" 
    name="{{ $name }}" 
    rows="{{ $rows }}" 
    class="{{ $finalClass }}" 
    placeholder="{{ $placeholder }}" 
    {{ $attributes }}
>{{ $value }}</textarea>

<x-input-error :messages="$errors->get($name)" class="mt-1" />

