<?php
/*
 * @package Theme Options
 */

// Add the JavaScript for Codepress (Code Snippet Editor with Highlighting)
function theme_options_codepress() {
	echo "<script src='" . THEME_OPTIONS_URL . "library/js/codepress/codepress.js' type='text/javascript'></script>";
}

// Toggle Options Sections
if (!function_exists('togglebox')) {
function togglebox() {
	echo "<script type='text/javascript'>
//<![CDATA[
jQuery(document).ready( function() {
	jQuery('.postbox').addClass('closed');
	jQuery('.postbox h3').not('.postbox h3 select').click( function() {
		jQuery(jQuery(this).parent().get(0)).toggleClass('closed');
		jQuery(jQuery(this).parent().get(1)).toggleClass('closed');
	});

	jQuery('.color_member').click(function () {
		var color = jQuery(this).attr('title');
		jQuery(jQuery(this).parent().prev()).val(color);
	});
});
//]]>
</script>";
}
}

?>
