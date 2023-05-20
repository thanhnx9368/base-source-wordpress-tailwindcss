<div class="wrap">

    <h1><?php echo __('Trích xuất', TEXT_DOMAIN).' '.__('thông tin', TEXT_DOMAIN).' '.__('liên hệ', TEXT_DOMAIN); ?></h1>

    <form method="post" action="">

        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('export_contacts'); ?>" />
        <table class="form-table">
            <tbody>

            </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Trích xuất', TEXT_DOMAIN); ?>"></p>

    </form>

</div>
