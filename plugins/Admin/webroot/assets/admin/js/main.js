$(document).ready(function() {
	$('#authMessage[class="message"]').wrap('<div class="alert alert-danger"></div>');
	// $('#flashMessage').wrap('<div class="alert alert-info"></div>');
	
	$('.form-control[required]').each(function() {
		$(this).prev().html($(this).prev().html() + ' *');
	});
});