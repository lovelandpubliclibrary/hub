{{ Form::open(['url' => '#', 'id' => 'addExistingPhotoForm']) }}

<div class="form-group required">
	<div class="row">
		@foreach ($photoColumns as $column)
			<div class="column">
				@foreach ($column as $photo)
					<button class="thumbnail selectExistingPhoto"
						data-photo-id="{{ $photo->id }}">
						<img src="{{ asset('storage/photos/' . $photo->filename) }}">
						<div>
							Photo #{{ $photo->id }}
						</div>
					</button>
				@endforeach
			</div>
			
		@endforeach
	</div>
</div>

{{ Form::close() }}