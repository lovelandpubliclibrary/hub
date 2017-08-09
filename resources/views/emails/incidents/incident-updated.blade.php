@component('mail::message')
# Incident Updated

An incident you created has been updated by {{ App\User::find($incident->updated_by)->name }}:  
{{ $incident->title }}

@component('mail::button', ['url' => url('incidents', [$incident->id])])
Click here to view the incident
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@component('mail::panel')
Please do not reply to this email; it has been dynamically generated by the LPL Repository 
and the mailbox it was sent from is not monitored.
If you need assistance or have a question, please email kevin.briggs@cityofloveland.org.
@endcomponent

@endcomponent