{{ Form::open(['action' => 'PhotoController@store', 'files' => true, 'id' => 'addPhotoForm']) }}

	<div class="form-group required">
		{{ Form::label('photo', 'Select file:', ['class' => 'control-label']) }}
		{{ Form::file('photo', null, ['class' => 'form-control', 'required' => 'required']) }}
	</div>

	<div class="form-group">
		{{ Form::label('photoCaption', 'Caption:', ['class' => 'control-label']) }}
		{{ Form::textarea('photoCaption', null, ['class' => 'form-control', 'rows' => 4]) }}
	</div>
	
	<h4>This is a photo of:</h4>

	<div class="form-group">
		<label class="control-label">
			{{-- the value of the radio buttons is set to 1 or 0 to be compatible with laravel validation --}}
			{{ Form::radio('associatingPatrons', '0', true, ['id' => 'associatingPatrons']) }}
			Just this incident (no patrons)
		</label>

		<div id="photo-patrons" class="form-group">

			<label class="control-label">
				{{ Form::radio('associatingPatrons', '1') }}
				The following patron(s):
			</label>
	
			<select name="associatedPatrons[]" id="associated-patrons" multiple="multiple"
						style="width:68%;">
				{{-- options for patrons added to the create incident form injected here --}}
			</select>

		</div>
	</div>	{{-- .form-group --}}

{{ Form::close() }}