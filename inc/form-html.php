<?php

/**
 * Contact Plugin Form HTML
 */

/**
 * Get Form
 *
 * @var $id: ID of the form
 * @return HTML form
 */
function get_form( $id ) {
    $_config = get('site');

    $the_form = $_config['forms'][$id];

    # Prepend output
    $output = '<div id="cf-form-' . $id . '" class="cf-form">
        <form action="/submit/'. $id .'" method="post">';

    # Build HTML
    foreach ( $the_form['fields'] as $key => $field ) {
        $output .= '<div class="field field-name-' . $key . ' field-type-' . $field['type'] . '">
            ' . get_field_html($id, $key) . '
        </div>';
    }

    # Append output
    $output .= '<div class="field">
        <button type="submit" id="submit">Submit</button>
    </div>
</form>
</div>';

    return $output;
}

function get_field_html( $form, $field ) {
    $_config = get('site');

    # The Field
    $the_field = $_config['forms'][$form]['fields'][$field];

    # Check Placeholder
    if ( !isset($the_field['placeholder']) ) {
        $the_field['placeholder'] = '';
    }

    # Check status
    if ( !isset($the_field['checked']) ) {
        $the_field['checked'] = false;
    }

    # Get HTML
    switch (true) {

        # Checkbox / Radio
        case $the_field['type'] == 'checkbox':
        case $the_field['type'] == 'radio':
            return '<label><input name="' . $field . '" type="'. $the_field['type'] .'">'. $the_field['label'] .'</label>';
            break;

        # Select
        case $the_field['type'] == 'select':
            return '<select name="' . $field . '">' . get_select_options($the_field) . '</select>';
            break;

        # Textarea
        case $the_field['type'] == 'textarea':
            return '<textarea name="' . $field . '" placeholder="' . $the_field['placeholder'] . '"></textarea>';
            break;

        default:
            return '<input name="' . $field . '" type="'. $the_field['type'] .'" placeholder="' . $the_field['placeholder'] . '">';
            break;

    }
}

function get_select_options( $field ) {
    $output = '';
    foreach( $field['options'] as $option ) {
        $output .= '<option value="' . $option['value'] . '">' . $option['label'] . '</option>';
    }
    return $output;
}
