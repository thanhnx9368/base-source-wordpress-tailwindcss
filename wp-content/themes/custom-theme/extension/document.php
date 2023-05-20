<?php
function tu_render_document_by_post_id_and_key( $post_id = 0, $file_id ) {
//    $document_file_url = get_post_meta($post_id, $file_url, true);
    $document_file_id = get_post_meta($post_id, $file_id, true);
    ?>

    <table class="form-table">
        <tbody>
            <!--<tr>
                <th>Đường dẫn file</th>
                <td>
                    <input id="<?php /*echo $file_url; */?>" type="text" name="<?php /*echo $file_url; */?>" value="<?php /*if(isset($document_file_url)) echo $document_file_url; */?>" placeholder="Document url" style="width: 100%;" />
                </td>
            </tr>-->
            <tr>
                <th scope="row"><label for="document_upload_files">Tải lên file</label></th>
                <td>
                    <div>
                        <button type="button" id="document_upload_files" class="button">Tải lên File</button>
                        <span id="document_file_name" style="line-height: 30px; padding: 0px 5px;"><?php echo isset($document_file_id) && $document_file_id > 0 ? basename( get_post($document_file_id)->guid) : 'No file selected'; ?></span>
                        <input type="hidden" id="<?php echo $file_id; ?>" name="<?php echo $file_id; ?>" value="<?php echo isset( $document_file_id ) ? $document_file_id : 0; ?>">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <script>
        jQuery(document).ready(function($) {

            var document_id = "<?php echo $file_id; ?>";

            $('#document_upload_files').click(function(event) {

                var fileFrame = wp.media.frames.fileFrame = wp.media({
                    title: 'Select file',
                    library: {type: 'application/pdf'},
                    button: {text: 'Select'},
                    multiple: false
                });

                fileFrame.on( 'select', function() {
                    file = fileFrame.state().get('selection').first().toJSON();
                    $('#' + document_id).val(file.id);
                    $('#document_file_name').text(file.filename + '(' + file.filesizeHumanReadable + ')');
                });

                fileFrame.open();
            });

        });
    </script>

<?php
}