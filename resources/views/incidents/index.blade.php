@extends('layouts.app')

@section('content')
	<div class="text-muted kb-margin-bottom-1rem">
		<a href='/'>
			<< Back to Home
		</a>
	</div>

	<div class="kb-margin-bottom-1rem">
		<a href="/incidents/create" class="btn btn-primary">
			Report a New Incident
		</a>
	</div>
	@if(count($incidents))
		<ul>
		@foreach($incidents as $incident)
			<li>
				{{ $incident->date }} 
				<a href="/incidents/{{ $incident->id }}">
					{{ $incident->title }}
				</a>
				@if ($incident->patron_name)
					({{ $incident->patron_name }})
				@endif

				<div class="incident-index-description">
					@if(strlen($incident->description) > 35)
						{{ substr($incident->description, 0, strpos(wordwrap($incident->description, 25), '\n')) }}
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