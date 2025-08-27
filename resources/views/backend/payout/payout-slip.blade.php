<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-2 mb-5">
        <div class="row">
            <div class="col-12 text-center mb-2 no-print">
                <button class="bg-primary rounded text-white p-1 border-white  fw-semibold"
                    onclick="window.print()">Print</button>
            </div>

            <div class="row">
                    <div class="col-4">
                        <img src="/assets/ticky-logo.png" style="width: 100px; height:100px" alt="">
                    </div>
                    <div class="text-center  lh-1 mb-2 col-4  d-flex align-items-end justify-content-center" style="height:100%">
                        <h6 class="fw-bold fs-2 ">Payout Slip</h6>
                        {{-- <span class="fw-normal">Work  slip for the month of {{$corruent_month}} </span> --}}
                    </div>
                    {{-- <div class="col-4 mt-auto"> --}}
                        {{-- <div class="text-end " style=" font-weight: 600"> <span class="fw-bold"> Engineer Code: </span> --}}
                            {{-- {{$engineer['engineer_code']}} --}}
                        {{-- </div> --}}
                        {{-- <div class="text-end " style=" font-weight: 600"> <span class="fw-bold"> Engineer Name: </span> --}}
                            {{-- {{ $engineer['first_name'] . ' ' . $engineer['last_name'] }} --}}
                        {{-- </div> --}}
                    {{-- </div> --}}

            </div>


            <div class="col-md-12">
                <div class="row">
                    <div class="row">
                        <table class="mt-2 table table-bordered">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Gross Rate/Hour</th>
                                    <th scope="col">Total Time</th>
                                    <th scope="col">Charges</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $gross_total = 0
                                @endphp
                                @foreach ($ticket_works as $ticket_work)
                                    @php
                                     $gross_total = $gross_total + $ticket_work['hourly_payable'];
                                    @endphp
                                    <tr>
                                        <td>{{ $ticket_work['id'] }}</td>
                                        <td>{{ $ticket_work['work_start_date'] }}</td>
                                        <td>This is test description</td>
                                        <td>{{ $ticket_work['hourly_rate'] }}</td>
                                        <td>{{ $ticket_work['total_work_time'] }}</td>
                                        <td>{{ $ticket_work['hourly_payable'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                            <div class="fw-bold ">Grand Total: {{$gross_total}}</div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
