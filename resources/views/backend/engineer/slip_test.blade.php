<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Salary Slip</title>
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
            <td class="p-2 text-md font-semibold bg-[#496979] text-white">Nov-24</td>

        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Employee Name</td>
            <td class="p-2">XXXXX</td>
            <td class="p-2 font-semibold">Date of Joining</td>
            <td class="p-2 text-center" colspan="2">1st November 2023</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Designation</td>
            <td class="p-2">IT Support Engineer</td>
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
            <td class="p-2">Direct Deposit</td>
            <td class="p-2 font-semibold">Number of Working Hours Attended</td>
            <td class="p-2 text-center" colspan="2">158.5</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Bank Account Number</td>
            <td class="p-2">XXXXX</td>
            <td class="p-2 font-semibold">Hourly Gross Rate</td>
            <td class="p-2 text-center" colspan="2">38</td>
        </tr>
        <tr class="border border-gray-300">
            <td class="p-2 font-semibold">Bank Name</td>
            <td class="p-2">XXXXX</td>
            <td></td>
            <td colspan="2" class="text-center"></td>
        </tr>
        <tr>
            <td class="py-4" colspan="5"> </td>
        </tr>
        <tr>
            <td class="p-2 text-center bg-primary-light-one text-white text-md font-semibold" colspan="2">Income</td>
            <td class="p-2 text-center bg-primary-light-one text-white text-md font-semibold" colspan="3">Deducation</td>
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
                <div class="flex justify-between">
                    <span>PLN</span>
                    <span>6,023.00</span>
                </div>
            </td>
            <td class="p-2 text-md  ">ZUS</td>
            <td class="p-2 text-md " colspan="2">
                <div class="flex justify-between">
                    <span>PLN</span>
                    <span>-</span>
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
            <td class="p-2 text-md  ">PIT</td>
            <td class="p-2 text-md " colspan="2">
                <div class="flex justify-between">
                    <span>PLN</span>
                    <span>-</span>
                </div>
            </td>
        </tr>
        <tr>
            <td class="py-4" colspan="5"> </td>
        </tr>
        <tr>
            <td class="p-2 text-center text-[#496979] text-md font-semibold " colspan="3">Net salary (A-B)</td>
            <td class="p-2 text-md" colspan="2">
                <div class="flex justify-between">
                    <span>PLN</span>
                    <span>6,023.00</span>
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