<?php

/**
 * @subpackage Contact
 * @version 2.0.0
 * @package Boilerplate II
 * @author Chris Galbraith
 */

# Set Minimum Boilerplate Version
plugin_requires_version('contact', '2.0.0');

# Check for GUMP
if ( !class_exists('GUMP') ) {
    require_once __DIR__ . '/inc/gump.class.php';
}

# Check for PHPMailer
if ( !class_exists('PHPMailer') ) {
    require_once __DIR__ . '/inc/PHPMailer/PHPMailerAutoload.php';
}

# Filter Forms before running the test
# below in case a plugin wants to
# auto-generate a form
set('site.forms', apply_filters('contact/forms', get('site.forms')));

# Load Forms
if ( ! get('site.forms') ) {
    throw new Exception('Contact Plugin: Unable to load form config. Check your config file. <em>Copy the example config from the <code>includes/plugins/contact</code> directory to your <code>.config.yml</code> file.</em>');
}

# Load Functions
require_once __DIR__ . '/inc/form-html.php';

# Load template scripts & stylesheets
#
# Minified versions are used for sites in
# any state other than development.
if ( get('site.environment') == 'dev' ) {

    // Look for jQuery
    $assets = get_scripts();
    if (!isset($assets['jquery'])) {
        add_script( 'jquery', get_package('script', 'jquery') );
    }

    add_script( 'contact-js', '/includes/plugins/contact/js/ajax-form.js' );
    add_stylesheet( 'contact-css', '/includes/plugins/contact/css/ajax-form.css' );
} else {
    add_script( 'contact-js', '/includes/plugins/contact/js/ajax-form.min.js' );
    add_stylesheet( 'contact-css', '/includes/plugins/contact/css/ajax-form.min.css' );
}

# Get Forms
foreach ( get('site.forms') as $id => $form ) {

    # Load Form Config
    $_contact_forms[$id] = $form;

    # Add ID as a key
    $_contact_forms[$id]['id'] = $id;

    # Add form ID as a contact form path
    $_contact_post[] = 'submit/' . $id;

}

# Handle form submissions
if ( in_array(get('page.path'), $_contact_post) ) {

    # Use Placeholder Templates
    #
    # Filter: contact_templates - must return a valid
    # directory containing 3 templates:
    #
    # - email-auto-responder.php
    # - email-message.php
    # - submit.php (Placeholder to avoid errors)
    use_theme(apply_filters('contact/templates', __DIR__ . '/templates'));

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
            $_current_form['success_message'] = apply_filters('contact_default_message', 'Thanks! Your message has been sent.');
        }

        # Set logo
        if ( isset($_current_form['logo']) ) {
            set('contact_logo', $_current_form['logo']);
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

            $_contact_json = array(
                'code'      => 100,
                'type'      => 'negative',
                'message'   => apply_filters('contact_on_fail', $gump->get_readable_errors(true))
            );

        } else {

            # Create Message
            set('contact_fields', $clean_data);

            # Set Message & Auto Responder
            $_message = $_theme->render('email-message.php', get(), false);
            $_auto_responder = $_theme->render('email-auto-responder.php', get(), false);

            # Send message with PHPMailer
            require_once __DIR__ . '/inc/mail.php';

        }

        header('Content-type: application/json');
        echo json_encode($_contact_json);
        exit;

    }

}
