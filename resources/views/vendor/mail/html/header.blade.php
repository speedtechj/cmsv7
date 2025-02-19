@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Forexcargodeals.com')
<img src="https://cmsv4calgary.forexcargodeals.com//storage/logo/logo.png" class="logo" alt="Laravel Logo" >
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
