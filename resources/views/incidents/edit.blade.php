@extends('layouts.app')

@section('content')
	<div id="#incidents">
		@include('layouts.breadcrumbs')

		@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif

		<div class="h1 text-center">
			Edit an Incident
		</div>

		{{ Form::open(['action' => 'IncidentController@update', 'files' => true]) }}
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="form-group required">
						{{ Form::label('title', 'Title:') }}
						{{ Form::text('title', $incident->title, ['class' => 'form-control']) }}
					</div>
				</div>

				<div class="panel-body">
					<div class="form-group required">
					    {{ Form::label('date', 'Date of Incident:') }}
					    {{ Form::date('date', $incident->date, ['class' => 'form-control']) }}
				  	</div>

				  	<div class="form-group required">
					    {{ Form::label('time', 'Time of incident:', ['class' => 'control-label']) }}
					    {{ Form::time('time', \Carbon\Carbon::now()->toTimeString(), ['class' => 'form-control', 'required' => 'required']) }}
				  	</div>

				  	<div class="form-group required">
					    {{ Form::label('locations', 'Location(s) the incident took place:', ['class' => 'control-label']) }}
					    {{ Form::select('locations[]', $locations, $incident->location->pluck('id'), ['class' => 'selectpicker form-control',
					    												'multiple' => 'multiple']) }}
				  	</div>

				  	<div class="form-group">
					    {{ Form::label('staffInvolved', 'Other staff members involved:', ['class' => 'control-label']) }}
					    {{ Form::select('staffInvolved[]', $staff, $incident->usersInvolved->pluck('id'), ['class' => 'selectpicker form-control',
					    												'data-size' => '8',
					    												'multiple' => 'multiple']) }}
				  	</div>

					<div class="form-group">
						{{ Form::label('patron_name', 'Patron Name:') }}
						{{ Form::text('patron_name', $incident->patron_name, ['class' => 'form-control']) }}
					</div>

					<div class="form-group">
						{{ Form::label('card_number', 'Patron Library Card Number:') }}
						{{ Form::text('card_number', $incident->card_number, ['class' => 'form-control']) }}
					</div>

					<div class="form-group">
						{{ Form::label('patron_description', 'Patron Description:') }}
						{{ Form::text('patron_description', $incident->patron_description, ['class' => 'form-control']) }}
					</div>

					<div class="form-group required">
						{{ Form::label('description', 'Describe the Incident:') }}
						{{ Form::textarea('description', $incident->description, ['class' => 'form-control', 'rows' => '6']) }}
					</div>

					<div class="form-group">
						@if (isset($photos))
							@foreach ($photos as $photo)
								<div class="col-xs-12 col-sm-5 col-md-4 text-center">
									<a href="{{ route('editPhoto', ['photo' => $photo->id]) }}">
										<img class="img-responsive thumbnail" src="{{ asset('images/patrons/' . $photo->filename) }}" alt="Patron Picture">
									</a>
								</div>
							@endforeach
						@endif
						<div class="col-xs-12">
							{{ Form::label('patron_photo', 'Upload a Picture:') }}
							{{ Form::file('patron_photo', ['class' => 'form-control-file', 'aria-describedby' => 'patron_photo']) }}
						</div>
					</div>

					<div class="panel-footer col-xs-12 text-right repository-margin-top-1rem">
						{{ Form::hidden('user', Auth::id()) }}
						{{ Form::hidden('incident', $incident->id) }}
						{{ Form::button('Save Changes',
										['class' => 'btn btn-default btn-success', 'type' => 'submit', 'title' => 'Save']) }}
					</div>
				</div><!-- .panel-body -->
			</div><!-- .panel -->
		{{ Form::close() }}
	</div> <!-- #incidents -->
@endsection