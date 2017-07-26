@extends('layouts.app')

@section('content')
	@if(Session::has('success_message'))
		<div class="alert alert-success">
			{{ Session::get('success_message') }}
		</div>
	@endif
	<div class="text-muted repository-margin-bottom-1rem">
		<a href='/incidents'>
			<< Back to Incidents
		</a>
	</div>

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

	<div class="panel panel-default">
		<div class="panel-heading text-center">
			<h2 class="panel-title">
				{{ $incident->title }}
			</h2>

			{{-- Display the button to edit the incident if the user authored it or is an admin --}}
			@if (Auth::user()->id == $incident->user_id || Auth::user()->role->contains('role', 'Admin'))
				<a class="btn btn-default pull-right repostory-save-button" href="/incidents/edit/{{ $incident->id }}" title="Edit Incident">
					<span class="glyphicon glyphicon-edit"></span>
				</a>
			@endif
		</div>

		<div class="panel-body">
			<div>
				@isset($incident->patron_photo)
					<img class="img-responsive rounded incident-patron-picture" src="{{ asset('images/patrons/' . $incident->patron_photo) }}" alt="Patron Picture">
				@endisset
			</div>

			<div>
				<strong>Date of Incident:</strong>
				{{ $incident->date }}
			</div>

			<div>
				<strong>Patron Name:</strong>
				@isset($incident->patron_name)
					{{ $incident->patron_name }}
				@else
					<span class="repository-text-italic">Unknown</span>
				@endisset
			</div>

			@isset($incident->card_number)
				<div>
					<strong>Library Card Number:</strong>
					{{ $incident->card_number }}
				</div>
			@endisset

			@isset($incident->patron_description)
				<div>
					<strong>Patron Description:</strong>
					{{ $incident->patron_description }}
				</div>
			@endisset

			<div>
				<strong>Reported by:</strong>
				{{ $incident->user->name }}
				on
				{{ $incident->created_at }}
			</div>

			<div class="repository-margin-top-1rem">
				<strong>Description of Incident:</strong>
			</div>
			<blockquote class="blockquote bg-faded text-muted">
				{{ $incident->description }}
				<footer class="blockquote-footer text-right">
					<span class="glyphicon glyphicon-user"></span> {{ $incident->user->name }}
				</footer>
			</blockquote>

			
		</div><!-- .panel-body -->
	</div><!-- .panel -->
@endsection