<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Work Schedule</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }

            @page {
                size: landscape;
            }
        }
    </style>
</head>

<body class="p-10 bg-gray-100">
    <table class="w-full border-collapse border border-cyan-800 text-[.85rem] text-left">
        <thead>
            <tr>
                <th class="border border-cyan-800 p-2" colspan="{{$ticketWorks[0]->engineer->job_type !== 'full_time' ? 12 : 13 }}">
                    <h2 class="text-center text-xl font-bold text-[#206487]"> {{ $ticketWorks[0]->engineer->first_name }} {{ $ticketWorks[0]->engineer->last_name}} Work Schedule - {{ now()->format('F Y') }}</h2>
                </th>
            </tr>
            <tr class="bg-[#02307b] text-white">
                <th class="border border-cyan-800 p-2">ID</th>
                <th class="border border-cyan-800 p-2">DATE</th>
                <th class="border border-cyan-800 p-2">DESCRIPTION</th>
                <th class="border border-cyan-800 p-2">Total Time</th>
                <th class="border border-cyan-800 p-2">Gross Rate/hour</th>
                @if($ticketWorks[0]->engineer->job_type !== 'full_time')
                <th class="border border-cyan-800 p-2">Payable</th>
                @endif
                <th class="border border-cyan-800 p-2">OT</th>
                <th class="border border-cyan-800 p-2">OOH</th>
                <th class="border border-cyan-800 p-2">WW</th>
                <th class="border border-cyan-800 p-2">HW</th>
                <th class="border border-cyan-800 p-2">Exp</th>
                <th class="border border-cyan-800 p-2">Price</th>
            </tr>
        </thead>
        <tbody>
            @php
            $grandTotal = 0;
            $zusTotal = 0;
            $pitTotal = 0;
            @endphp
            @foreach ($ticketWorks as $work)
            <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">{{ $loop->iteration }}</td>
                <!-- <td class="border border-cyan-800 p-2">{{ \Carbon\Carbon::parse($work->date)->format('d-m-Y') }}</td> -->
                <td class="border border-cyan-800 p-2">{{ $work->work_start_date }}</td>
                <td class="border border-cyan-800 p-2">
                    <div class="font-medium text-cyan-800 border-b pb-1 text-md">
                        {{ $work->ticket->ticket_code }}
                    </div>
                    <div>
                        {{ $work->ticket->task_name }}
                    </div>
                </td>
                <td class="border border-cyan-800 p-2">
                    {{ $work->total_work_time_count }}
                </td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->hourly_rate }}</td>
                @if($ticketWorks[0]->engineer->job_type !== 'full_time')
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->hourly_payable }} </td>
                @endif
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->overtime_payable }} </td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->out_of_office_payable }} </td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->weekend_payable }} </td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->holiday_payable }} </td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->other_pay }} </td>
                <!-- <td class="border border-cyan-800 p-2">   {{ $work->currency_short }} {{ $work->hourly_rate * $work->total_work_time }}</td>  -->
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ $work->daily_gross_pay }}</td>

                @php
                $grandTotal += $work->daily_gross_pay;
                $zusTotal += $work->ZUS;
                $pitTotal += $work->PIT;
                @endphp
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="border-cyan-800 font-bold">
                <td colspan="{{$ticketWorks[0]->engineer->job_type !== 'full_time' ? 11 : 10 }}" class="border-cyan-800 p-2 text-right">ZUS:</td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ number_format($zusTotal, 2) }}</td>
            </tr>
            <tr class="border-cyan-800 font-bold">
                <td colspan="{{$ticketWorks[0]->engineer->job_type !== 'full_time' ? 11 : 10 }}" class="border-cyan-800 p-2 text-right">PIT:</td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ number_format($pitTotal, 2) }}</td>
            </tr>
            <tr class="border-cyan-800 font-bold">
                <td colspan="{{$ticketWorks[0]->engineer->job_type !== 'full_time' ? 11 : 10 }}" class="border-cyan-800 p-2 text-right">Grand Total:</td>
                <td class="border border-cyan-800 p-2"> {{ $work->currency_short }} {{ number_format($grandTotal - $zusTotal - $pitTotal,2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mb-2 no-print">
        <button class="bg-[#02307b] rounded text-white px-5 py-3 mt-3 border-white fw-semibold" onclick="window.print()">Print</button>
    </div>
</body>

</html>