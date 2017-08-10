@extends('layouts.app')

@section('content')
	@include('layouts.breadcrumbs')

	@if(Session::has('success_message'))
		<div class="alert alert-success">
			{{ Session::get('success_message') }}
		</div>
	@endif

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="panel panel-default">
		<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
			<div class="center-block">
				<h2 class="panel-title">
					Photo of {{ ($photo->incident->patron_name ?: 'Unknown Patron') }}
				</h2>

				<div>
					from 
					<a href="{{ route('incident', ['incident' => $photo->incident->id]) }}">
						{{ $photo->incident->title }}
					</a>
					on {{ $photo->incident->date }}
				</div>
			</div>

			{{-- Display the button to edit the incident if the user authored it or is an admin --}}
			@if (Auth::id() == $photo->incident->user_id || Auth::user()->role->contains('role', 'Admin'))
				<a class="btn-sm btn-default pull-right link-default" href="/photos/edit/{{ $photo->id }}" title="Edit Incident">
					<span class="glyphicon glyphicon-edit"></span> Edit
				</a>
			@endif
		</div><!-- .panel-heading -->

		<div class="panel-body">
			<img class="img-responsive center-block" src="{{ asset('images/patrons/' . $photo->filename) }}" alt="Patron Picture">
			<div class="well well-sm text-center">
				{{ $photo->caption }}
			</div>
		</div><!-- .panel-body -->

	</div><!-- .panel -->
@endsection