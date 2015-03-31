jQuery(document).ready(function ($) {
	$('#mklasen_submit_add_dynamic widget').on('click', function() {
		val = $('.select_dynamic widget_category').val();
		if (!val) {
			shortcode = '[dynamic widget]';
		} else {
			shortcode = '[dynamic widget category="'+val+'"]';
		}
		//Insert content
		parent.tinyMCE.activeEditor.setContent(parent.tinyMCE.activeEditor.getContent() + shortcode);
		//Close window
		parent.jQuery("#TB_closeWindowButton").click();
	});
});