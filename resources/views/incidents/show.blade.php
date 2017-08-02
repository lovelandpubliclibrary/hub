@extends('layouts.app')

@section('content')
	<div class="text-muted repository-margin-bottom-1rem">
		<a href='/incidents'>
			<< Back to Incidents
		</a>
	</div>

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
			<div class="col-xs-10 col-xs-offset-1">
				<h2 class="panel-title">
					{{ $incident->title }}
				</h2>
			</div>

			{{-- Display the button to edit the incident if the user authored it or is an admin --}}
			@if (Auth::user()->id == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
				<a class="btn-sm btn-default pull-right link-default" href="/incidents/edit/{{ $incident->id }}" title="Edit Incident">
					<span class="glyphicon glyphicon-edit"></span> Edit
				</a>
			@endif
		</div><!-- .panel-heading -->

		<div class="panel-body">
			<div class="row">

				@isset($photos)
					@foreach ($photos as $photo)
						<a href="{{ route('photo', ['photo' => $photo->id]) }}" class="col-xs-12 col-sm-6 col-md-4">
							<img class="img-responsive thumbnail" src="{{ asset('images/patrons/' . $photo->filename) }}" alt="Patron Picture">
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
					<strong>Patron Name:</strong>
					@isset($incident->patron_name)
						{{ $incident->patron_name }}
					@else
						<span class="repository-text-italic">Unknown</span>
					@endisset
				</div>

				@isset($incident->card_number)
					<div class="col-xs-12">
						<strong>Library Card Number:</strong>
						{{ $incident->card_number }}
					</div>
				@endisset

				@isset($incident->patron_description)
					<div class="col-xs-12">
						<strong>Patron Description:</strong>
						{{ $incident->patron_description }}
					</div>
				@endisset

				<div class="col-xs-12">
					<strong>Reported by:</strong>
					{{ $incident->user->name }}
					on
					{{ $incident->created_at->toDayDateTimeString() }}
				</div>

				<div class="col-xs-12">
					<strong>Description of Incident:</strong>
					<blockquote class="blockquote bg-faded text-muted">
						{{ $incident->description }}
						<footer class="blockquote-footer text-right">
							<span class="glyphicon glyphicon-user"></span> {{ $incident->user->name }}
						</footer>
					</blockquote>
				</div>
			</div><!-- .row -->

			@if (isset($comments) && count($comments) > 0)
				@include ('comments.index')
			@endif

			<div>
				<h3 id="comment">Comment on this Incident:</h3>
				@include ('comments.create')
			</div>


		</div><!-- .panel-body -->
	</div><!-- .panel -->
@endsection