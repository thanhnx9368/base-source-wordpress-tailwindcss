<?php

/**
 * Author: ToanNguyen <anhtoan.dev@gmail.com>
 * Last Edited: 22 March 2017
 * Edited By: ToanNguyen
 */

/**
 * Render menu by menu name
 * @param $menu_name
 * @return false|object|void
 */
function tu_nav_menu($menu_name, $options = array()){

    $args = array('theme_location' => $menu_name, 'container' => false, 'items_wrap' => '%3$s');

    if (isset($options['walker'])) {
        $args['walker'] = $options['walker'];
    }

    return wp_nav_menu($args);
}

/**
 * Render languages switcher
 * @param array $args
 * @return array|null|string
 */
function tu_pll_the_languages($args = array())
{
    $args['dropdown'] = @$args['dropdown'] ?: 0; //Displays a list if set to 0, a dropdown list if set to 1
    $args['show_names'] = @$args['show_names'] ?: 1; //Displays language names if set to 1
    $args['display_names_as'] = @$args['display_names_as'] ?: 'slug'; //Either ‘name’ or ‘slug’
    $args['show_flags'] = @$args['show_flags'] ?: 1; //Displays flags if set to 1
    $args['hide_if_empty'] = @$args['hide_if_empty'] ?: 0; //Hides languages with no posts (or pages) if set to 1
    $args['force_home'] = @$args['force_home'] ?: 0; //Forces link to homepage if set to 1
    $args['hide_if_no_translation'] = @$args['hide_if_no_translation'] ?: 0; //Hides the language if no translation exists if set to 1
    $args['hide_current'] = @$args['hide_current'] ?: 0; //Hides the current language if set to 1
    $args['post_id'] = @$args['hide_current'] ?: null; //If set, displays links to translations of the post (or page) defined by post_id
    $args['raw'] = @$args['raw'] ?: 0; //Use this to create your own custom language switcher

    $args['echo'] = 0; //Notice this

    if ( function_exists( 'pll_the_languages' ) ) {
        return pll_the_languages($args);
    }

    return null;
}

/**
 * Get post using PLL
 * @param $post_id
 * @return int|null
 */
function tu_pll_get_post($post_id)
{
    if ( function_exists( 'pll_get_post' ) ) {
        return pll_get_post($post_id);
    }
    return $post_id;
}

/**
 * Get all terms by parent id
 * @param string $taxonomy
 * @param int $parent
 * @return array|int|WP_Error
 */
function tu_get_terms_by_parent_id($taxonomy = 'category', $parent = 0){
    $terms = get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
        //'orderby' => 'term_id',
        //'order' => 'DESC',
        'parent' => $parent
    ) );

    return $terms;
}

/**
 * Get first term by post_id
 * @param $post_id
 * @param $taxonomy
 * @return bool|mixed
 */
function tu_get_first_term_by_post_id($post_id, $taxonomy = 'category'){
    $temp = get_the_terms($post_id, $taxonomy);
    if($temp){
        return reset($temp);
    }
    else
        return false;
}

/**
 * Get image src by attachment id
 * @param $id
 * @param string $size
 * @return null
 */
function tu_get_image_src_by_attachment_id($id, $size = 'full'){
    if($id > 0){
        $imageInfo = wp_get_attachment_image_src( $id, $size);
        return $imageInfo[0];
    }
    return null;
}

/**
 * Get image HTML by attachment ids
 * @param $ids
 * @param string $size
 * @return string
 */
function tu_get_images_html_by_attachment_ids($ids, $size = ""){
    if(!$ids) return '';

    $html = '';
    $idArray = explode(',', $ids);
    if(!empty($idArray)){
        foreach($idArray as $id){
            $html .= '<img data-img-id="'.$id.'" src="'.tu_get_image_src_by_attachment_id($id, $size).'" />';
        }
    }
    return $html;
}

/**
 * @param        $image_ids
 * @param string $size
 *
 * @return array
 */
function tu_get_images_array_by_ids($image_ids, $size = ""){

    $result = array();

    if(is_array($image_ids) && !empty($image_ids)){
        foreach($image_ids as $image_id){
            array_push($result, array(
                'id' => $image_id,
                'url' => tu_get_image_src($image_id, $size)
            ));
        }
    }

    return $result;
}

/**
 * @param        $image_ids
 * @param string $size
 *
 * @return mixed|string|void
 */
function tu_get_images_json_by_ids($image_ids, $size = ""){

    return json_encode(tu_get_images_array_by_ids($image_ids, $size));
}

/**
 * Get post thumbnail src by post id
 *
 * @param $id
 * @param string $size
 * @return null
 */
function tu_get_post_thumbnail_src_by_post_id($id, $size = 'full'){
    return tu_get_image_src_by_attachment_id(get_post_thumbnail_id($id), $size);
}

/**
 * Get post terms
 *
 * @param        $post_id
 * @param string $taxonomy
 * @param bool   $string
 * @param string $item_prop
 *
 * @return bool|mixed|string
 */
function tu_get_post_terms($post_id, $taxonomy = 'category', $string = true, $item_prop = ''){
    $terms = wp_get_post_terms($post_id, $taxonomy);
    if(!empty($terms) && !isset($terms->errors)){
        if($string == true){
            $rs = array();
            $item_prop = $item_prop ? 'itemprop="'.$item_prop.'"' : '';
            foreach($terms as $t){
                $term_url = get_term_link($t->term_id, $taxonomy);
                if(isset($term_url->errors)){
                    $term_url = '';
                }
                $rs[] = '<a '.$item_prop.' href="'.$term_url.'">'.$t->name.'</a>';
            }
            return implode(', ', $rs);
        }else{
            return $terms;
        }
    }
    return false;
}

/**
 * Get post id by slug
 *
 * @param string $path
 * @param string $post_type
 *
 * @return int|null
 */
function tu_get_post_id_by_slug($path = '', $post_type = 'post'){
    $post = get_page_by_path($path, OBJECT, $post_type);
    if(isset($post->ID)){
        return tu_pll_get_post($post->ID);
    }
    return null;
}

/**
 * Get post permalink by its slug
 * @param string $path
 * @param string $post_type
 *
 * @return bool|false|string|void
 */
function tu_get_permalink_by_slug($path = '', $post_type = 'post'){
    $post = get_page_by_path($path, OBJECT, $post_type);
    if(isset($post->ID)){
        return get_permalink(tu_pll_get_post($post->ID));
    }
    return home_url('/');
}

/**
 * Redirect using javascript
 *
 * @param string $url
 */
function tu_redirect_by_script($url = null){
    echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
}

/**
 * No image url
 *
 * @param null $url
 *
 * @return string
 */
function tu_no_image_url($url = null)
{
    return $url ?: NO_IMAGE_URL;
}

/**
 * Create user with email, password, and role, then return user_id
 *
 * @param        $email
 * @param string $password
 * @param string $role
 *
 * @return int|WP_Error
 */
function tu_create_user_with_email($email, $password = '', $role = 'subscriber'){

    if (null == username_exists($email)) {

        $password = $password ?: wp_generate_password( 12, false );
        $user_id = wp_create_user($email, $password, $email);

        wp_update_user(
            array(
                'ID' => $user_id,
                'nickname' => $email
            )
        );

        $user = new WP_User($user_id);
        $user->set_role($role);

        return $user_id;
    }
}

/**
 * Shorter way to get paginate links
 *
 * @param $wp_query
 *
 * @return array|string|void
 */
function tu_paginate_links($wp_query){

    return paginate_links(array(
        'base' => add_query_arg(array('paged' => '%#%')),
        'format' => '/page/%#%',//'?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'type' => 'list',
        'prev_next' => true,
        'prev_text' => '<i class="fa fa-angle-double-left"></i>',
        'next_text' => '<i class="fa fa-angle-double-right"></i>'
    ));
}