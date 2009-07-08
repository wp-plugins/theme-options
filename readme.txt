=== Plugin Name ===
Contributors: dcole07
Donate link: http://dan-cole.com/
Tags: Code, Manager, Extension, Theme, Images, Comments, CSS, Formatting
Requires at least: 2.8
Tested up to: 2.8.1
Stable tag: 0.3

This plugin manages theme customizations.

== Description ==

This plugin manages theme customization. Everything is managed in the WordPress Backend. Code snippets can be activated, copied, created, deactivated, deleted, edited, and uploaded.

== Installation ==

1. Upload the `theme-options` folder and its contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. To use the plugin, go to 'Theme Options Panel' under the 'Appearance' menu in WordPress.
1. Activate the snippets you wish to use on your site.
1. Some code snippets may place options on the 'Snippet Options' page or create their own page.

== Features ==

1. Management of code in file and database form.
1. Backend code editor with code highlighting.
1. Hook Converting for code snippets.
1. Activate, copy, create, deactivate, delete, edite, and upload code.
1. Turns off code snippets that cause fatal errors.
1. Filtering of code snippets by status, author, storage type, and tags.
1. Bulk activate, deactivate, and delete.
1. Database storage of snippets, which is great for WPMU.
1. Pre-packed code snippets: theme image management, favicon, comment highlighting, custom background & logo header, author section in post, and Element formatting. 

== Frequently Asked Questions ==

= Why don't the pre-installed code snippets work? =

Every code snippet needs to use WordPress' Hook actions and filters, but if the hook is not within your theme, these snippets will have no place to attach to.

= Why are there no snippets listed under Theme Options Panel? =

The default snippets are located in a the folder `../wp-content/plugins/theme-options/snippets`. If that folder does not have the right permissions, it can't be read and your snippets can't be listed.

= What themes will the default snippets work for? =

They will work for any theme that uses the 'Standard Naming of Hooks in Themes', along with Thematic, Hybrid, and Prodigy. However, you can always write your own hook converter file and place it in the '.../plugins/theme-options/hooks/' folder. 

= What is required to create my own code snippet =

Your code will need to be in a PHP functions and placed in the correct location by 'add_action()' or 'add_filter()'. It is recommended that you fill in the default header, which is in a multi-line comment, but it can be done automatically for you. 

= Will my Wordpress break if I write code with errors? =

This plugin will automatically turn off any code snippet if it causes a fatal error, but not necessarily if it has an error or typo. Then on the second page load, that code snippet that failed will not be included and the 'Theme Options Panel' will highlight the failed code snippet.

= How can I insert image addresses into text boxes without copying and pasting the image address? =

By activating the Image Management snippet and using it to manage your images, you can do away with messing with URLs. Option pages that have thickbox running and have `<?php do_action('image_url_input', 'THE_TEXTBOX_NAME'); ?>` after each textbox that deals with images, will for those form fields have an image icon that allows you to select an image visually. 

== Screenshots ==

1. The Theme Options Plugin manages code, unlike other methods in which unrelated code gets mixed together.
2. Code snippets can be edited throught the backend and are saved in the database, rather than in a file.

== Changelog ==

= Version 0.3 (July 7, 2009) =
* Expanded the hooks that will be converted in Thematic and Hybrid
* Added Prodigy theme hooks to the converter.
* Changed how the converter does its job.

= Version 0.2 (June 13, 2009) =
* Fixed Image Management snippet's logo url inserter.
* Fixed Image Management snippet's inserting logo problem.
* Removed testing messages in snippet pannal POST.

= Version 0.1 (June 13, 2009) =
* (Mass) Activate, (mass) deactivate, upload, download, create, edit, mass delete, and copy Code Snippets.
* PHP code highlighting for backend editor using CodePress.
* Circuit breaker switch for code snippets.
* New code snippets: Image Management, Favicon, Post Author, Comments, Custom Header, and Format (site elements).
* Basic (limited) Hook Coverting: SNHT to Thematic, SNHT to Hybrid, Hybrid to SNHT, and Thematic to SNHT. 
* Snippets Panel Page and Snippet Options Page
* CSS file for any snippet to use.
