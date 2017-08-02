{{ Form::open(['action' => 'CommentController@store']) }}
		<div class="form-group">
		    {{ Form::label('comment', 'Comment:', ['class' => 'sr-only']) }}
		    {{ Form::textarea('comment', null, [
		    				  'class' => 'form-control',
		    				  'required' => 'required',
		    				  'rows' => '5']) }}
	  	</div>


		{{ Form::hidden('user', Auth::user()->id) }}
		{{ Form::hidden('incident', $incident->id) }}
		<div class="text-center">
			{{ Form::button('Save Comment', [
							'class' => 'btn btn-default', 
							'type' => 'submit', 
							'title' => 'Save']) }}
		</div>

	{{ Form::close() }}