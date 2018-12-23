{{ Form::open(['action' => 'PhotoController@create', 'files' => true, 'id' => 'addPhotoForm']) }}

	<div class="form-group required">
		{{ Form::label('photo', 'Select file:', ['class' => 'control-label']) }}
		{{ Form::file('photo', null, ['class' => 'form-control', 'required' => 'required']) }}
	</div>

	<div class="form-group">
		{{ Form::label('photoCaption', 'Caption:', ['class' => 'control-label']) }}
		{{ Form::textarea('photoCaption', null, ['class' => 'form-control', 'rows' => 4]) }}
	</div>
	
	<h4>
		This photo is of:
	</h4>

	<div class="form-group">
		<div id="photo-patrons" class="form-group">

			<label class="control-label" for="associatedPatrons[]">
				The following patron(s):
			</label>
	
			<select name="associatedPatrons[]" id="associatedPatrons" multiple="multiple"
						style="width:68%;">
				@if (url()->current() === route('createIncident'))
					{{-- patrons already added to the create incident form will be injected here --}}
				@elseif (url()->current() === route('createPhoto'))
					@foreach ($patrons as $patron)
						<option value="{{ $patron->id }}">
							{{ $patron->get_name('list') }}
						</option>
					@endforeach
				@endif
			</select>

		</div>

		@if (url()->current() === route('createPhoto'))
			<div id="photo-incidents" class="form-group">

				<label class="control-label" for="associatedIncident">
					The following incident:
				</label>
			
				<select name="associatedIncident" id="associatedIncident" style="width:68%;">
					<option></option>
					@foreach ($incidents as $incident)
						<option value="{{ $incident->id }}" title="{{ $incident->description }}">
							{{ $incident->title }}
						</option>
					@endforeach
				</select>

			</div>
		@endif

	</div>	{{-- .form-group --}}

	{{ Form::hidden('user', Auth::id()) }}

	<div class="text-right repository-margin-top-1rem">
		{{ Form::button('Save Photo', ['class' => 'btn btn-default btn-success',
						'type' => 'submit', 'title' => 'Save']) }}
	</div>

{{ Form::close() }}