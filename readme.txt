=== Plugin Name ===
Contributors: dcole07
Donate link: http://dan-cole.com/
Tags: Code, Manager, Extension, Theme, Images, Comments, CSS, Formatting
Requires at least: 2.8
Tested up to: 2.8
Stable tag: 0.2

This plugin manages theme customizations.

== Description ==

This plugin manages theme customization. Everything is managed in the WordPress Backend. Code snippets can be activated, copied, created, deactivated, deleted, edited, and uploaded.

== Installation ==

1. Upload the `theme-options` folder and its contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To use the plugin, go to 'Theme Options Panel' under the 'Appearance' menu in WordPress
1. Some code snippets may place options on the 'Snippet Options' page or create their own page.

== Features ==

1. Management of code in file and database form.
1. Backend code editor with code highlighting.
1. Hook Converting for code snippets.
1. Activate, copy, create, deactivate, delete, edite, and upload code.
1. Turns off code snippets that cause fatal errors.
1. Filtering of code snippets by status, author, storage type, and tags.
1. Bulk activate, deactivate, and delete.
1. Pre-packed code snippets: theme image management, favicon, comment highlighting, custom background & logo header, author section in post, and Element formatting. 

== Frequently Asked Questions ==

= Why don't the pre-installed code snippets work? =

Every code snippet needs to use WordPress' Hook actions and filters, but if the hook is not within your theme, these snippets will have no place to attach to.

= What themes will the default snippets work for? =

They will work for any theme that uses the 'Standard Naming of Hooks in Themes', along with Thematic and Hybrid. However, you can always write your own hook converter file and place it in the '.../plugins/theme-options/hooks/' folder. 

= What is required to create my own code snippet =

Your code will need to be in a PHP functions and placed in the correct location by 'add_action()' or 'add_filter()'. It is recommended that you fill in the default header, which is in a multi-line comment, but it can be done automatically for you. 

= Will my Wordpress break if I write code with errors? =

This plugin will automatically turn off any code snippet if it causes a fatal error, but not necessarily if it has an error or typo. Then on the second page load, that code snippet that failed will not be included and the 'Theme Options Panel' will highlight the failed code snippet.

= How can I insert image addresses into text boxes without copying and pasting the image address? =

By activating the Image Management snippet and using it to manage your images, you can do away with messing with URLs. Option pages that have thickbox running and have `<?php do_action('image_url_input', 'THE_TEXTBOX_NAME'); ?>` after each textbox that deals with images, will for those form fields have an image icon that allows you to select an image visually. 
