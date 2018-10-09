/***********************************
 Event Handlers & Content Modifiers
***********************************/
$(document).ready(function() {
	/* Instantiate Select2 jQuery plugin for all select elements */
	$('select').select2();

	/* Submit the AddPhoto form via AJAX when displayed as a modal */
	$('#addPhotoModal button[type="submit"]').click(function(event) {

		// prevent default events from firing
		event.preventDefault();
		event.stopPropagation();

		var save_button = $(this);
		var save_button_original_text = save_button.html();
		var errors = new Array;		// placeholder for error messages

		// clear previous error messages
		$('#addPhotoFormWrapper .alert').remove();

		// collect the values of the new photo form
		var form = $('#addPhotoForm');
		var form_data = new FormData();
		var file = form.find('input[type="file"]')[0].files[0];
		form_data.append('photo', file);
		form_data.append('user', form.find('input[name="user"]').val());
		if (form.find('textarea').val()) {
			form_data.append('caption', form.find('textarea').val());
		}

		if (form.find('#associatedPatrons').val()) {
		 	form_data.append('associatedPatrons[]', form.find('#associatedPatrons').val());
		}

		// provide feedback that the patron is being saved
		if (save_button.length) {
			save_button.html('Saving...');
			save_button.prop('disabled', true);
		}

		// submit the request to PhotoController@store
		$.ajax({
			headers:{'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type:'POST',
			data:form_data,
			url:'/photos/create',
			processData: false,
			contentType: false,
			success:function(photo_json) {
				// build the photo DOM/content and append to .photo-thumbnail-wrapper
				var photo_column = $('<div class="col-xs-3">').append('<div class="photo">');
				var photo_container = photo_column.find('.photo')	// build the photo container
				photo_container.append($('<div class="thumbnail">'));
				var photo = photo_container.find('.thumbnail');
				photo.append($(`<img src="${photo_json.url}" alt="${photo_json.filename}">`));
				photo.append($('<button class="btn btn-sm btn-danger remove-photo-btn">Remove</button>'));
				photo.append($(`<input type="hidden" name="photos[]" value=${photo_json.id}>`));
				console.log(photo_column);
				$('.photo-thumbnail-wrapper').append(photo_column);

				// add the event handler to the remove button, which will undo all of this
				photo.find('button.remove-photo-btn').click(function(event) {
					event.stopPropagation();
					event.preventDefault();

					// remove the thumbnail wrapper and it's contents
					photo_column.remove();
				});

				// reset the new patron form
				form.trigger('reset');

				// close the modal
				$('#addPhotoModal button[data-dismiss="modal"]:first').click();
			},
			error:function(response) {
				console.log(response)
				switch (response.status) {
					case 422:    // validation errors
						$.each(response.responseJSON, function() {
							errors.push(this[0]);
						});

						break;
					default:
						errors.push(response.responseText)
						break;
				}
			},
			complete:function() {
				if (errors.length) {
					$.each(errors, function() {
						var error = $.parseHTML(`<div class="alert alert-danger">${this}</div>`);
						$('#addPhotoFormWrapper').prepend(error);
					});
				}

				// reset the save button
				save_button.html(save_button_original_text);
				save_button.prop('disabled', false);
			}
		});
	});


	/* Submit the Add Patron form via Javascript when displayed as a modal */
	$('#addPatronModal button[type="submit"]').click(function(event) {

		// prevent default events from firing
		event.preventDefault();
		event.stopPropagation();

		var save_button = $(this);
		var save_button_original_text = save_button.html();
		var errors = new Array;		// placeholder for error messages

		// clear previous error messages
		$('#addPatronFormWrapper .alert').remove();

		// check if any new patron inputs were filled out
		var filled_inputs = $('#addPatronFormWrapper input, #addPatronFormWrapper textarea').filter(function() {
			return $(this).val();
		});

		// create a variable to hold an individual patron data
		var new_patron = new Object;
		new_patron.first_name = $('#first_name').val();
		new_patron.last_name = $('#last_name').val();
		new_patron.description = $('#addPatronFormWrapper textarea').val();
		new_patron.card_number = $('#card_number').val();
		new_patron.user = $('#addPatronForm input[name="user"]').val();

		// provide feedback that the patron is being saved
		if (save_button.length) {
			save_button.html('Saving...');
			save_button.prop('disabled', true);
		}


		// submit the request to PatronApiController@store
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
					console.log($(this));
					if ($(this).attr('name') != 'user') {
						$(this).val('');
					}
				});

				// close the modal
				$('#addPatronModal button[data-dismiss="modal"]:first').click();
			},
			error:function(response) {
				switch (response.status) {
					case 422:    // validation errors
						$.each(response.responseJSON, function() {
							errors.push($.parseHTML(`<div class="alert alert-danger">${this[0]}</div>`));
						});
						break;
					default:
						errors.push('An unspecified error occurred.')
						break;
				}
			},
		});

		if (errors.length) {
			$.each(errors, function() {
				var error = $.parseHTML(`<div class="alert alert-danger">${this}</div>`);
				$('#addPatronFormWrapper').prepend(error);
			});
		}

		// reset the save button
		save_button.html(save_button_original_text);
		save_button.prop('disabled', false);
	});


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


/***********************************
 Helper Functions
***********************************/
function buildAssociatedPatronsDropdown(){
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
		// add the patron to the associated-patrons dropdown
		var associatedPatronsDropdown = $('#associatedPatrons');
		associatedPatronsDropdown.find('option').remove();

		// create and add an option to the dropdown for each patron
		$.each(patrons, function(index, patron) {
			

			// https://select2.org/programmatic-control/add-select-clear-items
			var select2_data = {
				id: patron.id,
				text: patron.name,
			};

			var option = new Option(select2_data.text, select2_data.id, false, false);
			if (associatedPatronsDropdown.hasClass('select2-hidden-accessible')) {
				associatedPatronsDropdown.select2('destroy');
			}
			associatedPatronsDropdown.append(option).trigger('change');
			associatedPatronsDropdown.select2();
		});
	}
}
