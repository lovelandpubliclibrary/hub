{{ Form::open(['action' => 'PatronController@store', 'id' => 'addPatronForm']) }}

	<div class="form-group">
		{{ Form::label('first_name', 'First Name:', ['class' => 'control-label']) }}
		{{ Form::text('first_name', null, ['class' => 'form-control']) }}
	</div>

	<div class="form-group">
		{{ Form::label('last_name', 'Last Name:', ['class' => 'control-label']) }}
		{{ Form::text('last_name', null, ['class' => 'form-control']) }}
	</div>

  	<div class="form-group required">
  		{{ Form::label('description', 'Description:', ['class' => 'control-label']) }}
  		{{ Form::textarea('description', null,
  						  ['class' => 'form-control', 'rows' => '6', 'required' => 'required']) }}
  	</div>

  	<div class="form-group">
  		{{ Form::label('card_number', 'Library Card Number:', ['class' => 'control-label']) }}
  		{{ Form::text('card_number', null, ['class' => 'form-control']) }}
  	</div>

	{{ Form::hidden('user', Auth::id()) }}

	<div class="text-right repository-margin-top-1rem">
		{{ Form::button('Save Patron', ['class' => 'btn btn-default btn-success',
						'type' => 'submit', 'title' => 'Save']) }}
	</div>

{{ Form::close() }}