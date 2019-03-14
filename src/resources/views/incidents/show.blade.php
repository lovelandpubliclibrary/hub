@extends('layouts.app')

@section('content')
	<div id="incidents">
		@include('layouts.breadcrumbs')

		@if(Session::has('success_message'))
			<div class="alert alert-success">
				{{ Session::get('success_message') }}
			</div>
		@endif

		@if (count($errors) > 0)
			<div class="alert alert-danger">
				The following errors occurred:
				<ul>
					@foreach ($errors as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<div class="panel panel-default" id="incident">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-12 repository-margin-bottom-1rem">
					<h2 class="panel-title">
						{{ $incident->title }}
					</h2>
				</div>

				{{-- Display the button to edit the incident if the user authored it or is an admin --}}
				@if (Auth::id() == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
					<a class="btn-sm btn-default link-default" href="/incidents/edit/{{ $incident->id }}" title="Edit Incident">
						<span class="glyphicon glyphicon-edit"></span> Edit Incident
					</a>
				@endif
			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="row">
					@isset($photos)
						@foreach ($photos as $photo)
							<a href="{{ route('photo', ['photo' => $photo->id]) }}" class="col-xs-12 col-sm-6 col-md-4">
								<img class="img-responsive thumbnail" src="{{ asset('storage/photos/' . $photo->filename) }}" alt="Patron Picture">
							</a>
						@endforeach
					@endisset
				</div><!-- .row -->

				<div class="row">
					<div class="col-xs-12">
						<strong>Date of Incident:</strong>
						{{ $incident->date }}
					</div>

					<div class="col-xs-12">
						<strong>Time of Incident:</strong>
						{{ \Carbon\Carbon::parse($incident->time)->format('g:i A') }}
					</div>

					<div class="col-xs-12">
						<strong>Location(s) of Incident:</strong>
						@if (count($locations = $incident->location))
							@foreach ($locations as $location)
								{{-- add a comma after every location except the last one --}}
								{{ $locations->last() != $location ? $location->location . ', ' : $location->location }}
							@endforeach
						@else
							<em>Unknown</em>
						@endif
					</div>

					<div class="col-xs-12">

						<strong>Other staff members involved:</strong>

						<ul class="list-group">

							@if (count($staff = $incident->usersInvolved))

								@foreach ($staff as $user)

									<li class="list-group-item">
										{{-- add a comma after every location except the last one --}}
										{{ $staff->last() == $user ? $user->name : $user->name . ', ' }}
									</li>

								@endforeach

							@else

								<li class="list-group-item">
									None
								</li>

							@endif
						</ul>
					</div>
					

					<div class="col-xs-12">

						<strong>Patrons Involved:</strong>

						<ul class="list-group">

							@if (count($patrons = $incident->patron))
								
								@foreach ($patrons as $patron)
									
									<li class="list-group-item">

										<a href="{{ route('patron', ['patron' => $patron->id]) }}">
											{{ $patron->get_name('list') }}
										</a>
									
									</li>

								@endforeach

							@else
								
								<li class="list-group-item">
									None
								</li>
								
							@endif

						</ul>
					</div>

					@isset ($incident->card_number)
						<div class="col-xs-12">
							<strong>Library Card Number:</strong>
							{{ $incident->card_number }}
						</div>
					@endisset

					<div class="col-xs-12">
						<strong>Reported by:</strong>
						{{ $incident->user->name }}
						on
						{{ $incident->created_at->toDayDateTimeString() }}
					</div>

					<div class="col-xs-12">
						<strong>Description:</strong>
						<blockquote class="blockquote bg-faded text-muted">
							{{ $incident->description }}
							<footer class="blockquote-footer text-right">
								<span class="glyphicon glyphicon-user"></span> {{ $incident->user->name }}
							</footer>
						</blockquote>
					</div>
				</div><!-- .row -->

				@if (Auth::user()->isSupervisor() && !empty($unviewed_by))
					<div class="col-xs-12 bg-warning not-viewed">
						<ul class="list-group">
							<strong>Not yet viewed by</strong>
							<span class="caret"></span>
							@foreach ($unviewed_by as $user)
								{{-- inline style provided to assist w/ $(this).toggle(); --}}
								<li class="list-group-item" style="display:none;">
									{{ $user->name }}
								</li>
							@endforeach
						</ul>
					</div>
				@endif
			</div><!-- .panel-body -->

			<div class="panel-footer">
				@if (isset($comments) && count($comments) > 0)
					@include ('comments.index')
				@endif
				<div>
					<h3 id="comment">Comment on this Incident:</h3>
					@include ('comments.create')
				</div>
			</div>
		</div><!-- .panel -->
	</div> <!-- #incidents -->
	
@endsection