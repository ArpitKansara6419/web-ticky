@php

    $finalClasses = " bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 ".  $class ;
    $isRequired = isset($attributes['required']) !== false;

    $inputId = str_contains($id, '[]') ? str_replace(['[', ']'], ['-', ''], $id).'-'.rand(1000,9999) : $id;
@endphp

<label 
    for="{{$inputId}}"
    class="block mb-1 text-sm font-medium text-gray-900 dark:text-white"
>
    {{$label}}
    @if($isRequired)
        <span class="text-red-500">*</span>
    @endif
</label>
<input 
    type="text" 
    id="{{$inputId}}"
    name="{{$name}}"
    value="{{ $value }}"
    class="{{ $finalClasses }}"
    placeholder="{{$placeholder}}"
    {{ $attributes }} 
/>

@if($value === "")
<x-input-error :messages="$errors->get($name)" class="mt-1" />
@endif