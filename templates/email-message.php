<div class="message-body" style="color: #333; font-family: sans-serif; font-size: 16px; margin: 0 auto; max-width: 520px; padding: 40px">
    <?php if ( get('contact_logo') ): ?>
        <p style="padding-bottom: 20px;">
            <img src="<?php echo get('contact_logo'); ?>">
        </p>
    <?php endif; ?>
    <p style="font-size: 18px;"><?php echo get('contact_fields.name') ?> sent a new message sent from your website.</p>
    <hr style="border: 1px solid #ddd">
    <p style="margin-top: 30px;">
        <?php foreach ( get('contact_fields') as $key => $field ): ?>
            <span style="display: inline-block; padding-top: 4px;"><strong><?php echo ucfirst($key); ?>: </strong><?php echo nl2br($field); ?></span><br>
        <?php endforeach; ?>
    </p>
</div>
