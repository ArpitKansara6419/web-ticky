
<div>
    @if($label)
        <label class="text-sm dark:text-white" for="{{ $id }}">
            {{ $label }}
        </label>
    @endif
    
    <div class="relative">
        <!-- Dropdown Button -->
        <p class="w-full h-10">
        <button id="{{ $id }}Button" data-dropdown-toggle="{{ $id }}Dropdown" class="inline-flex justify-center items-center w-full h-full text-sm font-medium text-left text-white bg-teal-700 rounded-lg hover:bg-teal-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 {{ $class }}" type="button">
            {{ $placeholder }}
            <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
            </svg>
        </button>
        </p>

        <!-- Dropdown Menu -->
        <div id="{{ $id }}Dropdown" class="z-10 hidden bg-white rounded-lg shadow w-60 dark:bg-gray-700">
            <!-- Search Box -->
            <div class="p-3">
                <label for="{{ $id }}Search" class="sr-only">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 19l-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input type="text" id="{{ $id }}Search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search {{ strtolower($label) }}">
                </div>
            </div>

            <!-- Options List -->
            <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200" aria-labelledby="{{ $id }}Button">
                @foreach($options as $option)
                    <li>
                        <div class="flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                            <input id="{{ $id }}-{{ $option['value'] }}" type="checkbox" name="{{ $name }}[]" value="{{ $option['value'] }}" {{ in_array($option['value'], (array)$value) ? 'checked' : '' }} class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="{{ $id }}-{{ $option['value'] }}" class="w-full ml-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">{{ $option['name'] }}</label>
                        </div>
                    </li>
                @endforeach
            </ul>
           
        </div>
    </div>
</div>

<script>
    document.getElementById('{{ $id }}Button').addEventListener('click', function() {
        const dropdown = document.getElementById('{{ $id }}Dropdown');
        dropdown.classList.toggle('hidden');
    });
</script>
