{{ Form::open(['url' => '#', 'id' => 'addExistingPhotoForm']) }}

<div class="form-group required">
	<div class="row">
		@foreach ($photos as $column)
			<div class="column">
				@foreach ($column as $photo)
					<button class="thumbnail selectExistingPhoto"
						data-photo-id="{{ $photo->id }}">
						<img src="{{ asset('storage/photos/' . $photo->filename) }}">
					</button>
				@endforeach
			</div>
			
		@endforeach
	</div>
</div>

{{ Form::close() }}