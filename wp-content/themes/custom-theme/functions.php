<?php

/**
 * Define constants
 * These constants will be used globally
 */
define('BLOG_NAME', get_option('blogname'));
define('HOME_URL', home_url('/'));
define('TEMPLATE_URL', get_template_directory_uri());
define('TEMPLATE_PATH', get_template_directory());
define('ADMIN_AJAX_URL', admin_url('admin-ajax.php'));
define('IMAGE_URL', TEMPLATE_URL . '/assets/images');
define('ASSETS_URL', TEMPLATE_URL . '/assets');
define('NO_IMAGE_URL', IMAGE_URL.'/no-image.png');
define('TEXT_DOMAIN', 'tu');

/**
 * Constants for configuration
 */
define('FACEBOOK_APP_ID', '181095955692675');
// define('FACEBOOK_APP_SECRET', '92760d791a7047bf65536be984571780');

/**
 * Including core stuffs
 */
include_once(TEMPLATE_PATH . '/includes/init.php');

/**
 * Including post-types files
 * You can create more post-types if you need but you should use the structure of existed files
 */

include_once(TEMPLATE_PATH . '/post-types/article.php');
include_once(TEMPLATE_PATH . '/post-types/home.php');



