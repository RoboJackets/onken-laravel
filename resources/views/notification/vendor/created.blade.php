@component('mail::message')

Hello,

The vendor {{ $name }} was just created.

@component('mail::button', ['url' => $url, 'color' => 'blue'])
View Vendor
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
