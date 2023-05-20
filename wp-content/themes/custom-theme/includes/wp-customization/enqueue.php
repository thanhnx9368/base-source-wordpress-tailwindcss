<?php

/**
 * Adding scripts and styles
 * All your scripts and styles will be included in wp_head()
 */
add_action('wp_enqueue_scripts', 'tu_enqueue_scripts_styles');

function tu_enqueue_scripts_styles()
{

  if (wp_script_is('media')) {
    wp_enqueue_media();
  }

  wp_enqueue_style('main-css', TEMPLATE_URL . '/style.css');

  wp_enqueue_script('tailwindcss', TEMPLATE_URL . '/assets/scripts/vendors/tailwindcss.js', array('jquery'));
  wp_enqueue_script('tailwindcss-config', TEMPLATE_URL . '/assets/scripts/tailwind.config.js', array('jquery'));

  wp_enqueue_script('scripts', TEMPLATE_URL . '/assets/scripts/main.js', array('jquery'));

  $wp_script_data = array(
    'ADMIN_AJAX_URL' => ADMIN_AJAX_URL,
    'HOME_URL' => HOME_URL
  );

  wp_localize_script('scripts', 'wp_vars', $wp_script_data);
}
