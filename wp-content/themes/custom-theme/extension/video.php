<?php

function tu_render_video_post_id_and_key( $post_id = 0, $meta_key ) {
    $video_value = get_post_meta( $post_id, $meta_key, true );
    ?>

    <table class="form-table">
        <tbody>
        <tr>
            <td>
                <input id="<?php echo $meta_key; ?>" type="text" class="text" name="<?php echo $meta_key; ?>" value="<?php if(isset($video_value)) echo $video_value; ?>" placeholder="" />
                <input id="video_youtube_preview<?php echo $meta_key; ?>" type="button" name="publish" id="publish" class="button button-primary" value="Xem trước">
            </td>
        </tr>
        <tr>
            <td id="video_youtube_frame_outer<?php echo $meta_key; ?>"></td>
        </tr>
        </tbody>
    </table>

    <script type="text/javascript">
        jQuery(document).ready(function($){
            var video_el = "<?php echo $meta_key; ?>";
            var video_youtube_preview = $('#video_youtube_preview<?php echo $meta_key; ?>');

            jQuery(video_youtube_preview).click(function(){
                var url = jQuery('#' + video_el).val();
                var tmp1 = url.split("?");
                var code = '';

                if(tmp1.length > 1){
                    var tmp2 = tmp1[1].split("=");
                    code = tmp2[1];
                }else{
                    code = url;
                }

                jQuery('#' + video_el).val(code);

                var video_youtube_frame_outer = $('#video_youtube_frame_outer<?php echo $meta_key; ?>');
                console.log(video_youtube_frame_outer);
                jQuery(video_youtube_frame_outer).html('<iframe width="560" height="315" src="//www.youtube.com/embed/'+code+'" frameborder="0" allowfullscreen></iframe>');

                return false;
            });

            var code = "<?php if(isset($video_value) && $video_value) echo $video_value; ?>";
            if(code){
                jQuery(video_youtube_preview).click();
            }
        });
    </script>

<?php
}
