<?php
/**
 * INITIALIZE ----------- ----------- -----------
 */

add_action('init', 'tu_reg_post_type_home');

function tu_reg_post_type_home() {

    //Change this when creating post type
    $post_type_name = __('Trang chủ', TEXT_DOMAIN);
    $post_type_name_lower = mb_strtolower($post_type_name, 'utf-8');
    $post_type_name_slug = tu_remove_accent($post_type_name, '-');
    $post_type_menu_position = 4;

    $labels = array(
        'name' => $post_type_name,
        'singular_name' => $post_type_name,
        'menu_name' => $post_type_name,
        'all_items' => __('Tất cả', TEXT_DOMAIN).' '.$post_type_name_lower,
        'add_new' => __('Thêm mới', TEXT_DOMAIN),
        'add_new_item' => __('Thêm mới', TEXT_DOMAIN).' '.$post_type_name_lower,
        'edit_item' => __('Chỉnh sửa', TEXT_DOMAIN).' '.$post_type_name_lower,
        'new_item' => $post_type_name,
        'view_item' => __('Xem chi tiết', TEXT_DOMAIN),
        'search_items' => __('Tìm kiếm', TEXT_DOMAIN),
        'not_found' => __('Không tìm thấy bản ghi nào', TEXT_DOMAIN),
        'not_found_in_trash' => __('Không có bản ghi nào trong thùng rác', TEXT_DOMAIN),
        'view' => __('Xem', TEXT_DOMAIN).' '.$post_type_name_lower,
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'show_in_nav_menus' => false,
        'show_ui' => true,

        //Change this when creating post type
        'description' => $post_type_name,
        'menu_position' => $post_type_menu_position,
        'menu_icon' => 'dashicons-welcome-widgets-menus',
        'supports' => array('title'),
        'rewrite' => array(
            'slug' => $post_type_name_slug
            ),

        //Use `Page Template` instead, it is more easy to custom
        'has_archive' => false
    );

    register_post_type('home', $args);

}




/**
 * RETRIEVING FUNCTIONS ----------- ----------- -----------
 */

/**
 * Get homes
 *
 * @param int   $page
 * @param int   $post_per_page
 * @param array $custom_args
 *
 * @return WP_Query
 */
function tu_get_home_with_pagination($paged = 1, $post_per_page = 10, $custom_args = array()) {

    $args = array(
        'post_type' => 'home',
        'posts_per_page' => $post_per_page,
        'paged' => $paged,
        'orderby' => 'date',
        'order'   => 'DESC',
        'post_status' => 'publish',
        'tax_query' => array(),
        'meta_query' => array(),
    );

    // Push Taxonomy

    $args = array_merge($args, $custom_args);

    $posts = new WP_Query($args);

    return $posts;
}
