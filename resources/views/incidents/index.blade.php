@extends('layouts.app')

@section('content')
	<div class="content">
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
						@if ($incident->patron_name)
							({{ $incident->patron_name }})
						@endif
					</a>
					<div class="incident-index-description">
						{{ $incident->description }}
					</div>
				</li>
			@endforeach
		</ul>
	</div>
@endsection