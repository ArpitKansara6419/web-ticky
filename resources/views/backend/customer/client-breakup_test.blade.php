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
    <table class="invoice-container">
        <tr>
            <td> <img src="/assets/logo.png" style="width: 75px; height:75px" alt=""> </td>
            <td>
                <p>WhatsApp link: <a href="#" class="underline text-blue-700">https://wa.me/message/TEZRFLWAIRIEC1</a></p>
                <p><a href="#" class="underline text-blue-700">https://linkedin.com/company/aimbizit</a></p>
                <p><a href="#" class="underline text-blue-700">www.aimbizit.com</a></p>
            </td>
            <!-- <td class="invoice-header">ProPharma<br>29-Nov-24<br>0420241129</td> -->
            <td>
                <div class="text-right flex flex-col text-[1rem] font-semibold">
                    <span class="text-[1.5rem] text-cyan-800 font-bold mb-3">ProPharma</span>
                    <span class="">29-Nov-24</span>
                    <span class="">0420241129</span>
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
            <td>A[Agreed Rate]</td>
            <td></td>
            <td>In Euro</td>
        </tr>
        <tr>
            <td>05-11-2024</td>
            <td>OI 83436</td>
            <td>DHL - Need to provide the console of the device<br><i class="text-blue-600">*Rate calculated for 2 engineers</i></td>
            <td>€ 140.00</td>
            <td>80</td>
            <td>2</td>
            <td>70</td>
            <td></td>
            <td>€ 360.00</td>
        </tr>
        <tr>
            <td>09-11-2024</td>
            <td>OI 83485</td>
            <td>OI 83485 - Colt - OPS 30509425, 30509434 - Heidelberg - Site ID 0762</td>
            <td>€ 52.50</td>
            <td>70</td>
            <td>2</td>
            <td>53</td>
            <td></td>
            <td>€ 227.50</td>
        </tr>
        <tr>
            <td colspan="8" class="total">TOTAL</td>
            <td>€ 587.50</td>
        </tr>
    </table>

    <p class="thank-you">Thank You For Your Business!</p>

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
    <div class=" text-center mb-2 no-print">
        <button class="bg-cyan-500 rounded text-white px-5 py-3 mt-3 border-white fw-semibold"
            onclick="window.print()">Print</button>
    </div>
</body>

</html>