@extends('layouts.app')

@section('content')
	<div class="text-muted">
		<a href='/incidents'>
			<< Back to Incidents
		</a>
	</div>

  <div class="h1">
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
	    {{ Form::label('date', 'Date of Incident:') }}
	    {{ Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
	    {{ Form::label('patronName', 'Patron Name:') }}
      {{ Form::text('patronName', null, ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
	    {{ Form::label('patronDescription', 'Patron Description:') }}
      {{ Form::text('patronDescription', null, ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('title', 'Title:') }}
      {{ Form::text('title', null, ['class' => 'form-control']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('description', 'Describe the Incident:') }}
      {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '6']) }}
	  </div>

	  <div class="form-group">
      {{ Form::label('patronPicture', 'Patron Picture:') }}
	  {{ Form::file('patronPicture', ['class' => 'form-control-file', 'aria-describedby' => 'patronPicture']) }}
	  </div>

    {{ Form::hidden('userId', Auth::user()->id) }}
    {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}

	{{ Form::close() }}
  
@endsection