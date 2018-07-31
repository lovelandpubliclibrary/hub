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
					@include('photos.create')
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addPhoto()">Save</button>
			</div>

		</div> {{-- .modal-content --}}
	</div> {{-- .modal-dialog --}}
</div> {{-- #addPhotoModal --}}