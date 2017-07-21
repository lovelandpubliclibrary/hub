@extends ('layouts.app')

@section ('content')
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

	<div class="container">
		{{ Form::open(['action' => 'IncidentController@search', 'class' => 'form row kb-margin-bottom-1rem']) }}
			<div class="input-group col-xs-12 col-md-6">
				{{ Form::label('search', 'Search: ', ['class' => 'sr-only']) }}
				{{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search...'])}}
				<span class="input-group-btn">
					{{ Form::button('<span class=\'glyphicon glyphicon-search\'></span>', ['class' => 'btn btn-default', 'type' => 'submit'] )}}
				</span>
			</div>
		{{ Form::close() }}
	</div>

	@if(count($incidents))
		<table class="table table-striped table-condensed">
			<tr>
				<th>
					Date
				</th>

				<th class="hidden-xs">
					Patron Name
				</th>

				<th>
					Title
				</th>

				<th>
					Summary
				</th>
			</tr>

			@foreach ($incidents as $incident)
				<tr>
					<td>
						{{ \Carbon\Carbon::parse($incident->date)->toFormattedDateString() }}
					</td>

					<td class="hidden-xs">
						@if ($incident->patron_name)
							{{ $incident->patron_name }}
						@endif
					</td>

					<td>
						<a href="/incidents/{{ $incident->id }}">
							{{ $incident->title }}
						</a>

						@if ($incident->patron_photo)
							&nbsp;<span class="glyphicon glyphicon-picture"></span>
						@endif
					</td>

					<td>
						@if (strlen($incident->description) > 40)
							{{ $incident->truncate_description(40) }}...
						@else
							{{ $incident->description }}
						@endif
					</td>
				</tr>
			@endforeach
		</table>
	@else
				There are no incidents to display.
	@endif
@endsection