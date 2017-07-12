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

		<table class="table table-striped table-condensed">
			<tr>
				<th>
					Date
				</th>

				<th>
					Patron Name
				</th>

				<th>
					Title
				</th>

				<th>
					Picture
				</th>

				<th>
					Summary
				</th>
			</tr>
		</table>

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

				@if ($incident->patron_photo)
					<span class="glyphicon glyphicon-paperclip"> </span>
				@endif

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