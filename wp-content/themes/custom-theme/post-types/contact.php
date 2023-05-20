<?php
/**
 * TODO:
 * Chức năng export ra Excel
 */

/**
 * INITIALIZE ----------- ----------- -----------
 */

add_action('init', 'tu_reg_post_type_contact');

function tu_reg_post_type_contact() {

    //Change this when creating post type
    $post_type_name = __('Liên hệ & tư vấn', TEXT_DOMAIN);
    $post_type_name_lower = mb_strtolower($post_type_name, 'utf-8');
    $post_type_menu_position = 3;

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
        'menu_icon' => 'dashicons-feedback',
        'supports' => array('title'),
        'rewrite' => null,
        'has_archive' => false
    );

    register_post_type('contact', $args);
}

add_action('admin_init', 'tu_contact_remove_sub_menu');
function tu_contact_remove_sub_menu(){

    global $submenu;

    if(isset($submenu['edit.php?post_type=contact'][10])){
        unset($submenu['edit.php?post_type=contact'][10]);
    }
}

/**
 * RETRIEVING FUNCTIONS ----------- ----------- -----------
 */

/**
 * Get contacts
 *
 * @param int   $page
 * @param int   $post_per_page
 * @param array $custom_args
 *
 * @return WP_Query
 */
function tu_get_contact_with_pagination($page = 1, $post_per_page = 10, $custom_args = array()) {

    $args = array(
        'post_type' => 'contact',
        'posts_per_page' => $post_per_page,
        'paged' => $page,
        'post_status' => 'pending',
        'tax_query' => array()
    );

    $args = array_merge($args, $custom_args);

    $posts = new WP_Query($args);

    return $posts;
}

/**
 * NOTIFICATION ----------- ----------- -----------
 */
add_action('admin_menu', 'tu_contact_admin_menu_notification');
function tu_contact_admin_menu_notification() {
    global $menu;
    $subscribers = get_posts(array('post_type' => 'contact', 'posts_per_page' => -1, 'post_status' => 'pending'));
    $menu[6][0] .= $subscribers ? '&nbsp;<span class="update-plugins count-1" title="' .__('Bạn có', TEXT_DOMAIN).' '. count($subscribers) .' '. __('lượt booking mới', TEXT_DOMAIN).'"><span class="update-count">' . count($subscribers) . '</span></span>' : '';
}

/**
 * POST META BOXES ----------- ----------- -----------
 */

add_action('admin_init', 'tu_add_meta_box_contact');
function tu_add_meta_box_contact() {

    /** Meta box for general information */
    function tu_display_meta_box_contact_general($post) {
        $post_id = $post->ID;
        $contact_name = get_post_meta($post_id, 'contact_name', true);
        $contact_phone = get_post_meta($post_id, 'contact_phone', true);
        $contact_services = get_post_meta($post_id, 'contact_services', true);
        $contact_content = get_post_meta($post_id, 'contact_content', true);
        ?>
        <table class="form-table">
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('save_metabox_contact'); ?>">
            <tbody>
            <tr>
                <th scope="row"><label><?php _e('Họ tên', TEXT_DOMAIN); ?></label></th>
                <td><?php echo $contact_name; ?></td>
            </tr>
            <tr>
                <th scope="row"><label><?php _e('SĐT', TEXT_DOMAIN); ?></label></th>
                <td><?php echo $contact_phone; ?></td>
            </tr>
            <tr>
                <th scope="row"><label><?php _e('Dịch vụ quan tâm', TEXT_DOMAIN); ?></label></th>
                <td><?php echo $contact_services; ?></td>
            </tr>
            <tr>
                <th scope="row"><label><?php _e('Yêu cầu bổ sung', TEXT_DOMAIN); ?></label></th>
                <td><?php echo $contact_content; ?></td>
            </tr>
            </tbody>
        </table>
    <?php
    }

    add_meta_box(
        'tu_display_meta_box_contact_general', __('Thông tin liên hệ', TEXT_DOMAIN), 'tu_display_meta_box_contact_general', 'contact', 'normal', 'high'
    );
}


/**
 * Contact submit Ajax
 */
add_action('wp_ajax_home_form_booking', 'home_form_booking');
add_action('wp_ajax_nopriv_home_form_booking', 'home_form_booking');
function home_form_booking()
{
    // Function response
    function result_data($bool, $msg) {
        echo json_encode(array('success' => $bool, 'msg' => $msg));
        exit;
    }
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'home_form_booking')) {
        $msg = __('Phiên làm việc đã hết, vui lòng tải lại trang và thử lại.', TEXT_DOMAIN);
        result_data(false, $msg);
    }

    // Query post has metabox value exists
    function query_post_has_metabox_exists($post_type, $metabox_key, $value, $message)
    {
        $args = array(
            'post_type' => 'contact',
            'order' => 'DESC',
            'orderby' => 'ID',
            'posts_per_page' => 1,
            'paged' => 1,
            'post_status' => 'pending',
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => $metabox_key,
                    'value' => $value,
                    'compare' => '='
                )
            )
        );

        $posts = new WP_Query($args);

        if ($posts->have_posts()) {
            return $message;
        }

        return false;
    }

    // Function query data exists
    function ajax_form_field_exists($post_type, $metabox, $value, $message)
    {
        $is_field_exists = query_post_has_metabox_exists(
            $post_type,
            $metabox,
            $value,
            $message
        );

        if (!$is_field_exists == false) {
            result_data(false, $is_field_exists);
        }
    }

    $data = array();

    $data['contact_name'] = (isset($_POST['contact_name'])) ? sanitize_text_field($_POST['contact_name']) : null;
    $data['contact_phone'] = (isset($_POST['contact_phone'])) ? sanitize_text_field($_POST['contact_phone']) : null;
    $data['contact_services'] = (isset($_POST['contact_services'])) ? sanitize_text_field($_POST['contact_services']) : null;
    $data['contact_content'] = (isset($_POST['contact_content'])) ? sanitize_text_field($_POST['contact_content']) : null;
    $data['created_at'] = (isset($_POST['created_at'])) ? sanitize_text_field($_POST['created_at']) : null;


    $requires = ['contact_name', 'contact_phone', 'contact_services'];


    // Check fields empty
    foreach ($requires as $item) {
        if (empty($data[$item])) {
            $msg = __('Vui lòng nhập đầy đủ thông tin.', TEXT_DOMAIN);
            result_data(false, $msg);
        }
    }

    // Check phone
    if (!empty($data['contact_phone'])) {
        if (!preg_match('/^[0-9_\s]{10,20}+$/i', $data['contact_phone'])) {
            $msg = __('Số điện thoại không hợp lệ.', TEXT_DOMAIN);
            result_data(false, $msg);
        }
         /*ajax_form_field_exists(
             'contact',
             'contact_phone',
             $data['contact_phone'],
             __('Số điện thoại đã được đăng ký', TEXT_DOMAIN)
         );*/
    }


    $new_contact_agrs = array(
        'post_title' => $data['contact_name'] . ' - ' . $data['contact_phone'],
        'post_status' => 'pending',
        'post_type' => 'contact'
    );
    $new_contact = wp_insert_post($new_contact_agrs);

    if (!is_wp_error($new_contact)) {
        // Save metabox
        if (!empty($data['contact_name'])) {
            update_post_meta($new_contact, 'contact_name', $data['contact_name']);
        }

        if (!empty($data['contact_phone'])) {
            update_post_meta($new_contact, 'contact_phone', $data['contact_phone']);
        }

       if (!empty($data['contact_services'])) {
            update_post_meta($new_contact, 'contact_services', $data['contact_services']);
        }

        if (!empty($data['contact_content'])) {
            update_post_meta($new_contact, 'contact_content', $data['contact_content']);
        }

//        $msg = __('
//        Cám ơn bạn đã lựa chọn ID BEAUTY CENTER. <br>
//        Rất hân hạnh được đón tiếp bạn tại Spa. <br>
//        Chúng tôi sẽ liên hệ để xác nhận thông tin của Quý khách. <br>
//        Vui lòng liên hệ hotline <a href="tel:0333711928">0333.711.928</a> nếu cần trợ giúp.
//        ', TEXT_DOMAIN);

        $name = $data['contact_name'];
        $phone = $data['contact_phone'];
        $services = $data['contact_services'];
        $created_at = $data['created_at'];
        $send_mail = array('sale@lisacerny.com', 'phongdesign@idbeautycenter.vn');
        $mail_content = "
        <p><b>Thông tin khách hàng:</b></p>
        <p>- Họ tên: $name </p>
        <p>- SĐT: $phone</p>
        <p>- Dịch vụ cần tư vấn: $services</p>
        <p>- Gửi lúc: $created_at</p>
        ";

        wp_mail($send_mail, "Có khách hàng mới đăng kí tư vấn trên website ID Beauty Center:", $mail_content );
        result_data(true, $msg);
        exit;
    } else {
        $msg = __('Something went wrong. Please try again!', TEXT_DOMAIN);
        result_data(false, $msg);
    }

    exit;
}


/**
 * EXPORTING
 */
add_action('admin_menu', 'tu_add_contact_sub_menu_pages_export_contact');
function tu_add_contact_sub_menu_pages_export_contact() {
    add_submenu_page(
        'edit.php?post_type=contact',
        __('Trích xuất', TEXT_DOMAIN).' '.__('liên hệ', TEXT_DOMAIN),
        __('Trích xuất', TEXT_DOMAIN).' '.__('liên hệ', TEXT_DOMAIN),
        'edit_posts',
        'contact_export',
        'tu_add_contact_sub_menu_pages_export_contact_callback'
    );
}
function tu_add_contact_sub_menu_pages_export_contact_callback()
{
    include_once(get_template_directory().'/post-types/contact-export-page.php');
}
add_action('admin_init', 'add_contact_sub_menu_pages_export_contact_handle');
function add_contact_sub_menu_pages_export_contact_handle()
{
    include_once(get_template_directory().'/post-types/contact-export-page-handle.php');
}
