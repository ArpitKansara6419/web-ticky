<div class="mb-4">
    <label for="{{ $id }}" class="block mb-1 text-sm font-medium text-gray-900 dark:text-white">{{ $label }}</label>
    <div class="flex items-center">
        <!-- Country Code Dropdown -->
        <div class="w-1/3">
            <select id="dropdown-{{ $id }}" name="country_code" class="w-full flex-shrink-0 z-10 py-2.5 px-2 text-sm font-medium text-left text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                @foreach($countries as $code => $Cname)
                    <option value="{{ $code }}" {{ $selectedCountry == $code ? 'selected' : '' }}>{{ $Cname }}</option>
                @endforeach
            </select>
        </div>

        <!-- Phone Number Input -->
        <div class="relative w-2/3">
            <input type="text" id="{{ $id }}" name="{{ $name }}" 
                class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-0 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" 
                pattern="\d{10}" 
                placeholder="{{ $placeholder ?? '1234567890' }}" 
                {{ $required ? 'required' : '' }} 
                value="{{ $value }}"
            />
        </div>
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-1"/>
</div>
