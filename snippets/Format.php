<?php
/*
name: Format
author: Dan Cole
url: http://dan-cole.com/
description: Change the font family and font size of your whole site or key elements 
tags: font, format, font family, font size
*/

add_option('format_snippet_elements', 'html; h1; h2; h3; h4; p; blockquote; pre; code; a');

function format_snippet_page() {
	
?>
	<div class='wrap'>
	<h2><?php _e('Format Text', 'theme-options'); ?></h2>
	<p><?php _e('The content of your site is wrapped in HTML tags and labeled with style names. Using CSS, this content can be styled / formatted. Use the textbox below to add/delete style name, each of which will have a tab listed below that. Under each tab, fill as may of the fields below as you want to change the look of that element within your site. If the style name is a class, add a . (period) in front of the name. If the style name is an id, add a # (hash) in fron of the name. If the style name is an HTML tag, then nothing else is required. ', 'theme-options'); ?></p>
	<form name='options-page' method='post' action='themes.php?page=format_snippet_page'>
	<input type="textbox" name="format_snippet_elements" value="<?php echo get_option('format_snippet_elements'); ?>" class="listbox"/>
	<p class='submit'>
		<input type='hidden' name='action' value='save' />
		<input type='submit' name='Submit' value="<?php _e('Save Options', 'theme-options') ?>" />
	</p>
	<div id='poststuff'>
		<?php
		$items_data = get_option('format_snippet_elements');
		$items = explode(";", $items_data);
		for ($i = 0; $i < count($items); $i++) {
			if (substr(trim($items[$i]), 0, 1) == '.') {
				$type = 'class_';
				$name = trim(trim($items[$i]), '.');
			}
			elseif (substr(trim($items[$i]), 0, 1) == '#') {
				$type = 'id_';
				$name = trim(trim($items[$i]), '#');
			}
			else {
				$type = 'tag_';
				$name = trim($items[$i]);
			}
			?>
			<div class="postbox">
			<h3><?php echo $items[$i]; ?></h3>
			<div class="inside">
			<table class="form-table" id="format_<?php echo $type . $name; ?>_table">
				<?php format_snippet_settings($name, $type); ?>
			</table>
			</div>
			</div><!-- End div class='postbox' -->
			<?php
		}
		?>
	</div><!-- End div id='poststuff' -->
	<p class='submit'>
		<input type='hidden' name='action' value='save' />
		<input type='submit' name='Submit' value="<?php _e('Save Options', 'theme-options') ?>" />
	</p>
	</form>
	</div><!-- End div class='wrap' -->
<?php

	return array($favicon_name);
}

function attach_format_snippet_page() {
	include_once THEME_OPTIONS_DIR . 'library/js/backend.php';
	add_action('admin_head', 'togglebox');
	add_thickbox();
	add_theme_page('Format Font', 'Format Font', 8, format_snippet_page, format_snippet_page);
}
add_action('admin_menu', 'attach_format_snippet_page');

function format_snippet() {
	$items_data = get_option('format_snippet_elements');
	$items = explode(";", $items_data);
?>
<style>
<?php
	for ($i = 0; $i < count($items); $i++) {
		if (substr(trim($items[$i]), 0, 1) == '.') {
			$type = 'class_';
			$name = trim(trim($items[$i]), '.');
		}
		elseif (substr(trim($items[$i]), 0, 1) == '#') {
			$type = 'id_';
			$name = trim(trim($items[$i]), '#');
		}
		else {
			$type = 'tag_';
			$name = trim($items[$i]);
		}

		echo $items[$i] . " { ";
			if (get_option($type . $name . '_font-family') != NULL ) {
				echo "font-family: " . get_option($type . $name . '_font-family') . "; ";
			}
			if (get_option($type . $name . '_font-size') != NULL ) {
				echo "font-size: " . get_option($type . $name . '_font-size') . "em; ";
			}
			if (get_option($type . $name . '_font-weight') != NULL ) {
				echo "font-weight: " . get_option($type . $name . '_font-weight') . "; ";
			}
			if (get_option($type . $name . '_font-style') != NULL ) {
				echo "font-style: " . get_option($type . $name . '_font-style') . "; ";
			}
			if (get_option($type . $name . '_line-height') != NULL ) {
				echo "line-height: " . get_option($type . $name . '_line-height') . "; ";
			}

			// Border
			if (get_option($type . $name . '_border-top_style') != NULL ) {
				echo "border-top: " . get_option($type . $name . '_border-top_width') . " " . get_option($type . $name . '_border-top_style') . " " . get_option($type . $name . '_border-top_color') . "; ";
			}
			if (get_option($type . $name . '_border-right_style') != NULL ) {
				echo "border-right: " . get_option($type . $name . '_border-right') . " " . get_option($type . $name . '_border-right_style') . " " . get_option($type . $name . '_border-right_color') . "; ";
			}
			if (get_option($type . $name . '_border-bottom_style') != NULL ) {
				echo "border-bottom: " . get_option($type . $name . '_border-bottom') . " " . get_option($type . $name . '_border-bottom_style') . " " . get_option($type . $name . '_border-bottom_color') . "; ";
			}
			if (get_option($type . $name . '_border-left_style') != NULL ) {
				echo "border-left: " . get_option($type . $name . '_border-left') . " " . get_option($type . $name . '_border-left_style') . " " . get_option($type . $name . '_border-left_color') . "; ";
			}

			// Padding
			if (get_option($type . $name . '_padding-top') != NULL ) {
				echo "padding-top: " . get_option($type . $name . '_padding-top') . "%; ";
			}
			if (get_option($type . $name . '_padding-right') != NULL ) {
				echo "padding-right: " . get_option($type . $name . '_padding-right') . "%; ";
			}
			if (get_option($type . $name . '_padding-bottom') != NULL ) {
				echo "padding-bottom: " . get_option($type . $name . '_padding-bottom') . "%; ";
			}
			if (get_option($type . $name . '_padding-left') != NULL ) {
				echo "padding-left: " . get_option($type . $name . '_padding-left') . "%; ";
			}

			// Margin
			if (get_option($type . $name . '_margin-top') != NULL ) {
				echo "margin-top: " . get_option($type . $name . '_margin-top') . "%; ";
			}
			if (get_option($type . $name . '_margin-right') != NULL ) {
				echo "margin-right: " . get_option($type . $name . '_margin-right') . "%; ";
			}
			if (get_option($type . $name . '_margin-bottom') != NULL ) {
				echo "margin-bottom: " . get_option($type . $name . '_margin-bottom') . "%; ";
			}
			if (get_option($type . $name . '_margin-left') != NULL ) {
				echo "margin-left: " . get_option($type . $name . '_margin-left') . "%; ";
			}

			do_action('format_snippet_css', $name, $type);
		echo " }\n";
	}
?>

</style>
<?php
}
add_action( "wp_head", 'format_snippet' );

function format_snippet_options($list, $name = NULL) {
	if ($name == 'Format' || $name == NULL) {
		$items_data = get_option('format_snippet_elements');
		$items = explode(";", $items_data);

		$fields = array('_font-family', '_font-size', '_font-weight', '_font-style', '_line-height', '_border-top', '_border-right', '_border-bottom', '_border-left', '_padding-top', '_padding-right', '_padding-bottom', '_padding-left', '_margin-top', '_margin-right', '_margin-bottom', '_margin-left');

		for ($i = 0; $i < count($items); $i++) {
			for ($f = 0; $f < count($fields); $f++) {
				$list[] = $items[$i] . $fields;
			}
		}
	}
	return $list;
}
add_filter('snippet_options_list', 'format_snippet_options');

function format_snippet_settings($name, $type) {
?>
	<tr valign="top">
		<td colspan="2">
			<h4><?php _e('Font', 'theme-options'); ?></h4>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_font-family'; ?>"><?php echo _e('Font-Family', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_font-family'; ?>" value="<?php echo get_option($type . $name . '_font-family'); ?>" class="font-family regular-text" />
			<span class="setting-description"><?php _e('Needs to be a list of font family names and/or generic family names. Field will autocomplete.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_font-size'; ?>"><?php echo _e('Font-Size', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_font-size'; ?>" value="<?php echo get_option($type . $name . '_font-size'); ?>" class="small-text" /><b>em</b>. 
			<span class="setting-description"><?php _e('Units are <b>em</b>, which is a multipling scale size, based on the inherited font size.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_font-weight'; ?>"><?php echo _e('Font-Weight', 'theme-options'); ?></label></th>
		<td>
			<select name="<?php echo $type . $name . '_font-weight'; ?>">
					<?php $value = get_option($type . $name . '_font-weight'); ?>
					<option value=""<?php echo ($value == '') ? " selected='selected'" : ""; ?>><?php echo _e('Inherit', 'theme-options'); ?></option>
					<option value="normal"<?php echo ($value == 'normal') ? " selected='selected'" : ""; ?>><?php echo _e('normal', 'theme-options'); ?></option>
					<option value="bold"<?php echo ($value == 'bold') ? " selected='selected'" : ""; ?>><?php echo _e('bold', 'theme-options'); ?></option>
			</select>
			<span class="setting-description"><?php _e('Changes the thickness of the letters.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_font-style'; ?>"><?php echo _e('Font-Style', 'theme-options'); ?></label></th>
		<td>
			<select name="<?php echo $type . $name . '_font-style'; ?>">
					<?php $value = get_option($type . $name . '_font-style'); ?>
					<option value=""<?php echo ($value == '') ? " selected='selected'" : ""; ?>><?php echo _e('Inherit', 'theme-options'); ?></option>
					<option value="normal"<?php echo ($value == 'normal') ? " selected='selected'" : ""; ?>><?php echo _e('normal', 'theme-options'); ?></option>
					<option value="italic"<?php echo ($value == 'italic') ? " selected='selected'" : ""; ?>><?php echo _e('italic', 'theme-options'); ?></option>
					<option value="oblique"<?php echo ($value == 'oblique') ? " selected='selected'" : ""; ?>><?php echo _e('oblique', 'theme-options'); ?></option>
			</select>
			<span class="setting-description"><?php _e('', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_line-height'; ?>"><?php echo _e('Line-Height', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_line-height'; ?>" value="<?php echo get_option($type . $name . '_line-height'); ?>" class="small-text" />
			<span class="setting-description"><?php _e('Sets the distance between lines. Can be <b>normal</b>, number, length, or percentage.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2">
			<hr />
			<h4><?php _e('Border', 'theme-options'); ?></h4>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_border-top'; ?>"><?php echo _e('Border Top', 'theme-options'); ?></label></th>
		<td>
			<select title="<?php _e('Width', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-top_width'; ?>">
				<option<?php echo (get_option($type . $name . '_border-top_width') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-top_width') == 'thin') ? ' selected="selected"' : ''; ?> value="thin"><?php _e('Thin', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_width') == 'medium') ? ' selected="selected"' : ''; ?> value="medium"><?php _e('Medium', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_width') == 'thick') ? ' selected="selected"' : ''; ?> value="thick"><?php _e('Thick', 'theme-options'); ?></option>
			</select>
			<select title="<?php _e('Style', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-top_style'; ?>">
				<option<?php echo (get_option($type . $name . '_border-top_style') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'hidden') ? ' selected="selected"' : ''; ?> value="hidden"><?php _e('Hidden', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'dotted') ? ' selected="selected"' : ''; ?> value="dotted"><?php _e('Dotted', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'dashed') ? ' selected="selected"' : ''; ?> value="dashed"><?php _e('Dashed', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'solid') ? ' selected="selected"' : ''; ?> value="solid"><?php _e('Solid', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'double') ? ' selected="selected"' : ''; ?> value="double"><?php _e('Double', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'groove') ? ' selected="selected"' : ''; ?> value="groove"><?php _e('Groove', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'ridge') ? ' selected="selected"' : ''; ?> value="ridge"><?php _e('Ridge', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'inset') ? ' selected="selected"' : ''; ?> value="inset"><?php _e('Inset', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-top_style') == 'outset') ? ' selected="selected"' : ''; ?> value="outset"><?php _e('Outset', 'theme-options'); ?></option>
			</select>
			<input type="text" name="<?php echo $type . $name . '_border-top_color'; ?>" value="<?php echo get_option($type . $name . '_border-top_color'); ?>" class="medium-text" title="<?php _e('Color', 'theme-options'); ?>" /><?php do_action('color_input', $type . $name . '_border-top_color'); ?>
			<span class="setting-description"><?php _e('Sets the top border of the element.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_border-right'; ?>"><?php echo _e('Border Right', 'theme-options'); ?></label></th>
		<td>
			<select title="<?php _e('Width'); ?>" name="<?php echo $type . $name . '_border-right_width'; ?>">
				<option<?php echo (get_option($type . $name . '_border-right_width') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-right_width') == 'thin') ? ' selected="selected"' : ''; ?> value="thin"><?php _e('Thin', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_width') == 'medium') ? ' selected="selected"' : ''; ?> value="medium"><?php _e('Medium', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_width') == 'thick') ? ' selected="selected"' : ''; ?> value="thick"><?php _e('Thick', 'theme-options'); ?></option>
			</select>
			<select title="<?php _e('Style'); ?>" name="<?php echo $type . $name . '_border-right_style'; ?>">
				<option<?php echo (get_option($type . $name . '_border-right_style') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'hidden') ? ' selected="selected"' : ''; ?> value="hidden"><?php _e('Hidden', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'dotted') ? ' selected="selected"' : ''; ?> value="dotted"><?php _e('Dotted', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'dashed') ? ' selected="selected"' : ''; ?> value="dashed"><?php _e('Dashed', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'solid') ? ' selected="selected"' : ''; ?> value="solid"><?php _e('Solid', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'double') ? ' selected="selected"' : ''; ?> value="double"><?php _e('Double', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'groove') ? ' selected="selected"' : ''; ?> value="groove"><?php _e('Groove', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'ridge') ? ' selected="selected"' : ''; ?> value="ridge"><?php _e('Ridge', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'inset') ? ' selected="selected"' : ''; ?> value="inset"><?php _e('Inset', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-right_style') == 'outset') ? ' selected="selected"' : ''; ?> value="outset"><?php _e('Outset', 'theme-options'); ?></option>
			</select>
			<input type="text" name="<?php echo $type . $name . '_border-right_color'; ?>" value="<?php echo get_option($type . $name . '_border-right_color'); ?>" class="medium-text" title="<?php _e('Color', 'theme-options'); ?>" /><?php do_action('color_input', $type . $name . '_border-right_color'); ?>
			<span class="setting-description"><?php _e('Sets the right border of the element.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_border-bottom'; ?>"><?php echo _e('Border Bottom', 'theme-options'); ?></label></th>
		<td>
			<select title="<?php _e('Width', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-bottom_width'; ?>">
				<option<?php echo (get_option($type . $name . '_border-bottom_width') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_width') == 'thin') ? ' selected="selected"' : ''; ?> value="thin"><?php _e('Thin', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_width') == 'medium') ? ' selected="selected"' : ''; ?> value="medium"><?php _e('Medium', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_width') == 'thick') ? ' selected="selected"' : ''; ?> value="thick"><?php _e('Thick', 'theme-options'); ?></option>
			</select>
			<select title="<?php _e('Style', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-bottom_style'; ?>">
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'hidden') ? ' selected="selected"' : ''; ?> value="hidden"><?php _e('Hidden', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'dotted') ? ' selected="selected"' : ''; ?> value="dotted"><?php _e('Dotted', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'dashed') ? ' selected="selected"' : ''; ?> value="dashed"><?php _e('Dashed', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'solid') ? ' selected="selected"' : ''; ?> value="solid"><?php _e('Solid', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'double') ? ' selected="selected"' : ''; ?> value="double"><?php _e('Double', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'groove') ? ' selected="selected"' : ''; ?> value="groove"><?php _e('Groove', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'ridge') ? ' selected="selected"' : ''; ?> value="ridge"><?php _e('Ridge', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'inset') ? ' selected="selected"' : ''; ?> value="inset"><?php _e('Inset', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-bottom_style') == 'outset') ? ' selected="selected"' : ''; ?> value="outset"><?php _e('Outset', 'theme-options'); ?></option>
			</select>
			<input type="text" name="<?php echo $type . $name . '_border-bottom_color'; ?>" value="<?php echo get_option($type . $name . '_border-bottom_color'); ?>" class="medium-text" title="<?php _e('Color', 'theme-options'); ?>" /><?php do_action('color_input', $type . $name . '_border-bottom_color'); ?>
			<span class="setting-description"><?php _e('Sets the bottom border of the element.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_border-left'; ?>"><?php echo _e('Border Left', 'theme-options'); ?></label></th>
		<td>
			<select title="<?php _e('Width', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-left_width'; ?>">
				<option<?php echo (get_option($type . $name . '_border-left_width') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-left_width') == 'thin') ? ' selected="selected"' : ''; ?> value="thin"><?php _e('Thin', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_width') == 'medium') ? ' selected="selected"' : ''; ?> value="medium"><?php _e('Medium', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_width') == 'thick') ? ' selected="selected"' : ''; ?> value="thick"><?php _e('Thick', 'theme-options'); ?></option>
			</select>
			<select title="<?php _e('Style', 'theme-options'); ?>" name="<?php echo $type . $name . '_border-left_style'; ?>">
				<option<?php echo (get_option($type . $name . '_border-left_style') == '') ? ' selected="selected"' : ''; ?> value=""></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'hidden') ? ' selected="selected"' : ''; ?> value="hidden"><?php _e('Hidden', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'dotted') ? ' selected="selected"' : ''; ?> value="dotted"><?php _e('Dotted', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'dashed') ? ' selected="selected"' : ''; ?> value="dashed"><?php _e('Dashed', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'solid') ? ' selected="selected"' : ''; ?> value="solid"><?php _e('Solid', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'double') ? ' selected="selected"' : ''; ?> value="double"><?php _e('Double', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'groove') ? ' selected="selected"' : ''; ?> value="groove"><?php _e('Groove', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'ridge') ? ' selected="selected"' : ''; ?> value="ridge"><?php _e('Ridge', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'inset') ? ' selected="selected"' : ''; ?> value="inset"><?php _e('Inset', 'theme-options'); ?></option>
				<option<?php echo (get_option($type . $name . '_border-left_style') == 'outset') ? ' selected="selected"' : ''; ?> value="outset"><?php _e('Outset', 'theme-options'); ?></option>
			</select>
			<input type="text" name="<?php echo $type . $name . '_border-left_color'; ?>" value="<?php echo get_option($type . $name . '_border-left_color'); ?>" class="medium-text" title="<?php _e('Color'); ?>" /><?php do_action('color_input', $type . $name . '_border-left_color'); ?>
			<span class="setting-description"><?php _e('Sets the left border of the element.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2">
			<hr />
			<h4><?php _e('Padding', 'theme-options'); ?></h4>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_padding-top'; ?>"><?php echo _e('Padding Top', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_padding-top'; ?>" value="<?php echo get_option($type . $name . '_padding-top'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the inside space between the border and element content.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_padding-right'; ?>"><?php echo _e('Padding Right', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_padding-right'; ?>" value="<?php echo get_option($type . $name . '_padding-right'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the inside space between the border and element content.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_padding-bottom'; ?>"><?php echo _e('Padding Bottom', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_padding-bottom'; ?>" value="<?php echo get_option($type . $name . '_padding-bottom'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the inside space between the border and element content.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_padding-left'; ?>"><?php echo _e('Padding Left', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_padding-left'; ?>" value="<?php echo get_option($type . $name . '_padding-left'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the inside space between the border and element content.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2">
			<hr />
			<h4><?php _e('Margin', 'theme-options'); ?></h4>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_margin-top'; ?>"><?php echo _e('Margin Top', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_margin-top'; ?>" value="<?php echo get_option($type . $name . '_margin-top'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the outside space between the border and other elements.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_margin-right'; ?>"><?php echo _e('Margin Right', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_margin-right'; ?>" value="<?php echo get_option($type . $name . '_margin-right'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the outside space between the border and other elements.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_margin-bottom'; ?>"><?php echo _e('Margin Bottom', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_margin-bottom'; ?>" value="<?php echo get_option($type . $name . '_margin-bottom'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the outside space between the border and other elements.', 'theme-options'); ?></span>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row"><label for="<?php echo $type . $name . '_margin-left'; ?>"><?php echo _e('Margin Left', 'theme-options'); ?></label></th>
		<td>
			<input type="text" name="<?php echo $type . $name . '_margin-left'; ?>" value="<?php echo get_option($type . $name . '_margin-left'); ?>" class="small-text" /><b>%</b>
			<span class="setting-description"><?php _e('Sets the outside space between the border and other elements.', 'theme-options'); ?></span>
		</td>
	</tr>
	<?php do_action('format_snippet_settings', $name, $type);
}

function format_snippet_javascript() {
	$data = apply_filters('format_snippet_font_family_data', "");
?>
<link media="all" type="text/css" href="<?php echo THEME_OPTIONS_URL; ?>library/js/listbox/listbox.css" rel="stylesheet"/>
<script src="<?php echo THEME_OPTIONS_URL; ?>library/js/listbox/jquery.listbox.js"></script>
<script src="<?php echo THEME_OPTIONS_URL; ?>library/js/jquery-autocomplete/jquery.autocomplete.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".listbox").listbox();

		var font_family_data = '<?php echo $data; ?>'.split('; ');
		jQuery(".font-family").autocomplete(font_family_data);
	});
</script>
<?php
}
if ($_GET['page'] == 'format_snippet_page') {
	add_action('admin_head', 'format_snippet_javascript');
}

function format_snippet_default_fonts($data = "") {
	$data .= 'Arial, "Helvetica Neue", Helvetica, sans-serif; ';
	$data .= 'Cambria, Georgia, Times, "Times New Roman", serif; ';
	$data .= '"Courier New", Courier, monospace; ';
	$data .= 'Georgia, Times, "Times New Roman", serif; ';
	$data .= 'Helvetica, "Helvetica Neue", Arial, sans-serif; ';
	$data .= 'Verdana, Geneva, Tahoma, sans-serif; ';
	return $data;
}
add_filter('format_snippet_font_family_data', 'format_snippet_default_fonts', $data);

function format_snippet_post() {
	if (isset($_GET['page']) && $_GET['page'] == 'format_snippet_page' && isset($_REQUEST['action'])) { 
		$task = 'passed';
		if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) $action = $_REQUEST['action'];

		if ( in_array($_REQUEST['action_key'], array('activate-selected', 'deactivate-selected', 'delete-selected')) ) {
			$action = $_REQUEST['action_key'];
		}

		if( !empty($action) ) {
			switch( $action ) {
				case 'save':
					$other_fields = array('action', 'Submit');
					$other_fields = apply_filters('format_snippet_post_other_fields', $other_fields);
					foreach ($_POST as $key => $value) {
						if (!in_array($key, $other_fields)) {
							update_option($key, $value);
						}
					}
					break;
			}
		}
		return $task;
	}
}
add_action('theme_options_post', 'format_snippet_post');
?>
