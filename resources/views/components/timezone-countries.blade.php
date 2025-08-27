
    <label class="text-sm dark:text-white">
        Country <span class="text-red-500">*</span>
    </label>
    <x-input-dropdown name="{{ $name }}" id="{{ $id }}" placeholder="Select Country" class="select2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
        :options="$countries" optionalLabel="name" optionalValue="name"
         />
