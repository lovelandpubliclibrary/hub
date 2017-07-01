@extends('layouts.app')

@section('content')
	<div style="margin-bottom: 1em">
		<a href="/incidents/create" class="btn btn-primary">
			Report a New Incident
		</a>
	</div>
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
@endsection