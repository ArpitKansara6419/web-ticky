@extends('layouts.app')

@section('title', 'Payout')

@section('content')

<div class="">
    <div class="card">

        <div class="card-header flex justify-between items-center">
            <h3 class="font-extrabold">
                {{ ucfirst($customer->name) }}'s Invoice
            </h3>
            <!-- <div class="text-center">
                    <a href="{{ route('lead.create') }}">
                        <button
                            id="drawerBtn"
                            class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 "
                            type="button"
                        >
                            Add
                        </button>
                    </a>
                </div> -->
        </div>
        {{-- card-body  --}}
        <div class="card-body relative ">

            {{-- data-table  --}}
            <div class="border border-1 border-gray-200 dark:border-gray-700 rounded-xl p-4">
                <table id="search-table" class="w-full border rounded-xl" style="border-radius: 12px">
                    <thead>
                        <tr>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Sr.
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Customer
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Payment Date
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Currency
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Total Payout
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Pay Status
                                </span>
                            </th>
                            <th class="bg-blue-100 text-gray-800 dark:bg-gray-900 px-2 py-4">
                                <span class="flex items-center justify-center text-[.85rem]">
                                    Action
                                </span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payouts as $key => $payout)
                        <tr>
                            <td class="px-auto  py-4 ">
                                <span class="flex items-center justify-center">
                                    {{ $key + 1 }}
                                </span>
                            </td>
                            <td class="px-auto  py-4 ">
                                <span class="flex items-center justify-center">
                                    {{ $payout?->customer?->name }}
                                </span>
                            </td>
                            <td class="px-auto  py-4 ">
                                <span class="flex items-center justify-center">
                                    {{ $payout->payment_date }}
                                </span>
                            </td>
                            <td class="px-auto  py-4 ">
                                <span class="flex items-center justify-center">
                                    @php
                                    $currencySymbols = [
                                    'dollar' => '$',
                                    'zloty' => 'zł',
                                    'euro' => '€',
                                    'pound' => '£'
                                    ];

                                    $currencySymbol = $currencySymbols[$payout->currency] ?? $payout->currency; // Default to original if not found
                                    @endphp

                                    <span>{{ $currencySymbol }}</span>

                                </span>
                            </td>
                            <td class="px-auto  py-4 ">
                                <span class="flex items-center justify-center">
                                    {{ $payout->gross_pay }}
                                </span>
                            </td>
                            <td class="px-auto  py-4 ">
                                <div class="flex items-center justify-center">
                                    @if ($payout->payment_status === 'completed')
                                    <x-badge type="success" label="Completed" class="" />
                                    @elseif($payout->payment_status === 'processing')
                                    <x-badge type="info" label="Processing" class="" />
                                    @elseif($payout->payment_status === 'failed')
                                    <x-badge type="warning" label="Failed" class="" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-auto  py-4 gap-2">
                                <div class="flex items-center justify-center gap-1 ">
                                    @if ($payout->payment_status == 'processing')
                                    <button type="button" title="Paid"
                                        id="customer_invoice_pay"
                                        data-customer-payout-id="{{$payout->id}}"
                                        class="editBtn  text-white bg-gradient-to-r from-green-400 bg-green-400 via-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11.917 9.724 16.5 19 7.5" />
                                        </svg>
                                        <span class="sr-only">Icon description</span>
                                    </button>
                                    @endif

                                    <a href="{{ route('client.invoice',$payout->id ) }}">
                                        <button type="button" title="Invoice"
                                            class="  text-white bg-gradient-to-r from-green-400 via-green-400 bg-green-400 to-green-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>
                                    <a href="{{ route('client.breakup',$payout->id ) }}">
                                        <button type="button" title="Invoice Breakup"
                                            class="  text-white bg-gradient-to-r from-blue-400 via-blue-400 to-blue-400 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-600  font-medium rounded-lg text-sm px-5 py-2 text-center  flex">
                                            <svg class="w-5 h-5 text-white" aria-hidden="true"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M10 3v4a1 1 0 0 1-1 1H5m4 8h6m-6-4h6m4-8v16a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1Z" />
                                            </svg>
                                            <span class="sr-only">Icon description</span>
                                        </button>
                                    </a>
                                    <div class="flex gap-1 ">
                                        <form id="deleteForm_{{$payout->id}}" action="{{ route('customer-payout.destroy', $payout->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" title="Delete"
                                                data-payout-id="{{$payout->id}}"
                                                class="deleteBtn  text-white flex bg-gradient-to-r from-red-400 via-red-500 bg-red-400 to-red-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-700 shadow-lg dark:shadow-lg  font-medium rounded-lg text-sm px-5 py-2 text-center">
                                                <svg class="w-5 h-5 text-white " aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                                </svg>
                                                {{-- Delete --}}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if (session('toast'))
            <x-toast-message type="{{ session('toast')['type'] }}" message="{{ session('toast')['message'] }}" />
            @elseif($errors->any())
            <x-toast-message type="danger" message="Oops, Something went wrong, Check the form again!" />
            @endif
        </div>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest"></script>
        <script>
            if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
                const dataTable = new simpleDatatables.DataTable("#search-table", {
                    searchable: false,
                    sortable: false,
                    header: true,
                    perPage: 5,
                    paging: false,
                });
            }
        </script>


        <style>
            .datatable-wrapper .datatable-top {
                display: flex;
                justify-content: content-between;
                /* Align items to the start */
            }

            .custom-button {
                order: 3;
                /* Set order for your custom button */
                margin-left: 10px;
                /* Add some spacing */
            }
        </style>
    </div>
</div>


@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var payoutsData = @json($payouts);

        $(document).on('click', '.deleteBtn', function() {
            var payoutId = $(this).data('payout-id');
            const confirmation = confirm('Are you sure you want to delete this Payout & related data?');
            if (confirmation) {
                $(`#deleteForm_${payoutId}`).submit();
            }
        });
    });

    $(document).on('click', '#customer_invoice_pay', function() {

        var payoutId = $(this).data('customer-payout-id');

        const confirmed = confirm('Are you sure want update payment status?');

        if (confirmed) {

            $.ajax({
                url: '{{ route("invoice-status.paid") }}',
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                data: {
                    id: payoutId
                },
                success: function(response) {
                    alert("Invoice status updated successfully.");
                    location.reload();
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error fetching ticket details. Please try again.');
                }
            });

        }

    });
</script>
@endsection