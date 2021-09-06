@component('mail::message')
SAP Request Email Demo

<p>Hi {{ $data['name'] }},</p>
<p>This is a demo sap mail</p>

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
