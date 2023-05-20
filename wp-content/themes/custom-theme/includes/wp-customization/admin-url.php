<?php
/**
 * Created by toanna at 5:05 PM 03/04/2017, using PhpStorm.
 * https://en.bainternet.info/wordpress-easy-login-url-with-no-htaccess/
 */

// Create new rewrite rule
add_action( 'init', 'NLURL_rewrite' );
function NLURL_rewrite() {
    add_rewrite_rule( 'login/?$', 'wp-login.php', 'top' );
    add_rewrite_rule( 'register/?$', 'wp-login.php?action=register', 'top' );
    add_rewrite_rule( 'forgot/?$', 'wp-login.php?action=lostpassword', 'top' );
}

// Add rewrite rule and flush on plugin activation
register_activation_hook( __FILE__, 'NLURL_activate' );
function NLURL_activate() {
    NLURL_rewrite();
    flush_rewrite_rules();
}

// Flush on plugin deactivation
register_deactivation_hook( __FILE__, 'NLURL_deactivate' );
function NLURL_deactivate() {
    flush_rewrite_rules();
}

//register url fix
add_filter('register','fix_register_url');
function fix_register_url($link){
    return str_replace(site_url('wp-login.php?action=register', 'login'),site_url('register', 'login'),$link);
}

//login url fix
add_filter('login_url','fix_login_url');
function fix_login_url($link){
    return str_replace(site_url('wp-login.php', 'login'),site_url('login', 'login'),$link);
}

//forgot password url fix
add_filter('lostpassword_url','fix_lostpass_url');
function fix_lostpass_url($link){
    return str_replace('?action=lostpassword','',str_replace(network_site_url('wp-login.php', 'login'),site_url('forgot', 'login'),$link));
}

//Site URL hack to overwrite register url
add_filter('site_url','fix_urls',10,3);
function fix_urls($url, $path, $orig_scheme){
    if ($orig_scheme !== 'login')
        return $url;
    if ($path == 'wp-login.php?action=register')
        return site_url('register', 'login');

    return $url;
}