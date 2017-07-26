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

	<div class="panel panel-warning">
		<div class="panel-heading text-center">
			<h2 class="panel-title">
				{{ $incident->title }}
			</h2>
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
				<footer class="blockquote-footer">
					{{ $incident->user->name }}
				</footer>
			</blockquote>

			
		</div><!-- .panel-body -->
	</div><!-- .panel -->
@endsection