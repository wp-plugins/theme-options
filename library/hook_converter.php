<?php
/*
 * @author Dan Cole
 * @copyright 2009
 * @package Theme Options
 *
 */

if (!function_exists('merge_synonym_filters')) {
  function merge_synonym_filters() {
    global $synonym_filters, $wp_filter, $wp_current_filter;
    $tag = current($wp_current_filter);
    if (isset($synonym_filters[$tag]) && !isset($merged_filters[$tag])) {
      if ($synonym_filters[$tag] != FALSE) {
        foreach ((array)$synonym_filters[$tag] as $synonym) {
          if (isset($wp_filter[$synonym])) {
            foreach ((array)$wp_filter[$synonym] as $priority => $the_) {
              $wp_filter[$tag][$priority] = $the_;
            }
          }
        }
      }
    }
  }
  add_action('all', 'merge_synonym_filters');
}

if (!function_exists('add_filter_synonyms')) {
  function add_filter_synonyms($tags) {
    global $synonym_filters, $merged_filters;
    foreach ($tags as $tag) {
      if (isset($synonym_filters[$tag])) {
        $tags = array_merge((array)$tags, (array)$synonym_filters[$tag]);
      }
    }
    foreach ($tags as $tag) {
      foreach ($tags as $synonym) {
        if ($tag != $synonym) {
          $synonym_filters[$tag][$synonym] = $synonym;
        }
      }
      unset($merged_filters[$tag]);
    }
    return true;
  }

  function add_action_synonyms($tags) {
    return add_filter_synonyms($tags);
  }
}

function hook_converter() {
  $active_converters = array();
  $dir_handle = opendir(THEME_HOOKS_DIR);
	while (false !== ($file_name = readdir($dir_handle))) {
		if ( !in_array($file_name, array('.', '..', "", null)) && substr($file_name, -1) != "~" ) {
      $name = substr($file_name, 0, -4);
      if (get_option($name) == "yes") {
        $active_converters[] = THEME_HOOKS_DIR . $name . '.txt';
      }
    }
  }
  closedir($dir_handle);
  apply_filters('hook_converting_files', $active_converters);
  foreach ((array)$active_converters as $location) {
	  $fh = fopen($location, 'rb');
	  $file_data = fread($fh, filesize($location));
	  fclose($fh);
	  $equivalents = explode("\n", $file_data);
	  for ($equ = 0; $equ < count($equivalents); $equ++) {
		  if ($equivalents[$equ] != NULL && substr($equivalents[$equ], 0, 1) != "/") {
			  $line = str_replace("\t\t", "\t", $equivalents[$equ]);
        $line = str_replace("\t\t", "\t", $line);
			  $hook_names = explode("\t", $line);
        add_filter_synonyms($hook_names);
      }
    }
  }
}
add_action('init', 'hook_converter');

/*
// This function is no longer used.
function snippet_hook_converter($code) {
  $active_conveters = array();
  $dir_handle = opendir(THEME_HOOKS_DIR);
	while (false !== ($file_name = readdir($dir_handle))) {
		if ( !in_array($file_name, array('.', '..', "", null)) && substr($file_name, -1) != "~" ) {
      $name = substr($file_name, 0, -4);
      if (get_option($name) == "yes") {
        $active_converters[] = $name;
      }
    }
  }
  closedir($dir_handle);
  foreach ((array)$active_converters as $file_name) {
	  $location = THEME_HOOKS_DIR . $file_name . '.txt';
	  $fh = fopen($location, 'rb');
	  $file_data = fread($fh, filesize($location));
	  fclose($fh);
	  $equivalents = explode("\n", $file_data);
	  for ($equ = 0; $equ < count($equivalents); $equ++) {
		  if ($equivalents[$equ] != NULL && substr($equivalents[$equ], 0, 1) != "/") {
			  $line = str_replace("\t\t", "\t", $equivalents[$equ]);
			  $hook_name = explode("\t", $line);
        $code = str_replace(trim($hook_name[0]), trim($hook_name[1]), $code);
      }
    }
  }
  return $code;
}
//add_filter('execute_snippet_code', 'snippet_hook_converter');
*/
?>
