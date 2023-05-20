<?php

/**
 * Custom login header url
 */
add_filter('login_headerurl', 'tu_login_custom_link');

function tu_login_custom_link() {
    return HOME_URL;
}

/**
 * Custom admin login header title
 */
add_filter('login_headertitle', 'tu_change_title_on_logo');

function tu_change_title_on_logo() {
    return BLOG_NAME;
}


/**
 * Cutome admin logo
 */
add_action('login_enqueue_scripts', 'tu_change_wp_admin_logo');

function tu_change_wp_admin_logo() {
    ?>
    <style type="text/css">
        body.login{}
        body.login div#login{padding: 1% 0px 0px;}
        body.login div#login h1 a{background: url("<?php echo IMAGE_URL . '/logo.png'; ?>") no-repeat center center / 100%; width: 200px; height: 200px;}
    </style>
<?php
}

/**
 * Adding scripts and styles to admin panel
 * All your scripts and styles will be included in wp_head()
 */
add_action('admin_enqueue_scripts', 'tu_admin_enqueue_scripts_styles');

function tu_admin_enqueue_scripts_styles() {
    wp_enqueue_media();

    wp_enqueue_style('admin-style', TEMPLATE_URL . '/assets/stylesheets/css/admin.css');

    $wp_script_data = array(
        'ajaxurl' => ADMIN_AJAX_URL, // This will be deleted in next version
        'homeurl' => HOME_URL, // This will be deleted in next version
        'ADMIN_AJAX_URL' => ADMIN_AJAX_URL,
        'HOME_URL' => HOME_URL
    );

    wp_localize_script('scripts', 'wp_vars', $wp_script_data);
}

/**
 * Remove admin bar - Optional
 */
add_filter('show_admin_bar', '__return_false');


/**
 * Remove comment icon on admin bar
 */
add_action('wp_before_admin_bar_render', 'tu_remove_edit_comments_admin_bar');

function tu_remove_edit_comments_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

/**
 * Remove admin comment page
 */
add_action('admin_menu', 'tu_remove_admin_menu');

function tu_remove_admin_menu() {
    remove_menu_page('edit.php'); //Posts
    remove_menu_page('edit-comments.php');
}

/**
 * Remove admin comment support
 */
add_action('init', 'tu_remove_comment_support', 100);

function tu_remove_comment_support() {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
    remove_post_type_support('attachment', 'comments');
}


/**
 * Disable XMLRPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Disable auto update plugin
 */
add_filter('auto_update_plugin', '__return_true');

/**
 * Disable auto update theme
 */
add_filter('auto_update_theme', '__return_true');