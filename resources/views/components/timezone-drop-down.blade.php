<div>
    <label class="text-sm dark:text-white">
        Timezone <span class="text-red-500">*</span>
    </label>
    <x-input-dropdown name="{{ $name }}" id="{{ $id }}" placeholder="Select Timezone" class="select2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
        :options="$timezones" optionalLabel="zoneName" optionalValue="zoneName" optionalValue2="gmtOffsetName" optionalValue3="tzName" optionalValue4="emoji"
         />
</div>