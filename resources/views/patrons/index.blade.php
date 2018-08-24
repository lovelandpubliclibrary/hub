@extends ('layouts.app')

@section ('content')
	<div id="patrons">

		@include('layouts.breadcrumbs')

		<div class="panel panel-default">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Patrons
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="col-xs-12">
					<a href="/patrons/create" class="btn btn-danger col-xs-12 col-sm-3 repository-margin-bottom-1rem">
						Add a New Patron
					</a>

					{{ Form::open(['action' => 'PatronController@search', 'class' => 'form repository-margin-bottom-1rem']) }}
						<div class="input-group col-xs-12 col-sm-8 pull-right-sm repository-margin-bottom-1rem">
							@if (isset($search) && !empty($search))
								<span class="input-group-btn">
									<a class="btn btn-info" href="{{ route('patrons') }}">Clear Search</a>
								</span>
							@endif
							{{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search...', 'required' => 'required', 'autofocus' => 'autofocus']) }}
							<span class="input-group-btn">
								{{ Form::button('<span class=\'glyphicon glyphicon-search\'></span>', ['class' => 'btn btn-default', 'type' => 'submit'] )}}
							</span>
						</div>
					{{ Form::close() }}
				</div>

				@if(count($patrons))
					<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<th class="text-nowrap hidden-xs">
									Patron ID
								</th>

								<th class="text-nowrap">
									Name
								</th>

								<th>
									Description
								</th>

								<th class="hidden-xs">
									Incident Count
								</th>

								<th class="hidden-xs">
									Photos
								</th>
							</tr>
						</thead>

						<tbody>
							@foreach ($patrons as $patron)
								<tr>
									<td class="hub-center hidden-xs">
										{{ $patron->id }}
									</td>

									<td>
										<a href="{{ route('patron', ['patron' => $patron->id]) }}">
											{{ $patron->get_name('list') }}
										</a>
									</td>

									<td>
										{{ $patron->description }}
									</td>

									<td class="hub-center hidden-xs">
										{{ $patron->incident->count() }}
									</td>

									<td class="hidden-xs">
										@if ($patron->photo->isNotEmpty())
											{{-- <div class="row"> --}}
												@foreach ($patron->photo as $photo)
													<div class="col-md-6 col-sm-12">
														<div class="thumbnail">
															<a href="{{ route('photo', ['photo' => $photo->id]) }}">
																<img src="{{ asset('storage/photos/' . $photo->filename) }}" 
																alt="Photo of {{ $patron->get_name('full') }}">
															</a>
														</div>
													</div>
												@endforeach
											{{-- </div> --}}
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