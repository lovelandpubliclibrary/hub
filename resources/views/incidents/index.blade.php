@extends ('layouts.app')

@section ('content')
	<div id="incidents">

		@include('layouts.breadcrumbs')

		<div class="panel panel-default">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Incidents
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div>
					<a href="/incidents/create" class="btn btn-default col-xs-12 col-sm-3 repository-margin-bottom-1rem">
						Report a New Incident
					</a>

					{{ Form::open(['action' => 'IncidentController@search', 'class' => 'form repository-margin-bottom-1rem']) }}
						<div class="input-group col-xs-12 col-sm-8 pull-right-sm repository-margin-bottom-1rem">
							@if (isset($search) && !empty($search))
								<span class="input-group-btn">
									<a class="btn btn-info" href="{{ route('incidents') }}">Clear Search</a>
								</span>
							@endif
							{{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search...', 'required' => 'required', 'autofocus' => 'autofocus']) }}
							<span class="input-group-btn">
								{{ Form::button('<span class=\'glyphicon glyphicon-search\'></span>', ['class' => 'btn btn-default', 'type' => 'submit'] )}}
							</span>
						</div>
					{{ Form::close() }}
				</div>

				@if(count($incidents))
					<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<th>
									Date
								</th>

								<th class="hidden-xs text-nowrap">
									Patron Name
								</th>

								<th>
									Title
								</th>

								<th>
									Summary
								</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($incidents as $incident)
								<tr>
									<td class="text-nowrap">
										<span class="hidden-xs">
											{{ \Carbon\Carbon::parse($incident->date)->toFormattedDateString() }}
										</span>
										<span class="visible-xs-block">
											{{ \Carbon\Carbon::parse($incident->date)->toDateString() }}
										</span>
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
										<div>
											@if (count($incident->photo))
												<span class="glyphicon glyphicon-picture" title="has photo of patron"></span>
											@endif

											@if ($incident->card_number)
												<span class="glyphicon glyphicon-barcode" title="has library card of patron"></span>
											@endif

											@if (count($incident->comment))
												<span class="glyphicon glyphicon-comment" title="has comments by staff"></span>
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
						</tbody>
					</table>
					
				@else
					@if (!empty($search))
						There are no incidents which match your search parameters.
						<div class="text-center">
							<a href="{{ route('incidents') }}">Clear Search</a>
						</div>
					@else
						There are no incidents to display.
					@endif
				@endif
			</div> <!-- .panel-body -->
		</div> <!-- .panel -->
	</div> <!-- #incidents -->
@endsection