@extends('layouts.app')

@section('content')

  <div class="h1">
    Report an Incident
  </div>

	{{ Form::open(['action' => 'IncidentController@store', 'files' => true]) }}

    <div class="form-group">
	    {{ Form::label('incidentDate', 'Date of Incident:') }}
	    {{ Form::date('incidentDate', \Carbon\Carbon::now(), ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
	    {{ Form::label('patronName', 'Patron Name:') }}
      {{ Form::text('patronName', null, ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('incidentTitle', 'Title:') }}
      {{ Form::text('incidentTitle', null, ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('incidentDescription', 'Describe the Incident:') }}
      {{ Form::textarea('incidentDescription', null, ['class' => 'form-control', 'rows' => '6']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('patronPicture', 'Patron Picture:') }}
	    {{ Form::file('patronPicture', ['class' => 'form-control-file', 'aria-describedby' => 'patronPicture']) }}
	  </div>

    {{ Form::hidden('user_id', Auth::user()->id) }}
    {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}

	{{ Form::close() }}
  
@endsection