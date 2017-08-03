@extends('layouts.app')

@section('content')
	@include('layouts.breadcrumbs')

	<div class="h1 text-center">
		Report an Incident
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

	{{ Form::open(['action' => 'IncidentController@store', 'files' => true]) }}
		<div class="form-group">
			{{ Form::label('title', 'Title:') }}
			{{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) }}
		</div>
		
		<div class="form-group">
		    {{ Form::label('date', 'Date of Incident:') }}
		    {{ Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control', 'required' => 'required']) }}
	  	</div>

		<div class="form-group">
			{{ Form::label('patron_name', 'Patron Name:') }}
			{{ Form::text('patron_name', null, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('card_number', 'Patron Library Card Number:') }}
			{{ Form::text('card_number', null, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('patron_description', 'Patron Description:') }}
			{{ Form::text('patron_description', null, ['class' => 'form-control']) }}
		</div>

		<div class="form-group">
			{{ Form::label('description', 'Describe the Incident:') }}
			{{ Form::textarea('description', null,
							  ['class' => 'form-control', 'rows' => '6', 'required' => 'required']) }}
		</div>

		<div class="form-group">
			{{ Form::label('patron_photo', 'Patron Picture:') }}
			{{ Form::file('patron_photo', ['class' => 'form-control-file', 'aria-describedby' => 'patron_photo']) }}
		</div>

		{{ Form::hidden('userId', Auth::user()->id) }}
		<div class="text-center">
			{{ Form::button('Save Changes',
							['class' => 'btn btn-default', 'type' => 'submit', 'title' => 'Save']) }}
		</div>

	{{ Form::close() }}
  
@endsection