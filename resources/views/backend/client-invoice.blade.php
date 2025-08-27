<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-bottom: 1.5rem;
        }

        .card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #c8ced3;
            border-radius: .25rem;
        }

        .card-header:first-child {
            border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
        }

        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            background-color: #f0f3f5;
            border-bottom: 1px solid #c8ced3;
        }

        @media print {
            body {
                font-size: 12px;
                font-family: Arial, sans-serif;
                color: #000;
            }

            .row {
                display: flex;
                flex-wrap: wrap;
                margin-bottom: 20px;
            }

            .col-sm-2,
            .col-sm-3,
            .col-sm-4 {
                flex: 1;
                min-width: 150px;
                max-width: 250px;
                padding: 10px;
            }

            /* Borders and spacing for better visibility */
            .row {
                border: 1px solid #ddd;
                padding: 10px;
            }

            /* Logo and text alignment */
            img {
                display: block;
                margin: 0 auto;
            }

            strong {
                font-weight: bold;
                color: #333;
            }

            /* Remove unnecessary margins */
            h6 {
                margin-bottom: 8px;
                font-size: 14px;
                font-weight: bold;
                color: #555;
            }

            /* Links color */
            a {
                text-decoration: none;
                color: #007bff;
            }

            /* Avoid page breaking issues */
            .row {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-2">
        <div id="ui-view" data-select2-id="ui-view">
            <div>
                <div class="card">
                    <div class="card-header">Invoice
                        <strong>#BBB-10010110101938</strong>
                        <a class="btn btn-sm btn-secondary float-right mr-1 d-print-none" href="#" onclick="javascript:window.print();" data-abc="true">
                            <i class="fa fa-print"></i> Print</a>
                        <a class="btn btn-sm btn-info float-right mr-1 d-print-none" href="#" data-abc="true">
                            <i class="fa fa-save"></i> Save</a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4" style="border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
                            <!-- Logo Section -->
                            <div class="col-sm-2 text-center d-flex flex-column align-items-center justify-content-center">
                                <img src="/assets/ticky-logo.png" style="width: 100px; height: 100px;" alt="AIMBOT Logo">
                                <strong class="text-primary" style="margin-top: 10px; font-size: 14px;">AIMBOT BUSINESS SERVICES</strong>
                            </div>

                            <!-- From Section -->
                            <div class="col-sm-3">
                                <h6 class="mb-3">From:</h6>
                                <div>
                                    <strong>AIMBOT BUSINESS SERVICES</strong>
                                </div>
                                <div>42, Awesome Enclave</div>
                                <div>New York City, New York, 10394</div>
                                <div>Email: <a href="mailto:aimbotbusinessservices@gmail.com">aimbotbusinessservices@gmail.com</a></div>
                                <div>Phone: +91 7201 456 245</div>
                                <div>
                                    <strong>KRS Code: 99 8888 7777 6666 5555</strong>
                                </div>
                            </div>

                            <!-- To Section -->
                            <div class="col-sm-3">
                                <h6 class="mb-3">To:</h6>
                                <div>
                                    <strong>Mr. John Doe</strong>
                                </div>
                                <div>42, Awesome Enclave</div>
                                <div>New York City, New York, 10394</div>
                                <div>Email: <a href="mailto:johndoe@gmail.com">johndoe@gmail.com</a></div>
                                <div>Phone: +91 8346 456 538</div>
                            </div>

                            <!-- Details Section -->
                            <div class="col-sm-4">
                                <h6 class="mb-3">Details:</h6>
                                <div>Invoice: <strong>#BBB-10010110101938</strong></div>
                                <div>Date: April 30, 2019</div>
                                <div>VAT: NYC09090390</div>
                                <div>Account Name: AIMBOT BUSINESS SERVICES</div>
                                <div>Bank Name: SBI</div>
                                <div>SWIFT Code: <strong>99 8888 7777 6666 5555</strong></div>
                                <div>IBAN Code: <strong>99 8888 7777 6666 5555</strong></div>
                            </div>
                        </div>


                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Description of Serivce</th>
                                        <th class="right">Location</th>
                                        <th class="right">Amount in Euro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="center">1</td>
                                        <td class="left">Apple iphoe 10 with extended warranty</td>
                                        <td class="right">Polan</td>
                                        <td class="right">EUR 999.00</td>
                                    </tr>
                                    <tr>
                                        <td class="center">2</td>
                                        <td class="left">Samsung S6 with extended warranty</td>
                                        <td class="right">Poland</td>
                                        <td class="right">EUR 300.00</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-5">
                                <div>Service Duration : April 1, 2024 - April 30, 2024</div>
                                <div>Term of Payment: Cash</div>
                                <div>Date of Payment : April 30, 2024</div>
                            </div>
                            <div class="col-lg-4 col-sm-5 ml-auto">
                                <table class="table table-clear">
                                    <tbody>
                                        <tr>
                                            <td class="left">
                                                <strong>Subtotal</strong>
                                            </td>
                                            <td class="right">$8.497,00</td>
                                        </tr>
                                        <tr>
                                            <td class="left">
                                                <strong>Discount (20%)</strong>
                                            </td>
                                            <td class="right">$1,699,40</td>
                                        </tr>
                                        <tr>
                                            <td class="left">
                                                <strong>VAT (10%)</strong>
                                            </td>
                                            <td class="right">$679,76</td>
                                        </tr>
                                        <tr>
                                            <td class="left">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="right">
                                                <strong>$7.477,36</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>