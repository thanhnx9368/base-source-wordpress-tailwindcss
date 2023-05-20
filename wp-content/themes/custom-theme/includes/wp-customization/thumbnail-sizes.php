<?php

/** Remove default size */
function remove_unused_image_size( $sizes) {
    unset( $sizes['thumbnail']);
    unset( $sizes['medium']);
    unset( $sizes['large']);
    unset( $sizes['post-thumbnail']);
}
add_filter('intermediate_image_sizes_advanced', 'remove_unused_image_size');

/** Enable post thumbnails */
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support('post-thumbnails');
}

/** Custom Sizes */
if (function_exists('add_image_size')) {

    add_image_size('thumb-promotion', 240, 170);

    //Default: thumbnail:150x150, medium:300x300, medium_large:768x0, large:1024x1024
    //Home:
    // Banner: 1680x790
    // Grid 8: 750x520 1.442
    // Grid 6: 565x275 2.054
    // Grid 4: 360x455 0.791
}