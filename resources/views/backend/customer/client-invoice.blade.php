<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Invoice</title>
    <style>
        td {
            border: 1px solid slategrey;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="p-5 bg-gray-100">
    <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded-md">
        <div class="flex justify-around items-center border-b pb-4">
            <div class="flex justify-center flex-col items-center">
                <img src="/assets/logo.png" style="width: 75px; height:75px" alt="">
                <span class="uppercase font-serif mt-1">aimbizits.com</span>
            </div>
            <div>
                <p><strong>Invoice nr:</strong> {{ $customerPayout->invoice_number }}</p>
                <p><strong>Place of issue:</strong> Warsaw</p>
                <p><strong>Date of Issue:</strong> {{ \Carbon\Carbon::parse($customerPayout->payment_date)->subDays(30)->format('d M, Y') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <h2 class="font-bold bg-gray-200 p-2">Supplier</h2>
                <p class="ml-2">AIMBOT BUSINESS SERVICES</p>
                <p class="ml-2">Aleja Jana Pawła II</p>
                <p class="ml-2">Number 43A, Lokal 37B, Warszawa 01-001</p>
                <p class="ml-2"><strong>KRS:</strong> 0000933886</p>
            </div>
            <div>
                <h2 class="font-bold bg-gray-200 p-2">Client</h2>
                <p class="ml-2">{{ $customerPayout->customer->name }}</p>
                <p class="ml-2">{{ $customerPayout->customer->address }}</p>
                <p class="ml-2">Address line 2</p>
                <p class="ml-2"><strong>VAT: </strong> {{ $customerPayout->customer->vat_no ?? 'NA' }} </p>
            </div>
        </div>

        <div class="mt-4">
            @include('components.bank-details', ['bank_details' => $customerPayout->bank_details] )
        </div>

        <table class="w-full mt-4 border-collapse border border-gray-300">
            <thead>
                <tr class="bg-[#02307b] text-white">
                    <th class="border border-gray-300 p-2">Description of service</th>
                    <th class="border border-gray-300 p-2">Location</th>
                    <th class="border border-gray-300 p-2">Amount</th>
                </tr>
            </thead>
            @php $total = 0; @endphp
            <tbody>
                @foreach($payables as $key => $payable)
                    @php
                        $startDates = $payables->pluck('ticketWork.work_start_date')->filter();
                        $endDates = $payables->pluck('ticketWork.work_end_date')->filter();

                        $minStartDate = $startDates->min();
                        $maxEndDate = $endDates->max();
                    @endphp
                <tr>
                    <td class="border border-gray-300 p-2">{{$payable->ticket->task_name}}</td>
                    <td class="border border-gray-300 p-2">{{$payable->ticket->city}}</td>
                    <td class="border border-gray-300 p-2 uppercase"> {{$payable->currency}} {{ $payable->client_payable + $payable->tool_cost + $payable->travel_cost }} </td>
                </tr>
                @endforeach
                <tr class="bg-gray-200 font-bold">
                    <td colspan="2" class="border border-gray-300 p-2 text-right">TOTAL</td>
                    <td class="border border-gray-300 p-2 uppercase">{{$customerPayout->currency ?? 'NA'}} {{$customerPayout->gross_pay ?? '0'}}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <p><strong>Service duration:</strong> {{ \Carbon\Carbon::parse($minStartDate)->format('d M, Y') }} - {{ \Carbon\Carbon::parse($maxEndDate)->format('d M, Y') }}</p>
            <p><strong>Terms of Payment:</strong> {{ ucwords(str_replace('_', ' ', $customerPayout->payment_type)) }}</p>
            <p><strong>Date of Payment:</strong> {{ \Carbon\Carbon::parse($customerPayout->payment_date)->format('d M, Y') }}</p>
        </div>
    </div>
    <div class=" text-center mb-2 no-print">
        <button class="bg-cyan-500 rounded text-white px-5 py-3 mt-3 border-white fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>