{{ Form::open(['action' => ['PatronController@update', $patron->id], 'id' => 'editPatronForm']) }}

	<div class="form-group">
		{{ Form::label('first_name', 'First Name:', ['class' => 'control-label']) }}
		{{ Form::text('first_name', $patron->first_name, ['class' => 'form-control']) }}
	</div>

	<div class="form-group">
		{{ Form::label('last_name', 'Last Name:', ['class' => 'control-label']) }}
		{{ Form::text('last_name', $patron->last_name, ['class' => 'form-control']) }}
	</div>

  	<div class="form-group required">
  		{{ Form::label('description', 'Description:', ['class' => 'control-label']) }}
  		{{ Form::textarea('description', $patron->description,
  						  ['class' => 'form-control', 'rows' => '6', 'required' => 'required']) }}
  	</div>

  	<div class="form-group">
  		{{ Form::label('card_number', 'Library Card Number:', ['class' => 'control-label']) }}
  		{{ Form::text('card_number', $patron->card_number, ['class' => 'form-control']) }}
  	</div>

  	{{ Form::hidden('user', $patron->user->id) }}

	<div class="text-right repository-margin-top-1rem">
		{{ Form::button('Save Patron', ['class' => 'btn btn-default btn-success',
						'type' => 'submit', 'title' => 'Save']) }}
	</div>

{{ Form::close() }}