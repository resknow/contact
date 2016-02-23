<?php

# Create Message
$_mail['message'] = new PHPMailer();
$_mail['message']->setFrom($clean_data['email']);
$_mail['message']->addAddress($_current_form['recipient']);
$_mail['message']->isHTML(true);
$_mail['message']->Subject = $_current_form['subject'];
$_mail['message']->Body = $_message;

# Create Auto Responder
$_mail['auto_responder'] = new PHPMailer();
$_mail['auto_responder']->setFrom($_current_form['recipient']);
$_mail['auto_responder']->addAddress($clean_data['email']);
$_mail['auto_responder']->isHTML(true);
$_mail['auto_responder']->Subject = 'Thanks for contacting ' . get('site.company');
$_mail['auto_responder']->Body = $_auto_responder;

# Send Message & Auto Responder
if ( !$_mail['message']->send() || !$_mail['auto_responder']->send() ) {

    $_contact_response = 'Your message was not sent. (' . $mail->ErrorInfo . ')';

    if ( extras_enabled() ) {
        $_contact_response = apply_filters('contact_on_error', $_contact_response);
    }

    $_contact_json = array(
        'code'      => 100,
        'type'      => 'negative',
        'message'   => $_contact_response
    );

} else {

    $_contact_response = $_current_form['success_message'];

    if ( extras_enabled() ) {
        $_contact_response = apply_filters('contact_on_success', $_contact_response);
    }

    $_contact_json = array(
        'code'      => 200,
        'type'      => 'positive',
        'message'   => $_contact_response
    );

}
