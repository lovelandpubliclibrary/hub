@extends('layouts.app')

@section('content')
	<div id="incidents">

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

		<div class="panel panel-default" id="incident">
			<div class="panel-heading col-xs-12 text-center repository-margin-bottom-1rem">
				<div class="col-xs-10 col-xs-offset-1">
					<h2 class="panel-title">
						Report an Incident
					</h2>
				</div>
			</div><!-- .panel-heading -->

			<div class="panel-body">
				{{ Form::open(['action' => 'IncidentController@store', 'files' => true]) }}
					<div class="form-group required">
						{{ Form::label('title', 'Title:', ['class' => 'control-label']) }}
						{{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required']) }}
					</div>
					
					<div class="form-group required">
					    {{ Form::label('date', 'Date of incident:', ['class' => 'control-label']) }}
					    {{ Form::date('date', \Carbon\Carbon::now(), ['class' => 'form-control', 'required' => 'required']) }}
				  	</div>

				  	<div class="form-group required">
					    {{ Form::label('time', 'Time of incident:', ['class' => 'control-label']) }}
					    {{ Form::time('time', \Carbon\Carbon::now()->toTimeString(), ['class' => 'form-control', 'required' => 'required']) }}
				  	</div>

				  	<div class="form-group required">
					    {{ Form::label('locations', 'Location(s) the incident took place:', ['class' => 'control-label']) }}
					    {{ Form::select('locations[]', $locations, null, ['class' => 'selectpicker form-control',
					    												'data-size' => '8',
					    												'multiple' => 'multiple']) }}
				  	</div>

				  	<div class="form-group">
					    {{ Form::label('usersInvolved', 'Other staff members involved:', ['class' => 'control-label']) }}
					    {{ Form::select('usersInvolved[]', $staff, null, ['class' => 'selectpicker form-control',
					    												'data-size' => '8',
					    												'multiple' => 'multiple']) }}
				  	</div>

				  	<div class="form-group required">
				  		{{ Form::label('description', 'Describe the incident:', ['class' => 'control-label']) }}
				  		{{ Form::textarea('description', null,
				  						  ['class' => 'form-control', 'rows' => '6', 'required' => 'required']) }}
				  	</div>

				  	<div class="form-group">
						{{ Form::label('existingPatrons[]', 'Patrons Involved:', ['class' => 'control-label']) }}
						{{ Form::select('existingPatrons[]', $patrons, null, ['class' => 'selectpicker form-control',
																			'multiple' => 'multiple',
																			'id' => 'existingPatrons']) }}
					</div>

					<div class="form-group">
						<div>
							<button type="button" id="togglePatronModal" class="btn btn-default" data-toggle="modal" data-target="#addPatronModal">
								<div>
									<span class="glyphicon glyphicon-plus-sign"></span>
								</div>
								Add a new patron
							</button>
						</div>
					</div> {{-- .form-group --}}

					<h3 class="hub-center">
						Photos
					</h3>
					<div class="form-group">

							<div class="row" id="incident-photo-thumbnail-wrapper">
								{{-- photo inputs injected here w/ jQuery when added to incident --}}
							</div>
							
							<div class="row">
								<div class="col-xs-12" id="toggle-photo-modal-wrapper">
									<button type="button" id="togglePhotoModal" class="btn btn-default block" data-toggle="modal" data-target="#addPhotoModal">
										<div>
											<span class="glyphicon glyphicon-plus-sign"></span>
										</div>
										Add a Photo
									</button>
								</div>
							</div>
					</div>	{{-- .form-group --}}

					{{ Form::hidden('user', Auth::id()) }}

					<div class="col-xs-12 panel-footer text-right repository-margin-top-1rem">
						{{ Form::button('Save Incident', ['class' => 'btn btn-default btn-success',
										'type' => 'submit', 'title' => 'Save']) }}
					</div>

				{{ Form::close() }}
			</div><!-- .panel-body -->
		</div> <!-- .panel -->

		{{-- Modal Add Patron Form --}}
		<div class="modal fade" id="addPatronModal" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					
					<div class="modal-header">
						<h3 class="modal-title">
							Add a New Patron
						</h3>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<div class="modal-body">
						<div id="addPatronFormWrapper">
							@include('patrons.create')
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="addPatron()">Save</button>
					</div>

				</div> {{-- .modal-content --}}
			</div> {{-- .modal-dialog --}}
		</div> {{-- #addPatron modal --}}


		{{-- Modal Add Photo Form --}}
		<div class="modal fade" id="addPhotoModal" role="dialog" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					
					<div class="modal-header">
						<h3 class="modal-title">
							Add a Photo
						</h3>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					
					<div class="modal-body">
						<div id="addPhotoFormWrapper">
							@include('photos.create')
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" onclick="addPhoto()">Save</button>
					</div>

				</div> {{-- .modal-content --}}
			</div> {{-- .modal-dialog --}}
		</div> {{-- #addPhotoModal --}}

	</div> <!-- #incidents -->
  
@endsection