{{ Form::open(['url' => '#', 'id' => 'addExistingPhotoForm']) }}

<div class="form-group required">
	<div class="row">
		@if ($photoColumns->count() > 1)
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
		@else
			<button class="thumbnail selectExistingPhoto"
				data-photo-id="{{ $photoColumns->first()->id }}">
				<img src="{{ asset('storage/photos/' . $photoColumns->first()->filename) }}">
				<div>
					Photo #{{ $photoColumns->first()->id }}
				</div>
			</button>
		@endif
	</div>
</div>

{{ Form::close() }}