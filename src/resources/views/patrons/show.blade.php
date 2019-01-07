@extends('layouts.app')

@section('content')
	<div id="patrons">
		@include('layouts.breadcrumbs')
{{-- {{dd($patron)}} --}}
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

		<div class="panel panel-default" id="patrons">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-12 repository-margin-bottom-1rem">
					<h2 class="panel-title">
						{{ $patron->get_name() }}
					</h2>
				</div>

				{{-- Display the button to edit the patron if the user authored it or is an admin --}}
				@if (Auth::id() == $patron->user_id || Auth::user()->role->contains('role', 'Admin'))
					<div class="text-center-xs text-right-sm repository-margin-top-1rem">
						<a class="btn-sm btn-default link-default" href="/patrons/edit/{{ $patron->id }}" title="Edit Patron">
							<span class="glyphicon glyphicon-edit"></span> Edit Patron
						</a>
					</div>
				@endif

			</div><!-- .panel-heading -->

			<div class="panel-body">
				<div class="row">
					@if(count($photos = $patron->photo))
						@foreach ($photos as $photo)
							<div class="col-xs-12 col-sm-6 col-md-4">
								<a href="{{ route('photo', ['photo' => $photo->id]) }}">
									<img class="img-responsive thumbnail" src="{{ asset('storage/photos/' . $photo->filename) }}" alt="Patron Picture">
								</a>
							</div>
						@endforeach
					@endif
				</div><!-- .row -->

				<div class="row">
					<div class="col-xs-12">
						<strong>Date Added:</strong>
						{{ $patron->created_at }}
					</div>

					<div class="col-xs-12">
						<strong>Added by:</strong>
						{{ $patron->added_by() }}
					</div>

					@isset($patron->card_number)
						<div class="col-xs-12">
							<strong>Library Card Number:</strong>
							{{ $patron->card_number }}
						</div>
					@endisset

					<div class="col-xs-12">

						<strong>Incidents this patron is involved in:</strong>

						<ul class="list-group patrons-involved">

							@if (count($incidents = $patron->incident))
								@foreach ($incidents as $incident)
									 <li class="list-group-item">
									 	<a href="{{ route('incident', ['incident' => $incident->id]) }}">{{ $incident->title }}</a>
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

						<strong>Description:</strong>

						<blockquote class="blockquote bg-faded text-muted">
							
							{{ $patron->description }}
						
						</blockquote>
					
					</div>

				</div><!-- .row -->
			</div><!-- .panel-body -->

			<div class="panel-footer">
				@if (isset($comments) && count($comments))
					@include ('comments.index')
				@endif
				
				<div>
					<h3 id="comment">Comment about this Patron:</h3>
					@include ('comments.create')
				</div>
			</div>
		</div><!-- .panel -->
	</div> <!-- #incidents -->
	
@endsection