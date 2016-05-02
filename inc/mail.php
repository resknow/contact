<?php

# Create Message
$_mail['message'] = new PHPMailer();
$_mail['message']->setFrom($clean_data['email']);
$_mail['message']->addAddress($_current_form['recipient']);
$_mail['message']->isHTML(true);
$_mail['message']->Subject = $_current_form['subject'];
$_mail['message']->Body = $_message;

# Event: contact_message_before_send
do_event('contact/message/before_send', $_mail['message']);

# Create Auto Responder
$_mail['auto_responder'] = new PHPMailer();
$_mail['auto_responder']->setFrom($_current_form['recipient']);
$_mail['auto_responder']->addAddress($clean_data['email']);
$_mail['auto_responder']->isHTML(true);
$_mail['auto_responder']->Subject = 'Thanks for contacting ' . get('site.company');
$_mail['auto_responder']->Body = $_auto_responder;

# Event: contact_responder_before_send
do_event('contact/responder/before_send', $_mail['auto_responder']);

# Send Message & Auto Responder
if ( !$_mail['message']->send() || !$_mail['auto_responder']->send() ) {

    $_contact_json = array(
        'code'      => 100,
        'type'      => 'negative',
        'message'   => apply_filters('contact/error', 'Your message was not sent. (' . $mail->ErrorInfo . ')')
    );

} else {

    $_contact_json = array(
        'code'      => 200,
        'type'      => 'positive',
        'message'   => apply_filters('contact/success', $_current_form['success_message'])
    );

}
