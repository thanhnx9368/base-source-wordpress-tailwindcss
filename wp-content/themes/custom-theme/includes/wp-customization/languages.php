<?php

/** Setup text domain */
add_action('init', 'setup_theme_text_domain');
function setup_theme_text_domain() {
    load_theme_textdomain(TEXT_DOMAIN, get_template_directory() . '/languages');
}

/** Enable Polylang for custom post type */
/* add_filter('pll_get_post_types', 'my_pll_get_post_types');
  function my_pll_get_post_types($types) {
  return array_merge($types, array('center' => 'center'));
} */