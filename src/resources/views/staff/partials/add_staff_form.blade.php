{{ Form::open(['action' => 'StaffController@store', 'id' => 'addStaffForm']) }}

	<div class="form-group required">
		{{ Form::label('name', 'Name:', ['class' => 'control-label']) }}
		{{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
	</div>

	<div class="form-group required">
		{{ Form::label('email', 'City Email:', ['class' => 'control-label']) }}
		{{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required']) }}
	</div>

	<div class="form-group">
		{{ Form::label('password', 'Password:', ['class' => 'control-label']) }}
		<div class="input-group">
			{{ Form::text('password', $password, ['class' => 'form-control', 'readonly' => 'readonly']) }}
			<span class="input-group-addon btn btn-info" id="copyPassword">
				<img src="https://img.icons8.com/ios/15/000000/copy.png">
			</span>
		</div>
	</div>

  	<div class="form-group required">
  		{{ Form::label('divisions', 'Divisions:', ['class' => 'control-label']) }}
  		<select name="divisions[]" id="divisions" multiple="multiple" class="form-control">
  			@foreach ($divisions as $division)
  				<option value="{{ $division->id }}">
  					{{ $division->division }}
  				</option>
  			@endforeach
  		</select>
  	</div>

  	<div class="form-group required">
  		{{ Form::label('supervisor', 'Supervisor:', ['class' => 'control-label']) }}
  		<select name="supervisor" id="supervisor" class="form-control">
  			@foreach ($supervisors as $supervisor)
  				<option value="{{ $supervisor->id }}">
  					{{ $supervisor->name }}
  				</option>
  			@endforeach
  		</select>
  	</div>

  	<div class="form-group">
  		{{ Form::label('supervises', 'Supervises:', ['class' => 'control-label']) }}
  		<select name="supervises[]" id="supervises" multiple="multiple" class="form-control">
  			@foreach ($staff as $staff_member)
  				<option value="{{ $staff_member->id }}">
  					{{ $staff_member->name }}
  				</option>
  			@endforeach
  		</select>
  	</div>
	
	{{ Form::hidden('created_by', Auth::id()) }}

	<div class="text-right repository-margin-top-1rem">

		<div class="repository-margin-right-1rem">
			@if (Auth::user()->isAdministrator())
				{{ Form::label('administrator', 'Administrator?') }}
				{{ Form::checkbox('administrator', '1') }}
			@endif
		</div>

		{{ Form::button('Save', ['class' => 'btn btn-default btn-success',
						'type' => 'submit', 'title' => 'Save']) }}
	</div>

{{ Form::close() }}