<?php

add_action('admin_menu', 'tu_options');
function tu_options() {
    add_menu_page(__('Cài đặt Website', TEXT_DOMAIN), __('Cài đặt Website', TEXT_DOMAIN), 'manage_options', 'tu_options_page', 'tu_options_page');
}

add_action('admin_init', 'editor_add_cap_edit_options');
function editor_add_cap_edit_options() {
    $role = get_role('editor');
    $role->add_cap('manage_options');
}

add_action('admin_init', 'tu_options_settings');
function tu_options_settings() {
    register_setting('tu-option-group', 'option_company');
    register_setting('tu-option-group', 'option_hotline');
    register_setting('tu-option-group', 'option_phone');
    register_setting('tu-option-group', 'option_instagram');
    register_setting('tu-option-group', 'option_email');
    register_setting('tu-option-group', 'option_address');
    register_setting('tu-option-group', 'option_link_facebook');
    register_setting('tu-option-group', 'option_link_google');
    register_setting('tu-option-group', 'option_link_youtube');
    register_setting('tu-option-group', 'option_note');
    register_setting('tu-option-group', 'option_link_brochure');
    register_setting('tu-option-group', 'option_link_promotion');
}

function tu_options_page() {
    wp_enqueue_media();
    ?>
    <div class="wrap">

        <h2><?php _e('Cài đặt Website',TEXT_DOMAIN); ?></h2>

        <form method="POST" action="options.php">

            <?php settings_fields('tu-option-group'); ?>
            <?php do_settings_sections('tu-option-group'); ?>

            <h3>Thông tin liên hệ</h3>

            <table class="theme-options form-table">
                <tr>
                    <th scope="row"><label for="option_company"><?php _e('Tên công ty', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_company" name="option_company" value="<?php echo esc_attr(get_option('option_company')); ?>"  />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="option_hotline"><?php _e('Đường dây nóng', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_hotline" name="option_hotline" value="<?php echo esc_attr(get_option('option_hotline')); ?>"  />
                    </td>
                </tr>
              <tr>
                <th scope="row"><label for="option_phone"><?php _e('Số điện thoại', TEXT_DOMAIN); ?></label></th>
                <td>
                  <input class="regular-text" type="text" id="option_phone" name="option_phone" value="<?php echo esc_attr(get_option('option_phone')); ?>"  />
                </td>
              </tr>
                <tr>
                    <th scope="row"><label for="option_address"><?php _e('Địa chỉ', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_address" name="option_address" value="<?php echo esc_attr(get_option('option_address')); ?>"  />
                    </td>
                </tr>


                <tr>
                    <th scope="row"><label for="option_email"><?php _e('Email', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_email" name="option_email" value="<?php echo esc_attr(get_option('option_email')); ?>"  />
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="option_link_facebook"><?php _e('Đường dẫn Facebook', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_link_facebook" name="option_link_facebook" value="<?php echo esc_attr(get_option('option_link_facebook')); ?>"  />
                    </td>
                </tr>
               <!-- <tr>
                    <th scope="row"><label for="option_link_google"><?php /*_e('Đường dẫn Google', TEXT_DOMAIN); */?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_link_google" name="option_link_google" value="<?php /*echo esc_attr(get_option('option_link_google')); */?>"  />
                    </td>
                </tr>-->
                <tr>
                    <th scope="row"><label for="option_link_youtube"><?php _e('Đường dẫn Youtube', TEXT_DOMAIN); ?></label></th>
                    <td>
                        <input class="regular-text" type="text" id="option_link_youtube" name="option_link_youtube" value="<?php echo esc_attr(get_option('option_link_youtube')); ?>"  />
                    </td>
                </tr>
              <tr>
                <th scope="row"><label for="option_instagram"><?php _e('Instagram', TEXT_DOMAIN); ?></label></th>
                <td>
                  <input class="regular-text" type="text" id="option_instagram" name="option_instagram" value="<?php echo esc_attr(get_option('option_instagram')); ?>"  />
                </td>
              </tr>
            </table>

            <?php submit_button(); ?>

        </form>
    </div>
    <?php
}