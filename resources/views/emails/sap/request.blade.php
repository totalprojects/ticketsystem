@component('mail::message')
<h2><u>SAP Request</u></h2>
@if($data['type'] == 'requested_by')
<p>Hi {{ $data['name'] }},</p>
<p>There has been a request placed by you with request id as {{ $data['request_id'] }} for sap module(s) {{ $data['modules'] }}</p>
<p><strong>Kindly wait for the approval from your reporting manager</strong></p>
@elseif($data['type'] == 'reporting_manager')
<p>Hi {{ $data['name'] }},</p>
<p>There has been a request placed by your team member {{ $data['for'] }} with request id as {{ $data['request_id'] }} for sap module(s) {{ $data['modules'] }}</p>
<p><strong>Kindly visit IRMS Portal to Approve/Reject the request made.</strong></p>

@else
<p>Hi {{ $data['name'] }},
</p>
<p>There has been a request placed by {{ $data['for'] }} with request id {{ $data['request_id'] }} </p>
@endif

@component('mail::button', ['url' => 'http://125.22.105.181:33066/audit_compliance/public/app/sap/request'])
Click here to visit IRMS Portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
