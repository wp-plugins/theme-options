<?php
/*
 * @package Theme Options
 */

/*
Functions:
	activate_snippet()
	deactivate_snippet()
	add_snippet()
	delete_snippet()
	modify_snippet()
	copy_snippet()
	import_snippet()
	fetch_snippets()
	msort()
	execute_snippet()
	download_snippet()
	get_snippet_code()
*/
function activate_snippet($snippet) {
	$current = (array)get_option('active_snippets');
	if ($current[0] == FALSE) $current = array();
	if ( !in_array($snippet, $current) ) {
		$current[] = $snippet;
		sort($current);
		update_option('active_snippets', $current);
	}
}

function deactivate_snippet($snippet) {
	$new = array();
	$current = (array)get_option('active_snippets');
	if ( in_array($snippet, $current) ) {
		for ($e=0; $e<count($current); $e++) {
			if ($snippet != $current[$e]) $new[] = $current[$e]; 
		}
		sort($new);
		update_option('active_snippets', $new);
	}
}

function add_snippet($code) {
	$type = '.php';
	$titles = array('name');
	$end = -1 * strlen($type);
	$note_data = explode("\n", $code);
	$item = 0;
	for ($d=0; $d<count($note_data); $d++) {
		for ($n=0; $n<count($titles); $n++) {
			$start = strlen($titles[$n]);
			if ( strtolower(substr($note_data[$d], 0, $start)) == $titles[$n] ) {
				${$titles[$n]} = trim(substr($note_data[$d], $start+1));
				$items++;
				if ($items == count($titles)) break 2;
			}
		}
	}

	if ($name == NULL) {
		$name = 'snippet_' . date("YmdHi");
		$code = "<?php \n/*\nname: " . $name . "\n*/\n?>\n" . stripslashes($code);
		$code = str_replace("?>\n<?php", "", $code);
	}

	$snippets_list = (array)get_option('database_snippets');
	if (!in_array($name, $snippets_list)) {
		add_option('snippet_' . $name . '_code', stripslashes($code));
		add_option('snippet_' . $name . '_status', 'new');
		add_option('snippet_' . $name . '_switch', 'once');
		$snippets_list[] = $name;
		update_option('database_snippets', $snippets_list);
		return 'success';
	}
	else {
		$name = $name . '_' . date("YmdHi");
		add_option('snippet_' . $name . '_code', stripslashes($code));
		add_option('snippet_' . $name . '_status', 'new');
		add_option('snippet_' . $name . '_switch', 'once');
		$snippets_list[] = $name;
		update_option('database_snippets', $snippets_list);
		return 'success';
	}
}

function delete_snippet($name) {
	deactivate_snippet($name);

	$snippets_list = get_option('database_snippets');
	if (in_array($name, $snippets_list)) {
		$new = array();
		for ($e=0; $e<count($snippets_list); $e++) {
			if ($name != $snippets_list[$e]) $new[] = $snippets_list[$e];
		}
		sort($new);
		update_option('database_snippets', $new);

		$result = delete_option('snippet_' . $name . '_code');
	}
	else {
		if (file_exists(THEME_SNIPPETS_DIR . $name . '.php')) {
			$result = unlink(THEME_SNIPPETS_DIR . $name . '.php');
		}
		elseif (file_exists(THEME_SNIPPETS_DIR . $name . '/' . $name . '.php')) {
			$snippet_dir = THEME_SNIPPETS_DIR . $name . '/';
			$folder = dir($snippet_dir); 
			while($entry = $folder->read()) { 
				if ($entry!= "." && $entry!= "..") { 
					$result = unlink($snippet_dir . $entry);
				} 
			} 
			$folder->close(); 
			rmdir($snippet_dir);
		}
	}
	$snippet_options = apply_filters('snippet_options_list', array(), $name);
	foreach ($snippet_options as $option) {
		delete_option($option);
	}
	delete_option('snippet_' . $name . '_status');
	delete_option('snippet_' . $name . '_switch');
	return $result;
}

function modify_snippet($name, $code) {
	$current = (array)get_option('active_snippets');
	if ( in_array($name, $current) ) $status = 'active';
	$old_code = get_snippet_code($name); // Save old code
	delete_snippet($name); // Remove old code
	$result = add_snippet($code); // Add new code
	if ($status == 'active') {
		activate_snippet($name);
	}
	if ($result == 'failed') {
		add_snippet($old_code); // Restore on Failure
		return 'failed';
	}
	else {
		return 'success';
	}
}

function copy_snippet($name) {
	$code = get_snippet_code($name);
	$code_lines = explode("\n", $code);
	$item = 0;
	for ($d=0; $d<count($code_lines); $d++) {
		if ( strtolower(substr($code_lines[$d], 0, 4)) == 'name' ) {
			$new_name = $name;
			do {
				$new_name = $new_name . '_copy';
				$exists = get_snippet_code($new_name); // Returns 'failed' if there is no code
			} while ($exists != 'failed'); // Failed means were free to use the name
			$code_lines[$d] = str_replace($name, $new_name, $code_lines[$d]);
			break;
		}
	}
	$code = implode("\n", $code_lines);
	add_snippet($code);
}

function fetch_snippets($sortby = 'name', $filters = array()) {
	$active_snippets = get_option('active_snippets');
	$info = array();
	$type = '.php';
	$titles = array('name', 'author', 'url', 'description', 'tags');
	$end = -1 * strlen($type);

	// Info from folder
	$dir = THEME_SNIPPETS_DIR;
	$dir_handle = opendir($dir);
	if ($dir_handle != FALSE) {
		while (false !== ($file_name = readdir($dir_handle))) {
			if ( !in_array($file_name, array('.', '..', "", null)) ) {
				$requirements = 0;
				if ( strtolower(substr($file_name, $end)) == $type ) {
					$requirements = 1;
					$location = $dir . $file_name;
				}
				elseif ( filetype($dir . $file_name) == "dir" ) {
					$location = $dir . "/" . $file_name . "/" . $file_name . $type;
					if (file_exists($location)) {
						$requirements = 1;
					}
				}
				if ($requirements == 1) {
					$name = NULL; 
					$status = NULL; 
					$author = NULL; 
					$url = NULL; 
					$description = NULL; 
					$tags = NULL; 
					$switch = NULL; 

					$note_handle = fopen($location, 'rb');
					$note_info = fread($note_handle, filesize($location));
					fclose($note_handle);
					$note_data = explode("\n", $note_info);
					$item = 0;
					for ($d=0; $d<count($note_data); $d++) {
						for ($n=0; $n<count($titles); $n++) {
							$start = strlen($titles[$n]);
							if ( strtolower(substr($note_data[$d], 0, $start+1)) == $titles[$n] . ":" ) {
								${$titles[$n]} = trim(substr($note_data[$d], $start+1));
								$items++;
								if ($items == count($titles)) break 2;
							}
						}
					}
					if ($name != NULL) {
						$switch = get_option('snippet_' . $name . '_switch'); // The circuit breaker switch
						if ($switch == FALSE || $switch == "") {
							$switch = 'once'; // on, off, or once
							update_option('snippet_' . $name . '_switch', $switch);
						}
						$status = get_option('snippet_' . $name . '_status'); 
						$info[] = array('name'=>$name, 'status'=>$status, 'author'=>$author, 'url'=>$url, 'description'=>$description, 'tags'=>$tags, 'switch'=>$switch, 'type'=>'File');
					}
				}
			}
		}
		closedir($dir_handle);
	}

	// Info from database
	$snippets = get_option('database_snippets');
	for ($s=0; $s<count($snippets); $s++) {
		$name = NULL; 
		$status = NULL; 
		$author = NULL; 
		$url = NULL; 
		$description = NULL; 
		$tags = NULL; 
		$switch = NULL; 

		$code = get_option('snippet_' . $snippets[$s] . '_code');
		$note_data = explode("\n", $code);
		$item = 0;
		for ($d=0; $d<count($note_data); $d++) {
			for ($n=0; $n<count($titles); $n++) {
				$start = strlen($titles[$n]);
				if ( strtolower(substr($note_data[$d], 0, $start)) == $titles[$n] ) {
					${$titles[$n]} = trim(substr($note_data[$d], $start+1));
					$items++;
					if ($items == count($titles)) break 2;
				}
			}
		}
		$status = get_option('snippet_' . $name . '_status'); 
		$switch = get_option('snippet_' . $name . '_switch'); // The circuit breaker switch

		if ($name != NULL && $code != NULL && $code != FALSE) {
			$info[] = array('name'=>$name, 'status'=>$status, 'author'=>$author, 'url'=>$url, 'description'=>$description, 'tags'=>$tags, 'switch'=>$switch, 'type'=>'Database');
		}
	}

	// Sort & Filter Snippets
	$info = msort($info, $sortby);
	$table_data = filter_data($info, $filters);
	return $table_data;
}

function msort($array, $id = "name") {
	$temp_array = array();
	while(count($array)>0) {
		$lowest_id = 0;
		$index=0;
		foreach ($array as $item) {
			if (isset($item[$id]) && $array[$lowest_id][$id]) {
				if ($item[$id]<$array[$lowest_id][$id]) {
					$lowest_id = $index;
				}
			}
			$index++;
		}
		$temp_array[] = $array[$lowest_id];
		$array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
	}
	return $temp_array;
}

function filter_data($info, $filters) {
	$new = array();
	$authors_list = array();
	$tags_list = array();
	for ($r = 0; $r < count($info); $r++) {
		$active_snippets = (array)get_option('active_snippets');
		$add_status = FALSE;
		$add_author = FALSE;
		$add_type = FALSE;
		$add_tags = FALSE;
		if ($filters['status'] == "active" && in_array($info[$r]['name'], $active_snippets) || $filters['status'] == "") $add_status = TRUE; 
		if ($filters['status'] == "deactive" && !in_array($info[$r]['name'], $active_snippets) || $filters['status'] == "") $add_status = TRUE; 
		if ($info[$r]['author'] == $filters['author'] || $filters['author'] == "") $add_author = TRUE;
		if ($info[$r]['type'] == $filters['type'] || $filters['type'] == "") $add_type = TRUE;
		$tags = explode(",", $info[$r]['tags']);
		array_walk($tags, 'theme_option_trim_value'); // Run each tag through the trim_value function.
		if (in_array($filters['tags'], $tags) || $filters['tags'] == "") $add_tags = TRUE;
		// Add row into the out going list if it matches.
		if ($add_status == TRUE && $add_author == TRUE && $add_type == TRUE && $add_tags == TRUE) $new[] = $info[$r];

		// Make a list of Authors
		if (!in_array($info[$r]['author'], $authors_list) && !empty($info[$r]['author'])) $authors_list[] = $info[$r]['author'];

		// Make a list of Tags
		foreach ($tags as $tag) {
			if (!in_array($tag, $tags_list) && !empty($tag)) $tags_list[] = $tag;
		}
	}
	sort($authors_list);
	sort($tags_list);
	return array('snippets'=>$new, 'authors'=>$authors_list, 'tags'=>$tags_list);
}

function theme_option_trim_value(&$value) { 
		$value = trim($value); 
}

function execute_snippet($name) {
	$file_name = str_replace(" ", "_", $name);
	$status = get_option('snippet_' . $name . '_status');
	$switch = get_option('snippet_' . $name . '_switch');
	if ($status != 'failed') {
		$code = NULL;

		// Setup the Ciruit Breaker as though the code will fail.
		if ($switch != 'off') update_option('snippet_' . $name . '_status', 'failed');

		// Find where this snippet of code is and get it.
		$dir = THEME_SNIPPETS_DIR;
		$snippets = (array)get_option('database_snippets');
		if (in_array($name, $snippets)) {
			$code = stripslashes(get_option('snippet_' . $name . '_code'));
		}
		elseif (file_exists($dir . $file_name . '.php') && is_readable($dir . $file_name . '.php')) {
			$location = $dir . $file_name . '.php';
			$fh = fopen($location, 'rb');
			$code = fread($fh, filesize($location));
			fclose($fh);
		}
		elseif (file_exists($dir . $file_name . '/' . $file_name . '.php') && is_readable($dir . $file_name . '/' . $file_name . '.php')) {
			$location = $dir . $file_name . '/' . $file_name . '.php';
			$fh = fopen($location, 'rb');
			$code = fread($fh, filesize($location));
			fclose($fh);
		}
		else {
			// Something is wrong here...
			echo "<h1>Executing Snippet Error For: " . $name . "</h1>";
			echo "<p>The most likely cause it that the file name and name within the file don't have the same case or the file name has spaces in it.</p>";
		}

		// Run the code
		if ($code != NULL) {
			$code = apply_filters('execute_snippet_code', $code);
			ob_start();
			eval(' ?>' . $code . '<?php ');
			$output = ob_get_contents();
			ob_end_clean();
			echo $output;
		}

		// Correct the Ciruit Breaker because the code passed.
		if ($switch != 'off') update_option('snippet_' . $name . '_status', 'passed');
		// Turn off the Circuit Breaker Switch if asked.
		if ($switch == 'once') update_option('snippet_' . $name . '_switch', 'off');
	}
}

function download_snippet() { // Is included in /functions.php
	$name = $_GET['download'];
	$file_name = str_replace(" ", "_", $name);
	header('Content-Description: File Transfer');
	header("Content-Disposition: attachment; filename=$file_name.php");
	header('Content-Type: text/php; charset=' . get_option('blog_charset'), true);

	echo get_snippet_code($name);
	die();
}

function get_snippet_code($name) {
	// Find where this snippet of code is and display it.
	$file_name = str_replace(" ", "_", $name);
	$dir = THEME_SNIPPETS_DIR;
	$snippets = (array)get_option('database_snippets');
	if (in_array($name, $snippets)) {
		$code = stripslashes(get_option('snippet_' . $name . '_code'));
	}
	elseif (file_exists($dir . $file_name . '.php') && is_readable($dir . $file_name . '.php')) {
		$fh = fopen($dir . $file_name . '.php', 'r');
		$code = fread($fh, filesize($dir . $file_name . '.php'));
		fclose($fh);
	}
	elseif (file_exists($dir . $file_name . '/' . $file_name . '.php') && is_readable($dir . $file_name . '/' . $file_name . '.php')) {
		$fh = fopen($dir . $file_name . '/' . $file_name . '.php', 'r');
		$code = fread($fh, filesize($dir . $file_name . '/' . $file_name . '.php'));
		fclose($fh);
	}
	else {
		$code = 'failed';
	}
	return $code;
}

?>
