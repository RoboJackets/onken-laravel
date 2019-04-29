@component('mail::message')

Hello,

{{ $name }} ({{ $vendor }}, {{ $cost }}) was marked approved.

@component('mail::button', ['url' => $url, 'color' => 'blue'])
View Requisition
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
