function addPatron() {
	var errors = new Array;		// placeholder for error messages

	// clear previous error messages
	$('#addPatronFormWrapper .alert').remove();

	//check if any new patron inputs were filled out
	var filled_inputs = $('#addPatronFormWrapper input, #addPatronFormWrapper textarea').filter(function() {
		return $(this).val();
	});

	if (filled_inputs.length && $('#patronDescription').val() ) {
		// create a variable to hold an individual patron data
		var new_patron = new Object;
		new_patron.first_name = $('#patronFirstName').val();
		new_patron.last_name = $('#patronLastName').val();
		new_patron.description = $('#patronDescription').val();
		new_patron.cardNumber = $('#patronCardNumber').val();

		// provide feedback that the patron is being saved
		var modal_footer = $('#addPatronModal .modal-footer');
		var stashed_buttons = modal_footer.children();
		modal_footer.children().remove();
		modal_footer.html('Saving...');


		// submit the request to PatronController@store
		$.ajax({
			headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type:'POST',
			data:new_patron,
			url:'/patrons/create',
			success:function(patron_json) {
				// add the patron to the selected patrons dropdown
				// https://select2.org/programmatic-control/add-select-clear-items
				var select2_data = {
					id: patron_json.id,
					text: patron_json.list_name,
				};

				var option = new Option(select2_data.text, select2_data.id, true, true);
				$('#patrons').append(option).trigger('change');

				// reset the new patron form
				filled_inputs.each(function() {
					$(this).val('');
				});

				modal_footer.html('').append(stashed_buttons);

				// dismiss the form
				$('#addPatronModal button[data-dismiss="modal"]:first').click();
			},
			error:function(response) {
				errors.push('Error saving patron to database.');
				console.log(JSON.stringify(response));
				modal_footer.html('').append(stashed_buttons);
			}
		});
	} else {		// form is invalid
		errors.push('A description is required in order to save a new patron.');
	}

	if (errors.length) {
		$.each(errors, function() {
			var error = $.parseHTML(`<div class="alert alert-danger">${this}</div>`);
			$('#addPatronFormWrapper').prepend(error);
		});
	} else {
		$('#addPatron button[data-dismiss="modal"]:first').click();
	}
}


function addPhoto() {
	var errors = new Array;		// placeholder for error messages

	// clear previous error messages
	$('#addPhotoFormWrapper .alert').remove();

	// collect the values of the new photo form
	var form = $('#addPhotoForm');
	var form_data = new FormData();
	var file = form.find('input[type="file"]')[0].files[0];
	var associated_patrons = form.find('#associated-patrons').val();
	form_data.append('photo', file);
	form_data.append('caption', form.find('textarea').val());
	form_data.append('associatingPatrons', form.find('input[name="associatingPatrons"]:checked').val());
	form_data.append('associatedPatrons', associated_patrons);

	// validate the form
	if (typeof file == 'undefined') {		// no file uploaded
		errors.push('You must upload an image.');
	}

	if ($('input[name="associatingPatrons"]:checked').val() === "1" &&
			form.find('#associated-patrons').val().length === 0) {
		errors.push('No patrons selected.')
	}

	
	if (errors.length) {
		$.each(errors, function() {
			var error = $.parseHTML(`<div class="alert alert-danger">${this}</div>`);
			$('#addPhotoFormWrapper').prepend(error);
		});
	} else {		// no errors, continue processing form
		// provide feedback that the photo is being saved
		var modal_footer = $('#addPhotoModal .modal-footer');
		var stashed_buttons = modal_footer.children();
		modal_footer.children().remove();
		modal_footer.html('Saving...');

		// submit the request to PhotoController@store
		$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			data: form_data,
			url: '/photos/create',
			processData: false,
			contentType: false,
			success: function(response) {
				// reset the new photo form
				form[0].reset();
				modal_footer.html('').append(stashed_buttons);

				// dismiss the new photo form modal
				$('#addPhotoModal button[data-dismiss="modal"]:first').click();

				// build the photo DOM/content and append to .photo-thumbnail-wrapper
				var photo_column = $('<div class="col-xs-3">');		// build the bootstrap column
				photo_column.append('<div class="photo">');
				var photo_container = photo_column.find('.photo')	// build the photo container
				photo_container.append($('<div class="thumbnail">'));
				var photo = photo_container.find('.thumbnail');
				photo.append($(`<img src="${response.url}" alt="${response.filename}">`));
				photo.append($('<button class="btn btn-sm btn-danger remove-photo-btn">Remove</button>'));
				photo.append($(`<input type="hidden" name="photos[]" value=${response.id}>`));
				$('.photo-thumbnail-wrapper').append(photo_column);

				// add the event handler to the remove button, which will undo all of this
				photo.find('button.remove-photo-btn').click(function(event) {
					event.stopPropagation();
					event.preventDefault();

					// remove the thumbnail wrapper and it's contents
					photo_column.remove();
				});
				
			},
			error: function(response) {
				errors.push('Error saving photo to database.');
				console.log(JSON.stringify(response));
				modal_footer.html('').append(stashed_buttons);
			}
		});
	}
}


function buildAssociatedPatronsDropdown(){
	// start by hiding elements which may be unnecessary
	$('#photo-patrons').hide();

	// create a place to store the collected patron information
	var patrons = [];

	// collect the patrons from the create incident form
	var selected_patrons = $('select[name="patrons[]"] option:selected');

	// add the selected patrons to the placeholder array
	selected_patrons.each(function() {
		patrons.push({id:this.value, name:$(this).text()});
	});

	// check if patrons have been identified on the create incident form
	if (patrons.length) {
		// make visible the dropdown of patrons that could be associated with this photo
		$('#photo-patrons').show();

		// create and add an option to the dropdown for each patron
		$.each(patrons, function(index, patron) {
			// add the patron to the associated-patrons dropdown
			var associated_patrons = $('#associated-patrons');

			// https://select2.org/programmatic-control/add-select-clear-items
			var select2_data = {
				id: patron.id,
				text: patron.name,
			};

			var option = new Option(select2_data.text, select2_data.id, false, false);
			var associated_patrons = $('#associated-patrons');
			if (associated_patrons.hasClass('select2-hidden-accessible')) {
				associated_patrons.select2('destroy');
			}
			associated_patrons.append(option).trigger('change');
			associated_patrons.select2();
		});
	}
}


/***********************************
 Event Handlers & Content Modifiers
***********************************/
$(document).ready(function() {
	/* Instantiate Select2 jQuery plugin for all select elements */
	$('select').select2();

	/* Remove a photo added to the create incident form */
	$('button.remove-photo-btn').click(function() {
		console.log($(this).closest('.photo').parent());
	});

	/* Prepare photo modal on create incident form */
	$('#toggleAddPhotoModal').click(function() {
		// remove any previously added checkboxes from the modal form
		$('#associated-patrons').children('option').each(function() {
			$(this).remove();
		});

		buildAssociatedPatronsDropdown();
	})

	/* Ensure the correct input values when associating patrons to photos */
	// unselect all patrons when incident is selected
	$('#associatingPatrons').click(function() {
		$('#associated-patrons').val(null).trigger('change');
	});

	// update radio button to match patron selection
	$('#associated-patrons').on('select2:select', function() {
		$(this).siblings('label').find('input[type="radio"]').prop('checked', true);
	});

	// select incident-only when the selected last patron is removed
	$('#associated-patrons').on('select2:unselect', function() {
		if ($(this).find('option:selected').length == 0) {
			$('#associatingPatrons').prop('checked', true);
		}
	});

	// remove parent photo element for .remove-photo-btn buttons
	$('.remove-photo-btn').click(function() {
		$(this).closest('.photo').parent().remove();
	});

	/* Toggle the list of users who haven't viewed an incident */
	$('.not-viewed ul').click(function() {
		// collect the arrow element
		var arrow = $(this).find('.arrow').first().toggleClass('arrow-right arrow-down');
		
		// toggle the display of the list items
		$(this).find('li').toggle();
	});

	// Prevent links from triggering the toggle of $('.not-viewed ul')
	$('.not-viewed ul').find('a').click(function(event) {
		event.stopPropagation();
	});

});