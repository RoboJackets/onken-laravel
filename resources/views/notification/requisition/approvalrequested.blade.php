@component('mail::message')

Hello,

{{ $requester_name }} requested your approval of {{ $requisition_name }}.

@component('mail::button', ['url' => $requisition_url, 'color' => 'blue'])
View Requisition
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
