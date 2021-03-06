# WP Fuse
Fuse is a micro-framework for WordPress developed by and for the devs @ White Fire Web Design. 

License
----

MIT

Version
-------
0.0.14
* LIB\STRIPE : Lots of updates
* LIB\FORM : Fixed cc and bcc sending options
* LIB\FORM : Created 'mail' config in admin 
* LIB\FORM : Fixed table in admin when new fields created
* LIB\FORM : Added ability to send message
* Added Branding
* LIB\TEMPLATE : Added ability to set extension to template
* LIB\STRIPE : Return secret key `Stripe::get_secret_key()`
* LIB\STRIPE : Echo publishable key
* LIB\STRIPE : Add option to switch between Test and Live

0.0.13.patch1
* LIB\MAILCHIMP : Add subscribe and unsubscribe method
* LIB\FORM : Add form submission viewport move offset
* LIB\FORM : Add 'minLength' validation condition

0.0.13
* Added index.php
* LIB\FORM : Add 'match' validation condition

0.0.12.patch1
* LIB\FORM : Add Password Field

0.0.12
* LIB\FORM : Added Ajax Support
* LIB\FORM : Date order in form submissions table (admin)

0.0.11.patch1
* LIB\FORM : Fixed form file upload problem in Multisite

0.0.11
* LIB\FORM : Fixed 'undefined index' errors
* LIB\FORM : Added new field parameter 'display_label' allowing option to hide label on fields
* LIB\FORM : Added new field parameter 'label_email' allowing user to customise label output in email
* LIB\FORM : Added new field parameter 'label_classes' to specify label classes
* LIB\FORM : Added File support

0.0.10
* LIB\FORM : Added success 'script' option to [fuse-form *]
* Code refactoring

0.0.9
* Code refactoring

0.0.8
* Added argument 'level' to wp_nav_menu
* LESS : Added argument to accept vars
* LIB\FORM : Added call form via shortcode [fuse-form name=""]
* LIB\FORM : Added attributes 'method', 'action' and 'id' to form->config
* LIB\FORM : Added hidden input field 
* LIB\EMAIL : Added email class

0.0.7
* UTIL\LESS : Replaced library with less.php - https://github.com/oyejorge/less.php
* UTIL\LESS : Fixed changes in @import files does not trigger a new compile
* UTIL\LESS : Removed 'force' argument 

0.0.6
* LIB\FORM : fixed textarea not getting set classes
* LIB\FORM : added get_forms method
* LIB\FORM : updated form admin view to include saved database logs
* LIB\FORM : stopped logs from duplicating on save

0.0.5
* Updated general Dash to include list of supported Fuse modules
* Added db_table_exists function
* Added new Form module with basic save form to database function
* Updated MailChimp module with insert, update and remove methods
* Added mew function array_key_value_search()
* Added new function sort_terms_hierarchically()
* Added Welcome Panel

0.0.4
* Fixed all post types not showing in menu Archive Pages hotfix

0.0.3 
* Added lib for Popular Posts
* Added function get_primary_term() - requires YOAST SEO
