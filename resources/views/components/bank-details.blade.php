@if(empty($bank_details))
<h2 class="font-bold bg-gray-200 p-2">Account Details</h2>
<p class="ml-2"><strong>Account Name:</strong> AIMBOT BUSINESS SERVICES</p>
<p class="ml-2"><strong>Bank Name:</strong> ING Bank</p>
<p class="ml-2"><strong>Swift:</strong> INGBPLPW</p>
<p class="ml-2"><strong>IBAN:</strong> PL 93 1050 1012 1000 0090 3264 5138</p>
@else
<h2 class="font-bold bg-gray-200 p-2">Account Details</h2>
<p class="ml-2"><strong>Account Name:</strong> {{ $bank_details['account_holder_name'] }}</p>
<p class="ml-2"><strong>Bank Name:</strong> {{ $bank_details['bank_name'] }}</p>
<p class="ml-2"><strong>Swift:</strong> {{ $bank_details['swift_code'] }}</p>
<p class="ml-2"><strong>IBAN:</strong> {{ $bank_details['iban'] }}</p>
@endif
