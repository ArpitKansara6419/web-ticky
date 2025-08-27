<div>
    <label class="text-sm dark:text-white">
        Ticket Status *
    </label>
    <x-input-dropdown name="{{ $name }}" id="{{ $id }}" placeholder="Select Ticket Status" class="select2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
        :options="$ticketStatus" optionalLabel="name" optionalValue="value"
    />
</div>