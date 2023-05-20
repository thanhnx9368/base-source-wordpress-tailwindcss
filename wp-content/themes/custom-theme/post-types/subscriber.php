<?php
/**
 * TODO:
 * Chức năng export ra Excel
 */

/**
 * INITIALIZE ----------- ----------- -----------
 */

add_action('init', 'tu_reg_post_type_subscriber');

function tu_reg_post_type_subscriber() {

    //Change this when creating post type
    $post_type_name = __('DS đăng ký nhận tin', TEXT_DOMAIN);
    $post_type_name_lower = mb_strtolower($post_type_name, 'utf-8');
    $post_type_menu_position = 3;

    $labels = array(
        'name' => $post_type_name,
        'singular_name' => $post_type_name,
        'menu_name' => $post_type_name,
        'all_items' => __('Tất cả', TEXT_DOMAIN),
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
        'menu_icon' => 'dashicons-feedback',
        'supports' => array('title'),
        'rewrite' => null,
        /*'capabilities' => array(
            'create_posts' => 'do_not_allow',
        ),*/
        'has_archive' => false
    );

    register_post_type('subscriber', $args);
}

/**
 * RETRIEVING FUNCTIONS ----------- ----------- -----------
 */

/**
 * Get subscribers
 *
 * @param int   $page
 * @param int   $post_per_page
 * @param array $custom_args
 *
 * @return WP_Query
 */
function tu_get_subscriber_with_pagination($page = 1, $post_per_page = 10) {

    $args = array(
        'post_type' => 'subscriber',
        'posts_per_page' => $post_per_page,
        'paged' => $page,
        'post_status' => 'pending',
    );

    $posts = new WP_Query($args);

    return $posts;
}

function tu_is_subscriber_exist($phone) {
    $args = array(
        'post_type' => 'subscriber',
        'paged' => 1,
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key'     => 'subscriber_email',
                'value'   => $phone,
                'compare' => '=',
            ),
        ),
    );

    $posts = new WP_Query($args);

    if ( $posts->have_posts() ) return true;
    return false;
}


/**
 * NOTIFICATION ----------- ----------- -----------
 */
add_action('admin_menu', 'tu_subscriber_admin_menu_notification');
function tu_subscriber_admin_menu_notification() {
    global $menu;
    $subscribers = get_posts(array('post_type' => 'subscriber', 'posts_per_page' => -1, 'post_status' => 'pending'));
    $menu[7][0] .= $subscribers ? '&nbsp;<span class="update-plugins count-1" title="' .__('Bạn có', TEXT_DOMAIN).' '. count($subscribers) .' '. __('lượt subscriber mới', TEXT_DOMAIN).'"><span class="update-count">' . count($subscribers) . '</span></span>' : '';
}

/**
 * AJAX HANDLE
 */

/*Insert subscriber user form*/
add_action( 'wp_ajax_form_submit_subscriber_ajax', 'form_submit_subscriber_ajax' );
add_action( 'wp_ajax_nopriv_form_submit_subscriber_ajax', 'form_submit_subscriber_ajax' );

function form_submit_subscriber_ajax()
{
    // if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'form_submit_buy_nonce' ) ) {
    $request = array();
    $required_fields = array('email');
    $current_lang = pll_current_language( 'slug' );


    /*get fields*/
    $request['email'] = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
    /*validate*/

    foreach ( $required_fields as $field ) {
        if ( !isset($request[$field]) || !$request[$field] ) {
            if ( $current_lang == 'vi' ) {
                echo json_encode( array('success' => false, 'msg' => 'Vui lòng điền đầy đủ thông tin.') );
            } else {
                echo json_encode( array('success' => false, 'msg' => 'Please fill in required fields') );
            }

            exit;
        }
    }

    if ( !is_email($request['email']) && $request['email'] ) {
        if ( $current_lang == 'vi' ) {
            echo json_encode( array('success' => false, 'msg' => 'Email không hợp lệ. Vui lòng thử lại!') );
        } else {
            echo json_encode( array('success' => false, 'msg' => 'Your email is invalid. Please try again!') );
        }
        exit;
    }


    if ( tu_is_subscriber_exist($request['email']) ) {
        if ( $current_lang == 'vi' ) {
            echo json_encode( array('success' => false, 'msg' => 'Liên hệ này đã đăng ký. Vui lòng thử lại!') );
        } else {
            echo json_encode( array('success' => false, 'msg' => 'Your email already exists. Please try again!') );
        }
        exit;
    }

    /*insert post*/
    $post_data = array(
        'post_title' =>  $request['email'] ,
        'post_content' => '',
        'post_status' => 'pending',
        'post_type' => 'subscriber'
    );

    $subscriber_new_post = wp_insert_post($post_data);

    if( !is_wp_error( $subscriber_new_post ) )
    {
        update_post_meta( $subscriber_new_post, 'subscriber_email', $request['email'] );

        if ( $current_lang == 'vi' ) {
            echo json_encode( array('success' => false, 'msg' =>
                'Cám ơn bạn đã lựa chọn Sapa Jade Hill Resort cho kỳ nghỉ của mình. <br>
                Rất hân hạnh được đón tiếp bạn tại Sapa. <br>')
            );
        } else {
            echo json_encode( array('success' => false, 'msg' => '
            Thank you for choosing Sapa Jade Hill Resort & Spa. <br>
            It is our pleasure to welcome you in Sapa. <br>
            ') );
        }
        exit;
    }
    else {
        echo json_encode( array('success' => false, 'msg' => 'Something went wrong. Please try again!') );
        exit;
    }
}


/**
 * POST META BOXES ----------- ----------- -----------
 */

add_action('admin_init', 'tu_add_meta_box_subscriber');
function tu_add_meta_box_subscriber() {

    /** Meta box for general information */
    function tu_display_meta_box_subscriber_general($post) {
        $post_id = $post->ID;
        $subscriber_email = get_post_meta($post_id, 'subscriber_email', true);
        ?>
        <table class="form-table">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('save_metabox_subscriber'); ?>">
            <tbody>
                <tr>
                    <th scope="row"><label>Email</label></th>
                    <td><?php echo $subscriber_email; ?></td>
                </tr>
            </tbody>
        </table>
        <?php
    }

    add_meta_box(
        'tu_display_meta_box_subscriber_general', __('Thông tin đăng ký', TEXT_DOMAIN), 'tu_display_meta_box_subscriber_general', 'subscriber', 'normal', 'high'
    );
}