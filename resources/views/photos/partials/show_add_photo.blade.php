<div class="form-group">

	<label class="control-label">
		Photos:
	</label>

	<div class="row">
		<div class="photo-thumbnail-wrapper">
			@isset($photos)
				@foreach ($photos as $photo)
					<div class="col-xs-12 col-sm-5 col-md-4 text-center">
						<div class="photo">
							<div class="thumbnail">
								<img src="{{ asset('storage/photos/' . $photo->filename) }}" 
									 alt="{{ $photo->filename }}">
								<button class="btn btn-sm btn-danger remove-photo-btn">
									Remove
								</button>
							</div>
						</div>
						
						<input type="hidden" name="photos[]" value="{{ $photo->id }}">

					</div>
				@endforeach
			@endisset
			{{-- photo thumbnails injected here w/ jQuery when added to incident --}}
		</div> {{-- .photo-thumbnail-wrapper --}}
	</div>	{{-- .row --}}
	
	<div class="row">
		<div class="col-xs-12" id="toggle-photo-modal-wrapper">

			<button type="button" id="toggleExistingPhotoModal"
							class="btn btn-default block" data-toggle="modal"
							data-target="#existingPhotoModal">
				<div>
					<span class="glyphicon glyphicon-plus-sign"></span>
				</div>
				Add Existing Photo
			</button>

			<button type="button" id="toggleAddPhotoModal"
							class="btn btn-default block" data-toggle="modal"
							data-target="#addPhotoModal">
				<div>
					<span class="glyphicon glyphicon-plus-sign"></span>
				</div>
				Add a New Photo
			</button>

		</div>		{{-- #toggle-photo-modal-wrapper --}}
	</div>		{{-- .row --}}
</div>	{{-- .form-group --}}