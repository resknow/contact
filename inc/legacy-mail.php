<?php

# Set Message Headers
$_message_headers = 'MIME-Version: 1.0' . "\r\n";
$_message_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$_message_headers .= 'From:' . $clean_data['email'] . "\r\n";

# Set Auto Responder Headers
$_responder_headers = 'MIME-Version: 1.0' . "\r\n";
$_responder_headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$_responder_headers .= 'From:' . $_current_form['recipient'] . "\r\n";

# Send Message & Auto Responder
if ( !mail(
    $_current_form['recipient'],
    $_current_form['subject'],
    $_message,
    $_message_headers
) || !mail(
    $_current_form['recipient'],
    $_current_form['subject'],
    $_auto_responder,
    $_responder_headers
) ) {

    $_contact_response = 'Your message was not sent.';

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
