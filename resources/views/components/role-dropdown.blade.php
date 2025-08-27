<div>
    <label class="text-sm dark:text-white">
        Select Role <span class="text-red-500">*</span>
    </label>
    <x-input-dropdown name="{{ $name }}" id="{{ $id }}" placeholder="Select Role" class=""
        :options="$roles" optionalLabel="name" optionalValue="name"
        value="{{ isset($ticket) ? $ticket['lead_id'] : (isset($customer_id) || isset($lead_id) ? $lead_id : old('lead_id')) }}" />
</div>