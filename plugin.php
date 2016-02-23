<?php

/**
 * @subpackage Contact
 * @version 1.0.0
 * @package Boilerplate
 * @author Chris Galbraith
 */

# Check for GUMP
if ( !class_exists('GUMP') ) {
    require_once __DIR__ . '/inc/gump.class.php';
}

# Check for PHPMailer
if ( !class_exists('PHPMailer') ) {
    require_once __DIR__ . '/inc/PHPMailer/PHPMailerAutoload.php';
}

# Load Forms
if ( ! isset($_config['forms']) ) {
    throw new Exception('Contact Plugin: Unable to load form config. Check your config file. <em>Copy the example config from the <code>_plugins/contact</code> directory to your <code>.config.yml</code> file.</em>');
}

# Load Functions
require_once __DIR__ . '/inc/form-html.php';

# Load template scripts
$_contact_scripts = array_merge(get('site.scripts'), array(
    '/_plugins/contact/js/ajax-form.js'
));

set('site.scripts', $_contact_scripts);

# Load template stylesheets
$_contact_stylesheets = array_merge(get('site.stylesheets'), array(
    '/_plugins/contact/css/ajax-form.css'
));

set('site.stylesheets', $_contact_stylesheets);

# Get Forms
foreach ( $_config['forms'] as $id => $form ) {

    # Load Form Config
    $_contact_forms[$id] = $form;

    # Add ID as a key
    $_contact_forms[$id]['id'] = $id;

    # Add form ID as a contact form path
    $_contact_post[] = 'submit/' . $id;

}

# Handle form submissions
if ( in_array($_path, $_contact_post) ) {

    # Use Placeholder Templates
    $_theme->use_theme(__DIR__ . '/templates');

    # Check for POST data
    if ( is_form_data() ) {

        # Create GUMP object
        $gump = new GUMP();

        # Sanitize input
        $data = $gump->sanitize(form_data());

        # Get Config for this Form
        $_current_form = $_contact_forms[$_index[1]];

        # See if success message is set
        if ( !isset($_current_form['success_message']) ) {
            $_current_form['success_message'] = 'Thanks! Your message has been sent.';
        }

        # Create Validation & Filter Rules
        foreach ( $_current_form['fields'] as $key => $field ) {

            # Validation Rules
            if ( isset($field['validate']) ) {
                $_gump_validate[$key] = $field['validate'];
            }

            # Filter Rules
            if ( isset($field['filter']) ) {
                $_gump_filter[$key] = $field['filter'];
            }

            # Set Field Label
            GUMP::set_field_name($key, $field['label']);

        }

        # Set Validation Rules
        $gump->validation_rules($_gump_validate);

        # Set Filter Rules
        $gump->filter_rules($_gump_filter);

        # Run GUMP
        $clean_data = $gump->run($data);

        # Get GUMP Response
        if ( $clean_data === false ) {

            $_contact_response = $gump->get_readable_errors(true);

            if ( extras_enabled() ) {
                $_contact_response = apply_filters('contact_on_fail', $_contact_response);
            }

            $_contact_json = array(
                'code'      => 100,
                'type'      => 'negative',
                'message'   => $_contact_response
            );

        } else {

            # Create Message
            set('contact_fields', $clean_data);

            # Set Message & Auto Responder
            $_message = $_theme->render('email-message.php', get(), false);
            $_auto_responder = $_theme->render('email-auto-responder.php', get(), false);

            # Use PHPMailer if it's available,
            # otherwise use normal mail()
            if ( !class_exists('PHPMailer') ) {
                require_once __DIR__ . '/inc/legacy-mail.php';
            } else {
                require_once __DIR__ . '/inc/mail.php';
            }

        }

        header('Content-type: application/json');
        echo json_encode($_contact_json);
        exit;

    }

}
