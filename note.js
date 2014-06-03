
$(document).ready(function() {
	$('#add_note_btn').click(function() {
		$('input[type="submit"]').show();
		$('textarea').slideDown();
		$(this).hide();
		$('textarea').focus();
	});
	
});