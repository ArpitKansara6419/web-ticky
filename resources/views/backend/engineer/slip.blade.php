<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>{{ $engineer->first_name }}_{{ now()->format('F_Y') }}</title>
    <style>
        td {
            border: 1px solid slategrey;
        }

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

<body class="bg-gray-100  p-10">
    <table class="w-full border-collapse border border-gray-300 mt-2 text-[.85rem]">
        <tr>
            <td colspan="5" class="">
                <div class="flex  justify-center items-center w-full p-2 gap-8">
                    <img src="/assets/logo.png" style="width: 55px; height:55px" alt="">
                    <h2 class="text-xl font-bold text-center text-[#02307b] ">AIMBOT BUSINESS SERVICES</h2>
                </div>
            </td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 text-md  font-semibold text-center  bg-[#496979] text-white " colspan="3">salary Slip</td>
            <td class="p-2 text-md font-semibold bg-[#496979] text-white">Month</td>
            <td class="p-2 text-md font-semibold bg-[#496979] text-white">{{ \Carbon\Carbon::parse($payout->payment_date)->format('M-y') }}</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Employee Name</td>
            <td class="p-2">{{$engineer->first_name}} {{$engineer->last_name}}</td>
            <td class="p-2 font-semibold">Date of Joining</td>
            <td class="p-2 text-center" colspan="2">{{$engineer->job_start_date}}</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Designation</td>
            <td class="p-2">{{$engineer->job_title}}</td>
            <td class="p-2 font-semibold">Total Working Days</td>
            <td class="p-2 text-center" colspan="2">19</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">PESEL</td>
            <td class="p-2">XXXXX</td>
            <td class="p-2 font-semibold">Number of Working Days Attended</td>
            <td class="p-2 text-center" colspan="2">19</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Payment Mode</td>
            <td class="p-2">{{ ucwords(str_replace('_', ' ', $payout->payment_type)) }}
            </td>
            @if($engineer->job_type == 'full_time')
            <td class="p-2 font-semibold">Leaves</td>
            <td class="p-2 text-center" colspan="1">Paid</td>
            <td class="p-2 text-center" colspan="1">Unpaid</td>
            @else
            <td class="p-2 font-semibold">Number of Working Hours Attended</td>
            <td class="p-2 text-center" colspan="2">158.5</td>
            @endif
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Bank Account Number</td>
            <td class="p-2">{{ $engineer->enggPaymentDetail->account_number ?? '-' }}</td>
            @if($engineer->job_type == 'full_time')
            <td class="p-2 font-semibold">Leaves Taken</td>
            <td class="p-2 text-center" colspan="1">{{$totalPaidLeave}}</td>
            <td class="p-2 text-center" colspan="1">{{$totalUnpaidLeave}}</td>
            @else
            <td class="p-2 font-semibold">Hourly Gross Rate</td>
            <td class="p-2 text-center" colspan="2">{{ $engineer->enggCharge->hourly_charge ?? '-' }}
            </td>
            @endif
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Bank Name</td>
            <td class="p-2">{{ $engineer->enggPaymentDetail->bank_name ?? '-' }}</td>
            @if($engineer->job_type == 'full_time')
            <td class="p-2 font-semibold">Balance Leave</td>
            <td class="p-2 text-center" colspan="2">{{$leaveBalance}}</td>
            @else
            <td></td>
            <td colspan="2" class="text-center"></td>
            @endif
        </tr>
        <tr>
            <td class="py-4" colspan="5"> </td>
        </tr>
        <tr>
            <td class="p-2 text-center text-[#496979] text-md font-bold" colspan="2">Income</td>
            <td class="p-2 text-center text-[#496979] text-md font-bold" colspan="3">Deducation</td>
        </tr>
        <tr>
            <td class="p-2 text-md  font-semibold text-center  bg-[#496979] text-white ">Particulars</td>
            <td class="p-2 text-md font-semibold bg-[#496979] text-white">Amount</td>
            <td class="p-2 text-md  font-semibold text-center  bg-[#496979] text-white ">Particulars</td>
            <td class="p-2 text-md font-semibold bg-[#496979] text-white" colspan="2">Amount</td>
        </tr>
        <tr>
            <td class="p-2 text-md  ">Gross salary</td>
            <td class="p-2 text-md ">
                <div class=" justify-between text-right">
                    <span>{{ $engineerCurrency }}</span>
                    <span>{{number_format($payout->gross_pay, 2)}}</span>
                </div>
                <!-- <div class="flex justify-between">
                    <span>PLN</span>
                    <span>{{ number_format($payout->gross_pay, 2) }}</span>
                </div> -->
            </td>
            <td class="p-2 text-md flex justify-between  border-0 border-b ">
                <div class="flex items-center justify-between gap-2">
                    <label for="zus" class="text-gray-700 font-medium">ZUS</label>
                    <!-- <input
                        type="number"
                        id="zus"
                        name="zus"
                        data-type="zus"
                        value="{{$payout->ZUS}}"
                        class="w-40 px-3 py-1.5 rounded-md border border-[#38515e] focus:outline-none focus:ring-2 focus:ring-[#496979] transition duration-200 bg-white shadow-sm no-print"
                        placeholder="Enter amount" />
                    <button
                        type="button"
                        data-target="zus"
                        class="save-btn px-4 py-1.5 bg-[#496979] text-white text-sm no-print font-semibold rounded-md hover:bg-[#38515e] transition duration-200 shadow-sm">
                        Save
                    </button> -->
                </div>
            </td>

            <td class="p-2 text-md " colspan="2">
                <div class=" justify-between text-right">
                    <span>{{ $engineerCurrency }}</span>
                    <span>{{$payout->ZUS}}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="p-2 text-md  "></td>
            <td class="p-2 text-md ">
                <div class="flex justify-between">
                    <span></span>
                    <span></span>
                </div>
            </td>
            <td class="p-2 text-md  flex justify-between border-0">
                <div class="flex items-center justify-between gap-2">
                    <label for="pit" class="text-gray-700 font-medium">PIT</label>
                    <!-- <input
                        type="number"
                        id="pit"
                        name="pit"
                        data-type="pit"
                        class="w-40 px-3 py-1.5 rounded-md border border-[#38515e] focus:outline-none focus:ring-2 focus:ring-[#496979] transition duration-200 bg-white shadow-sm no-print"
                        value="{{$payout->PIT}}"
                        placeholder="Enter amount" />
                    <button
                        type="button"
                        data-target="pit"
                        class="save-btn px-4 py-1.5 bg-[#496979] text-white text-sm font-semibold rounded-md hover:bg-[#38515e] transition duration-200 shadow-sm no-print">
                        Save
                    </button> -->
                </div>
            </td>
            <td class="p-2 text-md " colspan="2">
                <div class="justify-between text-right">
                    <span>{{ $engineerCurrency }}</span>
                    <span>{{$payout->PIT}}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="py-4" colspan="5"> </td>
        </tr>
        <tr>
            <td class="p-2 text-center text-[#496979] text-md font-semibold " colspan="3">Net salary (A-B)</td>
            <td class="p-2 text-md" colspan="2">
                <div class="justify-between text-right">
                    <span>{{ $engineerCurrency }}</span>
                    <span>{{ number_format($payout->gross_pay - $payout->ZUS - $payout->PIT, 2) }}
                    </span>
                </div>
            </td>
        </tr>

    </table>
    <div class=" text-center mb-2 no-print">
        <button class="bg-[#496979] rounded text-white px-5 py-3 mt-3 border-white fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.save-btn').click(function() {
            const type = $(this).data('target');
            const inputValue = $(`input[data-type="${type}"]`).val();

            if (!inputValue) {
                alert(`Please enter a value for ${type.toUpperCase()}`);
                return;
            }

            $.ajax({
                url: '/save-zus-pit',
                method: 'POST',
                data: {
                    type: type,
                    value: inputValue,
                    engineer_id: '{{ $engineer->id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    alert(`${type.toUpperCase()} value saved successfully!`);
                    location.reload();
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        });
    });
</script>