<div class="modal fade" id="addPhotoModal" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<h3 class="modal-title">
					Add a Photo
				</h3>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div id="addPhotoFormWrapper">
					@include('photos.partials.add_photo_form')
				</div>
			</div>

		</div> {{-- .modal-content --}}
	</div> {{-- .modal-dialog --}}
</div> {{-- #addPhotoModal --}}
