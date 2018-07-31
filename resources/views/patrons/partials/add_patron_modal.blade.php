<div class="modal fade" id="addPatronModal" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<h3 class="modal-title">
					Add a New Patron
				</h3>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			<div class="modal-body">
				<div id="addPatronFormWrapper">
					@include('patrons.create')
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addPatron()">Save</button>
			</div>

		</div> {{-- .modal-content --}}
	</div> {{-- .modal-dialog --}}
</div> {{-- #addPatron modal --}}