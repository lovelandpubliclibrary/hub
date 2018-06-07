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
				$('#existingPatrons').append(option).trigger('change');

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
	} else {		// invalid
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
	form_data.append('photo', file);
	form_data.append('caption', form.find('textarea').val());
	form_data.append('associatingPatrons', form.find('input[name="associatingPatrons"]:checked').val());
	form_data.append('associatedPatrons', form.find('#associated-patrons').val());

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

				// build the photo DOM/content and append to #incident-photo-thumbnail-wrapper
				var photo_column = $('<div class="col-xs-3">');		// build the bootstrap column
				photo_column.append('<div class="incident-photo">');
				var photo_container = photo_column.find('.incident-photo')	// build the photo container
				photo_container.append($('<div class="thumbnail">'));
				var photo = photo_container.find('.thumbnail');
				photo.append($(`<img src="${response.url}" alt="${response.filename}">`));
				photo.append($(`<input type="hidden" name="photo_id_${response.id}" value="${response.id}">`));
				photo.append($('<button class="btn btn-sm btn-danger remove-photo-btn">Remove</button>'));
				$('#incident-photo-thumbnail-wrapper').append(photo_column);

				// add the event handler to the remove button
				photo.find('button.remove-photo-btn').click(function() {
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
	var selected_existing_patrons = $('select[name="existingPatrons[]"] option:selected');
	var added_patrons = $('#addedPatrons .patron:not(:first)');

	// add the selected patrons to the placeholder array
	selected_existing_patrons.each(function() {
		patrons.push({id:this.value, name:$(this).text()});
	});

	// add the added patrons to the placeholder array
	added_patrons.each(function() {
		var name = $(this).find('.patron-name').text();
		var value = $(this).find('input[name="patron-id"]').val();

		patrons.push({id:value, name:name});
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
		console.log($(this).closest('.incident-photo').parent());
	});

	/* Prepare photo modal on create incident form */
	$('#togglePhotoModal').click(function() {
		// remove any previously added checkboxes
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

});