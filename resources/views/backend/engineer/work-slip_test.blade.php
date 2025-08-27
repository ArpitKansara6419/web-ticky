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
        }
    </style>
</head>

<body class="p-10 bg-gray-100">
    <table class="w-full border-collapse border border-cyan-800 text-[.85rem] text-left">
        <thead>
            <tr>
                <th class="border border-cyan-800 p-2" colspan="6">
                    <h2 class="text-center text-xl font-bold text-[#206487]">Rahul Gada Work Schedule November'24</h2>
                </th>
            </tr>
            <tr class="bg-[#02307b] text-white">
                <th class="border border-cyan-800 p-2">ID</th>
                <th class="border border-cyan-800 p-2">DATE</th>
                <th class="border border-cyan-800 p-2">DESCRIPTION</th>
                <th class="border border-cyan-800 p-2">Gross Rate/hour</th>
                <th class="border border-cyan-800 p-2">Total time</th>
                <th class="border border-cyan-800 p-2">Price</th>
            </tr>
        </thead>
        <tbody>
            <!-- Example rows (Repeat as necessary) -->
            <tr class="bg-white hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">1</td>
                <td class="border border-cyan-800 p-2">04-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">2</td>
                <td class="border border-cyan-800 p-2">05-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">3</td>
                <td class="border border-cyan-800 p-2">06-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">4</td>
                <td class="border border-cyan-800 p-2">07-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">5</td>
                <td class="border border-cyan-800 p-2">08-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">6</td>
                <td class="border border-cyan-800 p-2">09-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">7</td>
                <td class="border border-cyan-800 p-2">10-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <tr class="bg-gray-50 hover:bg-gray-100">
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">11-11-2024</td>
                <td class="border border-cyan-800 p-2">Administrator systemow komputerowych</td>
                <td class="border border-cyan-800 p-2">PLN 38.00</td>
                <td class="border border-cyan-800 p-2">8</td>
                <td class="border border-cyan-800 p-2">PLN 304.00</td>
            </tr>
            <!-- Add remaining rows here -->
        </tbody>
        <tfoot>
            <tr class=" border-cyan-800 font-bold">
                <td colspan="5" class="border-cyan-800  p-2 text-right">Grand Total:</td>
                <td class="border border-cyan-800 p-2">PLN 6,023.00</td>
            </tr>
        </tfoot>
    </table>
    <div class=" text-center mb-2 no-print">
        <button class="bg-[#02307b] rounded text-white px-5 py-3 mt-3 border-white  fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>