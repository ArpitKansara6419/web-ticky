@php
    $classes = [
        'info'      => 'bg-blue-200 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'secondary' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'danger'    => 'bg-red-200 text-red-800 dark:bg-red-900 dark:text-red-300',
        'success'   => 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300',
        'warning'   => 'bg-yellow-200 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'indigo'    => 'bg-indigo-200 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
        'purple'    => 'bg-purple-200 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'pink'      => 'bg-pink-200 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
        'offered'   => 'bg-[#1976D2] text-white dark:bg-[#E3F2FD] dark:text-black',
        'inprogress'=> 'bg-[#FBC02D] text-white dark:bg-[#FFFDE7] dark:text-black',
        'hold'      => 'bg-[#FFA000] text-white dark:bg-[#FFF3E0] dark:text-black',
        'break'     => 'bg-[#7B1FA2] text-white dark:bg-[#F3E5F5] dark:text-black',
        'close'     => 'bg-[#616161] text-white dark:bg-[#F5F5F5] dark:text-black',
        'expired'   => 'bg-[#D32F2F] text-white dark:bg-[#FFEBEE] dark:text-black',
        'accepted'  => 'bg-[#388E3C] text-white dark:bg-[#E8F5E9] dark:text-black',
    ];

    // Get the base classes based on the type
    $typeClasses = $classes[$type] ?? $classes['info'];

    // Concatenate with user-provided class
    $finalClasses = $typeClasses . ' ' . $class;
@endphp

<span class="{{ $finalClasses }} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full whitespace-nowrap" {{ $attributes }}>
    {{ $label }}
</span>
