@foreach ($finalEngineerGrossPay as $currencyTotal)
<span class="text-[1rem] text-nowrap dark:text-gray-300">{{$currencyTotal['symbol']}} {{$currencyTotal['total_amount']}}</span>
@endforeach