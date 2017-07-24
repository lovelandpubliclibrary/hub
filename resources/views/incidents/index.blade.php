@extends('layouts.app')

@section('content')
	<div class="text-muted repository-margin-bottom-1rem">
		<a href='/'>
			<< Back to Home
		</a>
	</div>

	<div class="repository-margin-bottom-1rem">
		<a href="/incidents/create" class="btn btn-primary">
			Report a New Incident
		</a>
	</div>
	@if(count($incidents))
		<ul>
		@foreach($incidents as $incident)
			<li>
				{{ $incident->date }} 

				@if ($incident->patron_name)
					({{ $incident->patron_name }})
				@endif

				<a href="/incidents/{{ $incident->id }}">
					{{ $incident->title }}
				</a>

				<div class="incident-index-description">
					@if(strlen($incident->description) > 40)
						{{ $incident->truncate_description(40) }}...
					@else
						{{ $incident->description }}
					@endif
				</div>
			</li>
		@endforeach
		</ul>
	@else
		There are no incidents to display.
	@endif
@endsection