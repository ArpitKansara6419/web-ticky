@foreach ($finalMonthlyCustomerPayable as $engineerPaySum)
<span class="text-[1rem] text-nowrap dark:text-gray-300">{{$engineerPaySum['symbol']}} {{$engineerPaySum['total_payable'] ?? 0 }}</span>
@endforeach