@extends ('layouts.app')

@section ('content')
	<div class="text-muted repository-margin-bottom-1rem">
		<a href='/'>
			<< Back to Home
		</a>
	</div>

		<a href="/incidents/create" class="btn btn-default col-xs-12 repository-margin-bottom-1rem">
			Report a New Incident
		</a>

		{{ Form::open(['action' => 'IncidentController@search', 'class' => 'form repository-margin-bottom-1rem']) }}
			<div class="input-group col-xs-12">
				{{ Form::label('search', 'Search: ', ['class' => 'sr-only']) }}
				{{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search...', 'required' => 'required']) }}
				<span class="input-group-btn">
					{{ Form::button('<span class=\'glyphicon glyphicon-search\'></span>', ['class' => 'btn btn-default', 'type' => 'submit'] )}}
				</span>
			</div>
		{{ Form::close() }}

	@if (isset($search) && !empty($search))
		<p>
			Showing search results for <strong>{{ $search }}</strong>:
			<a class="pull-right" href="/incidents">Clear Search</a>
		</p>
	@endif

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
					<td class="text-nowrap">
						{{ \Carbon\Carbon::parse($incident->date)->toFormattedDateString() }}
					</td>

					<td class="hidden-xs text-nowrap">
						@if ($incident->patron_name)
							{{ $incident->patron_name }}
						@endif
					</td>

					<td>
						<a href="/incidents/{{ $incident->id }}">
							{{ $incident->title }}
						</a>

						{{-- display icons based on the properties of the incidents --}}
						<div class="text-nowrap">
							@if (count($incident->photo))
								<span class="glyphicon glyphicon-picture"></span>
							@endif

							@if ($incident->card_number)
								<span class="glyphicon glyphicon-barcode"></span>
							@endif

							@if (count($incident->comment))
								<span class="glyphicon glyphicon-comment"></span>
							@endif
						</div>
					</td>

					<td>
						@if (strlen($incident->description) > 75)
							{{ $incident->truncate_description(75) }}...
						@else
							{{ $incident->description }}
						@endif
					</td>
				</tr>
			@endforeach
		</table>
	@else
		@if (!empty($search))
			There are no incidents which match your search parameters.
		@else
			There are no incidents to display.
		@endif
	@endif
@endsection