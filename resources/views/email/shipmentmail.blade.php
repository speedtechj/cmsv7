<x-mail::message>
<div style="text-align:center;font-weight:bolder;font-size:18px;color:darkblue;text-decoration:underline">
    Invoice:{{$record['booking_invoice']}}</div><br><br>
<p style="text-align: left">{{$data['message']}}</p>

{{-- <x-mail::button :url="''">
Button Text
</x-mail::button> --}}

Thanks,<br>
Forexcargo Customer Service
{{-- {{ config('Forexcargodeals.com') }} --}}
</x-mail::message>
