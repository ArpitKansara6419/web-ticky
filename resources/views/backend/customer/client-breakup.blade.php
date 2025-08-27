<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            padding: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }
            @page {
                size: landscape;
            }
        }

        .invoice-container {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table,
        .invoice-table th,
        .invoice-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }


        .total {
            font-weight: bold;
            text-align: right;
            font-size: .9rem;
        }

        .bank-details {
            width: 50%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .bank-details,
        .bank-details th,
        .bank-details td {
            border: 1px solid black;
            padding: 5px;
        }

        .thank-you {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }

        th {
            font-size: .85rem !important;
            padding: 5px !important;
            text-align: center !important;
        }

        td {
            font-size: .8rem !important;
            padding: 5px !important;

        }

        .secondaryHeader td {
            text-align: center !important;
        }
    </style>
</head>

<body>
    @php
        $currencySymbols = [
            'euro' => '€',
            'usd' => '$',
            'dollar' => '$',
            'inr' => '₹',
            'gbp' => '£',
            'yen' => '¥',
            'zloty' => 'zł',
            'pln' => 'zł',
            'poland' => 'zł',
            // Add more as needed
        ];

        $symbol = $currencySymbols[strtolower($customerPayout->currency ?? '')] ?? 'NA';
    @endphp
    <table class="invoice-container">
        <tr>
            <td>
                <div class="flex justify-center text-center flex-col items-start">
                    <img src="{{ asset('assets/logo.png') }}" style="width: 100px; height:100px" class="ml-3" alt="">
                    <!-- <span class="uppercase  font-serif mt-1">aimbizits.com</span> -->
                </div>
            </td>

            <td>
                <p>WhatsApp link: <a href="https://wa.me/message/TEZRFLWAIRIEC1" class="underline text-blue-700">https://wa.me/message/TEZRFLWAIRIEC1</a></p>
                <p><a href="https://linkedin.com/company/aimbizit" class="underline text-blue-700">https://linkedin.com/company/aimbizit</a></p>
                <p><a href="https://www.aimbizit.com" class="underline text-blue-700">www.aimbizit.com</a></p>
            </td>
            <!-- <td class="invoice-header">ProPharma<br>29-Nov-24<br>0420241129</td> -->
            <td>
                <div class="text-right flex flex-col text-[1rem] font-semibold">
                    <!-- <span class="text-[1.5rem] text-cyan-800 font-bold mb-3">ProPharma</span> -->
                    <span class="">{{ \Carbon\Carbon::parse($customerPayout->payment_date)->subDays(30)->format('d M, Y') }}</span>
                    <span class="">{{ $customerPayout->invoice_number }}</span>
                </div>
            </td>
        </tr>
    </table>


    <table class="invoice-table">
        <tr class="bg-[#02307b] text-white">
            <th>DATE</th>
            <th>Ticket no</th>
            <th>DESCRIPTION</th>
            <th>Rate</th>
            <th>Travel</th>
            <th>Add. Hours</th>
            <th>Add. Hour Rate</th>
            <th>Tools</th>
            <th>AMOUNT</th>
        </tr>
        <tr class="bg-[#d8e1f0] secondaryHeader">
            <td>[DATE]</td>
            <td>[Ticket#]</td>
            <td>[Brief Description of Task]</td>
            <td>[Agreed Rate]</td>
            <td>[Agreed Rate]</td>
            <td></td>
            <td>[Agreed Rate]</td>
            <td></td>
            <td>In Euro</td>
        </tr>
        @foreach($payables as $key => $payable)
        <tr>
            <td>{{$payable->work_start_date}}</td>
            <td>{{$payable->ticket?->lead?->client_ticket_no ? $payable->ticket?->lead?->client_ticket_no : $payable->ticket->ticket_code}}</td>
            <td class="truncate max-w-[12rem] text-nowrap">{{$payable->ticket->scope_of_work}}</td>
            <td>{{ucfirst($payable->ticket->rate_type)}} - {{ $symbol }} {{$payable->ticket->standard_rate}}</td>
            <td>{{$payable->travel_cost ?? '0.00'}}</td>
            <td>{{$payable->total_work_time}}</td>
            <td>{{$payable->hourly_rate ?? '-'}}</td>
            <td>{{$payable->tool_cost ?? '0.00'}}</td>
            <td >
                <span class="block text-right">{{ $symbol }} {{ number_format($payable->client_payable + $payable->tool_cost + $payable->travel_cost, 2) }}</span>
            </td>
        </tr>
        @endforeach

        <tr>
            <td colspan="8" class="total">TOTAL</td>
            <td ><span class="block text-right"> {{ $symbol }}  {{$customerPayout->gross_pay ?? '0'}}</span></td>
        </tr>
    </table>

    <p class="thank-you">Thank You For Your Business!</p>

    @if($customerPayout)
    <table class="bank-details">
        <tr>
            <th colspan="2" class="bg-[#02307b] text-white">BANK Details for Payment</th>
        </tr>
        <tr>
            <td>Bank Name *</td>
            <td>{{ $customerPayout->bank_details['bank_name'] }}</td>
        </tr>
        
        <tr>
            <td>Account Holder Name *</td>
            <td>{{ $customerPayout->bank_details['account_holder_name'] }}</td>
        </tr>
        <tr>
            <td>Account Number *</td>
            <td>{{ $customerPayout->bank_details['account_number'] }}</td>
        </tr>
        <tr>
            <td>IBAN *</td>
            <td>{{ $customerPayout->bank_details['iban'] }}</td>
        </tr>
        <tr>
            <td>Country *</td>
            <td>{{ $customerPayout->bank_details['country'] }}</td>
        </tr>
    </table>
    @else
    <table class="bank-details">
        <tr>
            <th colspan="2" class="bg-[#02307b] text-white">BANK Details for Payment</th>
        </tr>
        <tr>
            <td>Bank Name *</td>
            <td>ING Bank</td>
        </tr>
        <tr>
            <td>Bank Address *</td>
            <td>Śląski SA ul. Sokolska 34, 40-086 Katowice</td>
        </tr>
        <tr>
            <td>Account Holder Name *</td>
            <td>AIMBOT BUSINESS SERVICES</td>
        </tr>
        <tr>
            <td>Account Number *</td>
            <td>93 1050 1012 1000 0090 3264 5138</td>
        </tr>
        <tr>
            <td>IBAN *</td>
            <td>PL 93 1050 1012 1000 0090 3264 5138</td>
        </tr>
        <tr>
            <td>BIC / Swift Code *</td>
            <td>INGBPLPW</td>
        </tr>
        <tr>
            <td>Country *</td>
            <td>Poland</td>
        </tr>
    </table>
    @endif
    <div class=" text-center mb-2 no-print">
        <button class="bg-cyan-500 rounded text-white px-5 py-3 mt-3 border-white fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>