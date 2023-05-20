<?php

/** Constants */
define('HELPERS', TEMPLATE_PATH.'/includes/helpers');
define('LIBRARIES', TEMPLATE_PATH.'/includes/libraries');
define('WP_LIBRARIES', TEMPLATE_PATH.'/includes/wp-libraries');
define('WP_CUSTOMIZATION', TEMPLATE_PATH.'/includes/wp-customization');
define('WP_EXTENSION', TEMPLATE_PATH.'/extension');


/** Helpers */
include_once(HELPERS . '/tu-helpers.php');
include_once(HELPERS . '/wp-helpers.php');

/** Wordpress Customization */
include_once(WP_CUSTOMIZATION . '/options.php');
include_once(WP_CUSTOMIZATION . '/admin.php');
include_once(WP_CUSTOMIZATION . '/enqueue.php');
include_once(WP_CUSTOMIZATION . '/languages.php');
include_once(WP_CUSTOMIZATION . '/mail.php');
include_once(WP_CUSTOMIZATION . '/menus.php');
include_once(WP_CUSTOMIZATION . '/security.php');
include_once(WP_CUSTOMIZATION . '/thumbnail-sizes.php');
include_once(WP_CUSTOMIZATION . '/upload.php');
include_once(WP_CUSTOMIZATION . '/widgets.php');

/** Remove admin bar - Optional */
add_filter('show_admin_bar', '__return_false');

/** Extension */
require_once(WP_EXTENSION . '/image.php');

/** Mulitiple language **/
