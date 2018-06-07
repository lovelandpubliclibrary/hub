{{ Form::open(['action' => 'PatronController@store']) }}

	<div class="form-group">
		{{ Form::label('patronFirstName', 'First Name:', ['class' => 'control-label']) }}
		{{ Form::text('patronFirstName', null, ['class' => 'form-control']) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('patronLastName', 'Last Name:', ['class' => 'control-label']) }}
		{{ Form::text('patronLastName', null, ['class' => 'form-control']) }}
	</div>
	
	<div class="form-group required">
		{{ Form::label('patronDescription', 'Description:', ['class' => 'control-label']) }}
		{{ Form::textarea('patronDescription', null,
								['class' => 'form-control', 'rows' => 4, 'required' => 'required']) }}
	</div>
	
	<div class="form-group">
		{{ Form::label('patronCardNumber', 'Library Card Number:', ['class' => 'control-label']) }}
		{{ Form::text('patronCardNumber', null, ['class' => 'form-control']) }}
	</div>

{{ Form::close() }}