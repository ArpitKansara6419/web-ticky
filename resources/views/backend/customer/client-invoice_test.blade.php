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
            <img src="/assets/logo.png" style="width: 75px; height:75px" alt="">
            <div>
                <p><strong>Invoice nr:</strong> 0420241129</p>
                <p><strong>Place of issue:</strong> Warsaw</p>
                <p><strong>Date of Issue:</strong> 29-11-2024</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4">
            <div >
                <h2 class="font-bold bg-gray-200 p-2">Supplier</h2>
                <p class="ml-2">AIMBOT BUSINESS SERVICES</p>
                <p class="ml-2">Aleja Jana Pawła II</p>
                <p class="ml-2">Number 43A, Lokal 37B, Warszawa 01-001</p>
                <p class="ml-2"><strong>KRS:</strong> 0000933886</p>
            </div>
            <div>
                <h2 class="font-bold bg-gray-200 p-2">Client</h2>
                <p class="ml-2">Client1</p>
                <p class="ml-2">Address line 1</p>
                <p class="ml-2">Address line 2</p>
                <p class="ml-2"><strong>VAT:</strong></p>
            </div>
        </div>

        <div class="mt-4">
            <h2 class="font-bold bg-gray-200 p-2">Account Details</h2>
            <p class="ml-2"><strong>Account Name:</strong> AIMBOT BUSINESS SERVICES</p>
            <p class="ml-2"><strong>Bank Name:</strong> ING Bank</p>
            <p class="ml-2"><strong>Swift:</strong> INGBPLPW</p>
            <p class="ml-2"><strong>IBAN:</strong> PL 93 1050 1012 1000 0090 3264 5138</p>
        </div>

        <table class="w-full mt-4 border-collapse border border-gray-300">
            <thead>
            <tr class="bg-[#02307b] text-white">
                    <th class="border border-gray-300 p-2">Description of service</th>
                    <th class="border border-gray-300 p-2">Location</th>
                    <th class="border border-gray-300 p-2">Amount in Euro</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-gray-300 p-2">IT services</td>
                    <td class="border border-gray-300 p-2">Poland</td>
                    <td class="border border-gray-300 p-2">EUR 587.50</td>
                </tr>
                <tr class="bg-gray-200 font-bold">
                    <td colspan="2" class="border border-gray-300 p-2 text-right">TOTAL</td>
                    <td class="border border-gray-300 p-2">EUR 587.50</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <p><strong>Service duration:</strong> 01/11/2024 - 30/11/2024</p>
            <p><strong>Terms of Payment:</strong> Bank transfer</p>
            <p><strong>Date of Payment:</strong> 31/12/2024</p>
        </div>
    </div>
    <div class=" text-center mb-2 no-print">
        <button class="bg-cyan-500 rounded text-white px-5 py-3 mt-3 border-white fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>