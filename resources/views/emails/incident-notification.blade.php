@component('mail::message')
	# New Incident

	A new incident as been posted to the Repository by {{ $incident->user->name }}.

	@component('mail::button', ['url' => url('incidents', [$incident->id])])
		View Incident
	@endcomponent

	Thanks,<br>
	{{ config('app.name') }}


@endcomponent
