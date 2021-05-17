var editor = new MediumEditor('[editor="true"]');
editor.subscribe('blur', function(event, editable) {
	// console.log(editable.innerHTML);
	// console.log(editable.id);
	// console.log(event);
	// console.log(event.currentTarget);
	// console.log(event.currentTarget.activeElement);
	// console.log(editable.nodeType);
	// console.log(editable.getAttribute('data-inplaze-id'));
	// console.log(event);
	$.ajax('/include/test.php', {
		type: 'POST', 
		data: { id: editable.getAttribute('data-inplaze-id'), content: editable.innerHTML.trim() },
		success: function (data, status, xhr) {
			// $('.fbox-content').append('status: ' + status + ', data: ' + data);
			console.log(status, data);
		},
		error: function (jqXhr, textStatus, errorMessage) {
			console.log(errorMessage);
			// $('.fbox-content').append('Error' + errorMessage);
		}
	});
});