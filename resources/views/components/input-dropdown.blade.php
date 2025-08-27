@php
    $options = $options ?? [];
    $finalClass = "dropdownField bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 " . $class;
    
@endphp

<label 
    for="{{ $name }}"
    class="block {{ $id == 'bank_type_id' ?? 'mb-2' }}  text-sm font-medium text-gray-900 dark:text-white"
>
   {{ $label }}
</label>

{{-- overflow-y-auto --}}
<div class=" ">
    <select 
        id="{{ $id }}" 
        name="{{ $name }}" 
        class="{{ $finalClass }}"
        {{ $attributes }}   
        >
        <option value="" class="">{{ $placeholder }}</option>
        
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" 
                @if(isset($value) && $value == $option['value']) selected @endif
            >
                
                @if(isset($option['extra_value']))
                    {{ $option['extra_value'] }} {{ $option['value'] }}
                @else
                    {{ $option['name'] }}
                @endif
            </option>
        @endforeach
        
    </select>
    {{--  {{print_r($options)}}  --}}
</div>
<x-input-error :messages="$errors->get($id)" class="mt-1" />

<script>
    {{--  $("#{{$id}}").select2();  --}}
</script>
