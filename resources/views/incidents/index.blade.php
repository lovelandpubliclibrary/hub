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
				<div class="col-xs-12">
					<a href="/incidents/create" class="btn btn-danger col-xs-12 col-sm-3 repository-margin-bottom-1rem">
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
								</th>

								<th>
									Date
								</th>

								<th class="hidden-xs text-nowrap">
									Patron Names
								</th>

								<th>
									Title
								</th>

								<th class="hidden-xs">
									Summary
								</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($incidents as $incident)
								<tr>
									<td>
										@if (!$user_viewed->contains($incident))
											<span class="label 
												{{ \Carbon\Carbon::createFromFormat('Y-m-d', $incident->date) >= Auth::user()->created_at->subMonth() ?
													'label-danger' : 'label-info' }}
												">New
											</span>
										@endif
									</td>

									<td class="text-nowrap">
										<span class="hidden-xs">
											{{ \Carbon\Carbon::parse($incident->date)->toFormattedDateString() }}
										</span>
										<span class="visible-xs-block">
											{{ \Carbon\Carbon::parse($incident->date)->toDateString() }}
										</span>
									</td>

									<td class="hidden-xs text-nowrap">
										@if ($incident->patron)
											<ul>
												@foreach ($incident->patron as $patron)
													<li>
														{{ $patron->get_list_name() }}
													</li>
												@endforeach
											</ul>
										@endif
									</td>

									<td>
										<a href="/incidents/{{ $incident->id }}">
											{{ $incident->title }}
										</a>

										{{-- display icons based on the properties of the incidents --}}
										<div>
											@if (count($incident->photo))
												<span class="glyphicon glyphicon-picture" title="has photo(s)"></span>
											@endif

											@if (count($incident->comment))
												<span class="glyphicon glyphicon-comment" title="has comments by staff"></span>
											@endif

											@if ($incident->user_id == Auth::user()->id)
												<span class="glyphicon glyphicon-pencil" title="can edit"></span>
											@endif
										</div>
									</td>

									<td class="hidden-xs">
										@if ($incident->user_id == Auth::user()->id)
											<div class="hub-float-right">
												<a class="btn btn-default" href="/incidents/edit/{{ $incident->id }}" role="button">
													Edit
												</a>
											</div>
										@endif

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