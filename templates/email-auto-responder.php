<div class="message-body" style="color: #333; font-family: sans-serif; font-size: 16px; margin: 0 auto; max-width: 520px; padding: 40px">
    <?php if ( get('contact_logo') ): ?>
        <p style="padding-bottom: 20px;">
            <img src="<?php e('contact_logo'); ?>">
        </p>
    <?php endif; ?>
    <p style="font-size: 18px;">Thanks for contacting <?php e('site.company') ?></p>
    <hr style="border: 1px solid #ddd">
    <p style="margin-top: 30px;">
        Hi <?php e('contact_fields.name'); ?>,<br><br>
        Thanks for contacting <?php e('site.company') ?>. Your message has been sent and we'll be in contact soon.<br><br>
        Kind regards,<br>
        <?php e('site.company') ?>
    </p>
</div>
