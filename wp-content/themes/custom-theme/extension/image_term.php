<?php

/** Functions get image by meta_key */
function tu_get_images_array_by_term_id( $term_id, $meta_key  ) {
    $image_arr = get_term_meta( $term_id, $meta_key, true );
    if ($image_arr) {
        foreach ($image_arr as $k => $image) {
            $image['url'] = tu_get_image_src_by_attachment_id($image['id'], 'full');
            $image_arr[$k] = $image;
        }
        return $image_arr;
    }
    return array();
}


/*render image html*/
function tu_render_image_array_by_terms_id_and_name( $banner_name, $term_id = 0, $meta_key ) { ?>

    <table class="form-table <?php echo $meta_key; ?>-container">
        <tbody>
        <tr>
            <th><?php echo $banner_name; ?></th>
            <td>
                <button class="<?php echo $meta_key; ?>-button button" type="button">Chọn file</button>
                <ul class="<?php echo $meta_key; ?>-list tu-meta-box-photo-list ui-sortable"></ul>
            </td>
        </tr>
        </tbody>
    </table>

    <div id="<?php echo $meta_key; ?>_edit_popup_container" class="hidden">
        <table id="<?php echo $meta_key; ?>_edit_popup" class="form-table" data-title="Thông tin ảnh">
            <tr>
                <th>Ảnh</th>
                <td><img class="attachment" src="<?php echo tu_get_image_src_by_attachment_id(0); ?>" style="width: auto; max-width: 100%;"></td>
            </tr>
            <tr>
                <th>Tiêu đề</th>
                <td><input class="title large-text" type="text" /></td>
            </tr>
            <tr>
                <th>Đường dẫn</th>
                <td><input class="external_url large-text" type="text" /></td>
            </tr>
            <tr>
                <th>Tóm tắt</th>
                <td><textarea class="description large-text" rows="6"></textarea>
            </tr>
            <tr>
                <th><input class="guid" type="hidden"></th>
                <td><button class="button button-primary button-large" type="button">Lưu lại</button></td>
            </tr>
        </table>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            var image_el = "<?php echo $meta_key; ?>";

            /* Init variables */
            var album_images_container = $('.' + image_el + '-container');
            var album_images_list = $('.' + image_el + '-list');
            var album_images_button = $('.' + image_el + '-button');
            var album_images_edit_popup = $('#' + image_el + '_edit_popup');

            /* Sortable handles*/
            album_images_list.each(function (index, el) {
                $(this).sortable({
                    stop: function () {
                        album_images_update_list_items_name();
                    }
                }).disableSelection();
            });
            album_images_render_list(JSON.parse('<?php echo tu_json_encode(tu_get_images_array_by_term_id($term_id, $meta_key)); ?>'));
            /* Choosing images handles */
            album_images_button.click(function (evt) {

                var _this = $(this);

                var fileFrame = wp.media.frames.fileFrame = wp.media({
                    title: 'Select image',
                    library: {type: 'image'},
                    button: {text: 'Select'},
                    multiple: true
                });

                fileFrame.on('select', function () {

                    var attachments = fileFrame.state().get('selection').toJSON();

                    $.each(attachments, function (index, item) {
                        album_images_list.append(album_images_get_html_list_item(item));
                        album_images_update_list_items_name();
                    });

                    $(_this).siblings('ul').sortable();
                });

                fileFrame.open();
            });

            /* Replace one image handles */
            album_images_list.delegate('.album_images-photo', 'click', function () {

                var _this = $(this);

                var fileFrame = wp.media.frames.fileFrame = wp.media({
                    title: 'Select image',
                    library: {type: 'image'},
                    button: {text: 'Select'},
                    multiple: false
                });

                var _this_attachment_id = $(_this).siblings('.photo').val();

                if (_this_attachment_id) {
                    fileFrame.on('open', function () {
                        var selection = fileFrame.state().get('selection');
                        var attachment = wp.media.attachment(_this_attachment_id);
                        attachment.fetch();
                        selection.add(attachment ? [attachment] : []);
                    });
                }

                fileFrame.on('select', function () {

                    var attachments = fileFrame.state().get('selection').toJSON();

                    $.each(attachments, function (index, item) {
                        $(_this).attr('src', item.url);
                        $(_this).attr('value', item.id);
                        $(_this).siblings('.attachment').val(item.id);
                    });
                });

                fileFrame.open();
            });

            /* Remove image handles */
            album_images_container.delegate('.album_images-remove', 'click', function (evt) {
                $(this).parent('li').remove();
                album_images_update_list_items_name();
            });

            /* Edit popup handles */
            $(album_images_list).delegate('.album_images-edit', 'click', function () {
                album_images_edit_popup.find('.guid').val($(this).parent('li').attr('id'));
                album_images_edit_popup.find('.attachment').attr('src', $(this).siblings('img').attr('src'));
                album_images_edit_popup.find('.title').val($(this).siblings('.title').val());
                album_images_edit_popup.find('.external_url').val($(this).siblings('.external_url').val());
                album_images_edit_popup.find('.description').val($(this).siblings('.description').val());

                tb_show(album_images_edit_popup.attr('data-title'), '#TB_inline?width=600&height=550&inlineId=' + image_el + '_edit_popup_container');
            });
            $(album_images_edit_popup).find('button').click(function (evt) {

                var guid = album_images_edit_popup.find('.guid').val();
                $('#' + guid).children('.title').val(album_images_edit_popup.find('.title').val());
                $('#' + guid).children('.external_url').val(album_images_edit_popup.find('.external_url').val());
                $('#' + guid).children('.description').val(album_images_edit_popup.find('.description').val());
                tb_remove();
            });

            /* Function - Update list item's name */
            function album_images_update_list_items_name() {
                album_images_list.find('li').each(function (index, el) {
                    $(this).attr('id', 'album_images_' + index);
                    $(this).find('.attachment').attr('name', image_el + '[' + index + '][id]');
                    $(this).find('.title').attr('name', image_el + '[' + index + '][title]');
                    $(this).find('.external_url').attr('name', image_el + '[' + index + '][external_url]');
                    $(this).find('.description').attr('name', image_el + '[' + index + '][description]');
                });
            }

            /* Function - Render the list */
            function album_images_render_list(attachments) {

                var attachment_html_forms = '';

                jQuery.each(attachments, function (index, item) {
                    attachment_html_forms += album_images_get_html_list_item(item);
                });

                album_images_list.html(attachment_html_forms);
                album_images_update_list_items_name();
            }

            function album_images_get_html_list_item(item) {
                var item_title = item.title || '';
                var item_external_url = item.external_url || '';
                var item_description = item.description || '';

                var html = '<img src="' + item.url + '" class="album_images-photo" title="Change photo" value="' + item.id + '">';
                html += '<input class="attachment" type="hidden" value="' + item.id + '" />';
                html += '<input class="title" type="hidden" value="' + item_title + '" />';
                html += '<input class="external_url" type="hidden" value="' + item_external_url + '" />';
                html += '<textarea class="description hidden">' + item_description + '</textarea>';
                html += '<span class="edit album_images-edit dashicons dashicons-edit" title="Edit"></span>';
                html += '<span class="remove album_images-remove dashicons dashicons-no-alt" title"Remove"></span>';

                return '<li>' + html + '</li>';
            }

        });
    </script>

    <?php
}




/*render image html*/
function tu_render_image_array_by_term_ground_category_amenity( $term_id = 0 ) { ?>
    <?php
    if ( $term_id ) {
        ?>
        <!--Display images icon -->
        <table class="form-table ground_screen1_images4-container">
            <tbody>
            <tr>
                <th>
                    Hình ảnh tiện ích
                </th>
                <td>
                    <button class="ground_screen1_images4-button button" type="button">Chọn ảnh</button>
                    <ul class="ground_screen1_images4-list tu-meta-box-photo-list ui-sortable"></ul>
                </td>
            </tr>
            </tbody>
        </table>

        <div id="ground_screen1_images4_edit_popup_container" class="hidden">
            <table id="ground_screen1_images4_edit_popup" class="form-table" data-title="Thông tin ảnh">
                <tr>
                    <th>Ảnh</th>
                    <td><img class="attachment" src="<?php echo tu_get_image_src_by_attachment_id(0); ?>"
                             style="width: auto; max-width: 100%;"></td>
                </tr>
                <tr>
                    <th>Tiêu đề</th>
                    <td><textarea class="title large-text" name="" id="" cols="30" rows="1"></textarea></td>
                </tr>
                <tr>
                    <th>Tóm tắt</th>
                    <td><textarea class="description large-text" rows="6"></textarea>
                </tr>
                <tr>
                    <th><input class="guid" type="hidden"></th>
                    <td>
                        <button class="button button-primary button-large" type="button">Lưu lại</button>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                /* Init variables */
                var ground_screen1_images4_container = $(".ground_screen1_images4-container");
                var ground_screen1_images4_list = $(".ground_screen1_images4-list");
                var ground_screen1_images4_button = $('.ground_screen1_images4-button');
                var ground_screen1_images4_edit_popup = $('#ground_screen1_images4_edit_popup');

                /* Sortable handles*/
                ground_screen1_images4_list.each(function (index, el) {
                    $(this).sortable({
                        stop: function () {
                            ground_screen1_images4_update_list_items_name();
                        }
                    }).disableSelection();
                });
                ground_screen1_images4_render_list(JSON.parse('<?php echo tu_json_encode(tu_get_images_array_by_term_id($term_id, "ground_screen1_images4")); ?>'));
                /* Choosing images handles */
                ground_screen1_images4_button.click(function (evt) {

                    var _this = $(this);

                    var fileFrame = wp.media.frames.fileFrame = wp.media({
                        title: 'Select image',
                        library: {type: 'image'},
                        button: {text: 'Select'},
                        multiple: true
                    });

                    fileFrame.on('select', function () {

                        var attachments = fileFrame.state().get('selection').toJSON();

                        $.each(attachments, function (index, item) {
                            ground_screen1_images4_list.append(ground_screen1_images4_get_html_list_item(item));
                            ground_screen1_images4_update_list_items_name();
                        });

                        $(_this).siblings('ul').sortable();
                    });

                    fileFrame.open();
                });

                /* Replace one image handles */
                ground_screen1_images4_list.delegate('.ground_screen1_images4-photo', 'click', function () {

                    var _this = $(this);

                    var fileFrame = wp.media.frames.fileFrame = wp.media({
                        title: 'Select image',
                        library: {type: 'image'},
                        button: {text: 'Select'},
                        multiple: false
                    });

                    var _this_attachment_id = $(_this).siblings('.photo').val();

                    if (_this_attachment_id) {
                        fileFrame.on('open', function () {
                            var selection = fileFrame.state().get('selection');
                            var attachment = wp.media.attachment(_this_attachment_id);
                            attachment.fetch();
                            selection.add(attachment ? [attachment] : []);
                        });
                    }

                    fileFrame.on('select', function () {

                        var attachments = fileFrame.state().get('selection').toJSON();

                        $.each(attachments, function (index, item) {
                            $(_this).attr('src', item.url);
                            $(_this).attr('value', item.id);
                            $(_this).siblings('.attachment').val(item.id);
                        });
                    });

                    fileFrame.open();
                });

                /* Remove image handles */
                ground_screen1_images4_container.delegate('.ground_screen1_images4-remove', 'click', function (evt) {
                    $(this).parent('li').remove();
                    ground_screen1_images4_update_list_items_name();
                });

                /* Edit popup handles */
                $(ground_screen1_images4_list).delegate('.ground_screen1_images4-edit', 'click', function () {

                    ground_screen1_images4_edit_popup.find('.guid').val($(this).parent('li').attr('id'));
                    ground_screen1_images4_edit_popup.find('.attachment').attr('src', $(this).siblings('img').attr('src'));
                    ground_screen1_images4_edit_popup.find('.title').val($(this).siblings('.title').val());
                    ground_screen1_images4_edit_popup.find('.description').val($(this).siblings('.description').val());

                    tb_show(ground_screen1_images4_edit_popup.attr('data-title'), '#TB_inline?width=600&height=550&inlineId=ground_screen1_images4_edit_popup_container');
                });
                $(ground_screen1_images4_edit_popup).find('button').click(function (evt) {

                    var guid = ground_screen1_images4_edit_popup.find('.guid').val();
                    $('#' + guid).children('.title').val(ground_screen1_images4_edit_popup.find('.title').val());
                    $('#' + guid).children('.description').val(ground_screen1_images4_edit_popup.find('.description').val());
                    tb_remove();
                });

                /* Function - Update list item's name */
                function ground_screen1_images4_update_list_items_name() {
                    ground_screen1_images4_list.find('li').each(function (index, el) {
                        console.log(index);
                        $(this).attr('id', 'ground_screen1_images4_' + index);
                        $(this).find('.attachment').attr('name', 'ground_screen1_images4[' + index + '][id]');
                        $(this).find('.title').attr('name', 'ground_screen1_images4[' + index + '][title]');
                        $(this).find('.external_url').attr('name', 'ground_screen1_images4[' + index + '][external_url]');
                        $(this).find('.description').attr('name', 'ground_screen1_images4[' + index + '][description]');
                    });
                }

                /* Function - Render the list */
                function ground_screen1_images4_render_list(attachments) {

                    var attachment_html_forms = '';

                    jQuery.each(attachments, function (index, item) {
                        attachment_html_forms += ground_screen1_images4_get_html_list_item(item);
                    });

                    ground_screen1_images4_list.html(attachment_html_forms);
                    ground_screen1_images4_update_list_items_name();
                }

                function ground_screen1_images4_get_html_list_item(item) {
                    console.log(item);
                    var item_title = item.title || '';
                    var item_external_url = item.external_url || '';
                    var item_description = item.description || '';

                    var html = '<img src="' + item.url + '" class="ground_screen1_images4-photo" title="Change photo" value="' + item.id + '">';
                    html += '<input class="attachment" type="hidden" value="' + item.id + '" />';
                    html += '<textarea class="title hidden">' + item_title + '</textarea>';
                    html += '<textarea class="external_url hidden">' + item_external_url + '</textarea>';
                    html += '<textarea class="description hidden">' + item_description + '</textarea>';
                    html += '<span class="edit ground_screen1_images4-edit dashicons dashicons-edit" title="Edit"></span>';
                    html += '<span class="remove ground_screen1_images4-remove dashicons dashicons-no-alt" title"Remove"></span>';

                    return '<li>' + html + '</li>';
                }

            });
        </script>

        <?php

    }

}

?>

