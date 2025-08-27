<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .invoice-footer {
            text-align: center;
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-top: 2px solid #ddd;
        }

        th {
            background-color: #f8f9fa !important;
            color: black;
            text-align: center;
            vertical-align: middle;
            font-size: .7rem !important;
        }

        td {
            text-align: center;
            vertical-align: middle;
            font-size: .7rem !important;
        }

        table {
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="">
            <!-- Invoice Header -->
            <div class="row p-3">
                <div class="col-3 text-center">
                    <div class="d-flex flex-column justify-content-center align-items-center">
                        <img src="/assets/logo.png" style="width: 70px; height:70px" alt="">
                        <strong class="text-primary" style="margin-top: 10px; font-size: 10px;">AIMBOT BUSINESS SERVICES</strong>
                    </div>
                </div>
                <div class="col-6 justify-content-center d-flex gap-1 flex-column" style="font-size: .8rem;">
                    <div>WhatsApp Link: <a href="">https://wa.me/message/TEZRFEC</a></div>
                    <div>Linkedin Link: <a href="">https://linkedin.com/company/aimbizits</a></div>
                    <div>Website Link: <a href="">https://aimbizits.com</a></div>
                </div>
                <div class="col-3   d-flex flex-column justify-content-center align-items-end">
                    <div class="border text-center " style="width: 7rem; font-size: .85rem;">20-Nov-24</div>
                    <div class="border text-center" style="width: 7rem;font-size: .85rem">0245005643</div>
                </div>
            </div>

            <!-- Invoice Body -->
            <div class="invoice-body">
                <div class="row ">
                    <div class="col-8">
                        <table class="table table-bordered">
                            <strong class="mb-2 text-primary">Bank Details for Payment</strong>
                            <tbody>
                                <tr>
                                    <th>Bank Name *</th>
                                    <td>ING Bank</td>
                                </tr>
                                <tr>
                                    <th>Bank Address *</th>
                                    <td>Śląski SA ul. Sokolska 34, 40-086 Katowice</td>
                                </tr>
                                <tr>
                                    <th>Account Holder Name *</th>
                                    <td>AIMBOT BUSINESS SERVICES</td>
                                </tr>
                                <tr>
                                    <th>Account Number *</th>
                                    <td>93 1050 1012 1000 0090 3264 5138</td>
                                </tr>
                                <tr>
                                    <th>IBAN *</th>
                                    <td>PL 93 1050 1012 1000 0090 3264 5138</td>
                                </tr>
                                <tr>
                                    <th>BIC / Swift Code *</th>
                                    <td>INGBPLPW</td>
                                </tr>
                                <tr>
                                    <th>Sort Code (UK ONLY)</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Country *</th>
                                    <td>Poland</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
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
                        <tr>
                            <th>[Date]</th>
                            <th>[Ticket #]</th>
                            <th>[Brief Description of Task]</th>
                            <th>[Agreed Rate]</th>
                            <th>[Agreed Rate]</th>
                            <th>[Agreed Rate]</th>
                            <th>[Agreed Rate]</th>
                            <th></th>
                            <th>In Euro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ticket_works as $ticket_work)
                        <tr>
                            <td>05-11-2024</td>
                            <td>{{$ticket_work['ticket_id']}}</td>
                            <td>
                                DHL - Need to provide the console of the device<br>
                                <span style="color: blue;">*Rate calculated for 2 engineers</span>
                            </td>
                            <td>€ 140.00</td>
                            <td>{{$ticket_work['travel_cost']}}</td>
                            <td>{{$ticket_work['total_work_time']}}</td>
                            <td>{{$ticket_work['hourly_rate']}}</td>
                            <td>{{$ticket_work['tool_cost']}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="col-md-12 p-2 text-end">
                    <span class="fw-bold">Total: € 4560.00</span>
                </div>

            </div>

            <!-- Invoice Footer -->
            <div class="invoice-footer">
                <p>Thank you for your business!</p>
                <button onclick="window.print()" class="btn btn-primary no-print">Print Invoice</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>