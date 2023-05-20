<?php
/**
 * INITIALIZE ----------- ----------- -----------
 */

add_action('init', 'tu_reg_post_type_article');

function tu_reg_post_type_article() {

    //Change this when creating post type
    $post_type_name = 'Tin Tức';
    $post_type_name_lower = mb_strtolower($post_type_name, 'utf-8');
    $post_type_name_slug = tu_remove_accent($post_type_name, '-');
    $post_type_menu_position = 3;

    $labels = array(
        'name' => $post_type_name,
        'singular_name' => $post_type_name,
        'menu_name' => $post_type_name,
        'all_items' => 'Tất cả '.$post_type_name_lower,
        'add_new' => 'Thêm mới',
        'add_new_item' => 'Thêm mới '.$post_type_name_lower,
        'edit_item' => 'Chỉnh sửa '.$post_type_name_lower,
        'new_item' => $post_type_name,
        'view_item' => 'Xem chi tiết',
        'search_items' => 'Tìm kiếm',
        'not_found' => 'Không tìm thấy bản ghi nào',
        'not_found_in_trash' => 'Không có bản ghi nào trong thùng rác',
        'view' => 'Xem'.$post_type_name_lower,
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
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author'),
        'rewrite' => array(
            'slug' => 'tin-tuc'
        ),

        //Use `Page Template` instead, it is more easy to custom
        'has_archive' => false
    );

    register_post_type('article', $args);

     register_taxonomy('article_category', array('article'), array(
     "hierarchical" => true,
     "label" => 'Chuyên mục tin tức',
     "singular_label" => 'Chuyên mục tin tức',
     "rewrite" => array('slug' => 'chuyen-muc-tin-tuc', 'hierarchical' => true),
     "show_admin_column" => true
 ));

}

/**
 * RETRIEVING FUNCTIONS ----------- ----------- -----------
 */

/**
 * Get articles
 *
 * @param int   $page
 * @param int   $post_per_page
 * @param array $custom_args
 *
 * @return WP_Query
 */
function tu_get_article_with_pagination($page = 1, $post_per_page = 10, $custom_args = array()) {

    $args = array(
        'post_type' => 'article',
        'posts_per_page' => $post_per_page,
        'paged' => $page,
        's' => '',
        'post_status' => 'publish',
        'tax_query' => array(),
        'meta_query' => array(),
    );
    // Push Taxonomy
    if (isset($custom_args['article_category'])) {

        array_push($args['tax_query'], array(
            'taxonomy' => 'article_category',
            'field' => 'id',
            'terms' => $custom_args['article_category']
        ));

        unset($custom_args['article_category']);
    }
    // Push article is hot
    if (isset($custom_args['article_is_hot'])) {

        array_push($args['meta_query'], array(
            'key'     => 'article_is_hot',
            'value'   => $custom_args['article_is_hot'],
            'compare' => '=',
        ));

        unset($custom_args['article_is_hot']);
    }

    if (isset( $custom_args['post__not_in'] ) && $custom_args['post__not_in'])  {
        $custom_args['post__not_in'] = $custom_args['post__not_in'];
    }

    if (isset($custom_args['s'])) {
        $args['s'] = $custom_args['s'];
    }

    $args = array_merge($args, $custom_args);

    $posts = new WP_Query($args);

    return $posts;
}



add_action('admin_init', 'tu_add_meta_box_article');
function tu_add_meta_box_article() {

    function tu_display_meta_box_article_general($post) {
        $post_id = $post->ID;
        $article_is_hot = get_post_meta($post_id, 'article_is_hot', true);
        ?>
        <table class="form-table">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('save_meta_box_article'); ?>">
            <tbody>
            <tr>
                <th scope="row"><label for="article_is_hot"><?php _e('Bài viết nổi bật', TEXT_DOMAIN) ?></label></th>
                <td>
                    <input type="hidden" name="do" value="post"/>
                    <input type="checkbox" id="article_is_hot" name="article_is_hot"
                           value="1" <?php if ($article_is_hot == 'true') echo 'checked="checked"'; ?> />
                </td>
            </tr>
            </tbody>
        </table>
        <?php
    }

    add_meta_box( 'tu_display_meta_box_article_general', 'Thông tin cơ bản', 'tu_display_meta_box_article_general', 'article', 'normal', 'high' );
}

add_action('save_post', 'tu_save_meta_box_article');
function tu_save_meta_box_article($post_id) {

    // Autosave, do nothing
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    // AJAX? Not used here
    if (defined('DOING_AJAX') && DOING_AJAX)
        return;
    // Check user permissions
    if (!current_user_can('edit_post', $post_id))
        return;
    // Return if it's a post revision
    if (false !== wp_is_post_revision($post_id))
        return;

    $article_is_hot = isset($_POST['article_is_hot']) && (int) $_POST['article_is_hot'] ? 'true' : 'false';
    update_post_meta($post_id, 'article_is_hot', $article_is_hot);

}


add_filter('manage_edit-article_columns', 'my_article_columns');
function my_article_columns($columns) {
    $columns['article_is_hot'] = 'Tin nổi bật';

    return $columns;
}

add_action('manage_article_posts_custom_column', 'my_article_column_content', 10, 2);
function my_article_column_content($column_name, $post_id) {

    switch ( $column_name ) {

        case 'article_is_hot' :
            $article_is_hot = get_post_meta($post_id, 'article_is_hot', true);
            if ( $article_is_hot == 'true' ){
                ?>
                <span class="dashicons dashicons-yes"></span>
                <?php
            }
            break;

    }

}

/**
 * AJAX HANDLE
 */

add_action('wp_ajax_load_more_article_ajax', 'load_more_article_ajax');
add_action('wp_ajax_nopriv_load_more_article_ajax', 'load_more_article_ajax');
function load_more_article_ajax() {

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'load_more_article_nonce')) {
        echo json_encode(array('success' => false, 'msg' => 'Phiên làm việc đã hết, vui lòng tải lại trang và thử lại.'));
        exit;
    }

    $start_item = isset( $_POST['start_item'] ) ? (int) $_POST['start_item'] : 1;
    $item_load_more = isset( $_POST['item_load_more'] ) ? (int) $_POST['item_load_more'] : 1;
    $current_term_id = isset( $_POST['current_term_id'] ) ? (int) $_POST['current_term_id'] : 1;
    $search_value = isset( $_POST['search_value'] ) ? (int) $_POST['search_value'] : '';

    if ( $search_value == 1 ) {
        $article = tu_get_article_with_pagination($paged, -1, array('article_is_hot' => 'false'));

    } else {
        $article = tu_get_article_with_pagination($paged, -1, array('article_category' => $current_term_id, 'article_is_hot' => 'false'));
    }


    if ( $article->have_posts() )  $i = 1; {
        $html = '';
        $array = array();
        $item_after_load = $start_item + $item_load_more;
        while ( $article->have_posts() ) {
            $article->the_post();
            $post_id = get_the_ID();
            array_push($array, $post_id);
            $thumbnail = has_post_thumbnail( $post_id ) ? tu_get_post_thumbnail_src_by_post_id( $post_id, 'home-album' ) : IMAGE_URL.'/tin-tuc/img3.jpg';
            $title = get_the_title($post_id);
            $hyperlink = get_the_permalink($post_id);
            if ( $i > $start_item ) {
                if ( $i > $item_after_load ) break;
                $html .=
                    '<div class="new_item">
                            <a href="'. $hyperlink .'">
                                <div class="_img" style="background-image: url( '.$thumbnail . ');"></div>
                                <div class="_text">
                                    <div class="_title">'. $title .'</div>
                                    <div class="_readmore"> '.pll_e('Chi tiết').' <img src="'. IMAGE_URL.'/tin-tuc/btn-next.svg' .'"></div>
                                </div>
                            </a>
                        </div>
                            ';
            }
            $i++;

        }
        $count_article = count($array);
        if ( $count_article > $item_after_load ) {
            echo json_encode( array('success' => true, 'msg' => 'Successfull!', 'after' => $item_after_load, 'html' => $html, 'load' => true) );
            exit;
        } else {
            echo json_encode( array('success' => true, 'msg' => 'Successfull!', 'after' => $item_after_load, 'html' => $html, 'load' => false) );
            exit;
        }

    }

}