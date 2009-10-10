<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @website http://dan-cole.com
 * @package 'Color Management' snippet for 'Theme Options', a WordPress plugin
 *
 */

if ($_GET['tab'] && $_GET['item']) {
	$root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
	require_once $root . "/wp-blog-header.php";
	global $wpdb;
	if ($_GET['item'] && $_GET['color']) {
		update_option($_GET['item'], $_GET['color']);
	}
	color_table('inserter');
?>
	<script type='text/javascript'>
	//<![CDATA[
	jQuery(document).ready( function() {
		jQuery(".inline a").click(function (e) {
			e.preventDefault();
			var color_value = jQuery(this).parent().attr('title');
			if (color_value == "Inherit") {
				var color_value = "";
			}
			jQuery("input[name='<?php echo $_GET['item']; ?>']").attr("value", color_value);
			if (color_value == "") {
				var color_value = "url(<?php echo THEME_SNIPPETS_URL; ?>Color_Management/color.png)";
			}
			jQuery("input[name='<?php echo $_GET['item']; ?>']").next().children().eq(0).css("background", color_value);
			tb_remove();
		});
	});
	//]]>
	</script>
	<?php
}	

?>
