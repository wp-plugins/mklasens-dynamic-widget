jQuery(document).ready(function ($) {
	$('.mklasens-dynamic widget .question').click(function() {
		id = $(this).attr('data-id');
		parent = $(this).parent();
		if ($(this).hasClass('active')) {
			$('.question[data-id='+id+']', parent).removeClass('active');
			$('.answer[data-id='+id+']', parent).slideUp(200);
		} else {
			$('.question[data-id='+id+']', parent).addClass('active');
			$('.answer[data-id='+id+']', parent).slideDown(350);
		}
	});
});