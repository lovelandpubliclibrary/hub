@extends('layouts.app')

@section('content')
	<div class="text-muted">
		<a href='/incidents'>
			<< Back to Incidents
		</a>
	</div>

  <div class="h1 text-center">
    Edit an Incident
  </div>

	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	{{ Form::open(['action' => 'IncidentController@update', 'files' => true]) }}
		<div class="form-group">
		    {{ Form::label('date', 'Date of Incident:') }}
		    {{ Form::date('date', $incident->date, ['class' => 'form-control']) }}
	  	</div>

		<div class="form-group">
			{{ Form::label('patronName', 'Patron Name:') }}
			{{ Form::text('patronName', $incident->patron_name, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('patronCardNumber', 'Patron Library Card Number:') }}
			{{ Form::text('patronCardNumber', $incident->card_number, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('patronDescription', 'Patron Description:') }}
			{{ Form::text('patronDescription', $incident->patron_description, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('title', 'Title:') }}
			{{ Form::text('title', $incident->title, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('description', 'Describe the Incident:') }}
			{{ Form::textarea('description', $incident->description, ['class' => 'form-control', 'rows' => '6']) }}
		</div>

		<div class="form-group">
			{{ Form::label('patronPicture', 'Patron Picture:') }}
			@if ($incident->patron_photo)
				<img class="img-responsive rounded incident-patron-picture"
					 src="{{ asset('images/patrons/' . $incident->patron_photo) }}" alt="Patron Picture">
			@endif
			{{ Form::file('patronPicture', ['class' => 'form-control-file', 'aria-describedby' => 'patronPicture']) }}
		</div>

		{{ Form::hidden('userId', Auth::user()->id) }}
		{{ Form::hidden('incident', $incident->id) }}
		<div class="text-center">
			{{ Form::button('<span class="glyphicon glyphicon-floppy-disk repository-save-button"></span>',
							['class' => 'btn btn-default', 'type' => 'submit', 'title' => 'Save']) }}
		</div>
	{{ Form::close() }}
  
@endsection