<?php

// Add meta box for contacts
function add_contact_meta_box() {
    add_meta_box(
        'contact_meta_box',
        'Contact Information',
        'custom_render_contact_meta_box',
        'person',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_contact_meta_box');

// Render contact meta box
function render_contact_meta_box($post) {
    // Display form for adding contacts here
    echo '<label for="country">Country:</label>';
    echo '<select id="country" name="country">';
    
    // Fetch country data from REST Countries API
    $countries = get_countries();
    foreach ($countries as $country) {
        echo '<option value="' . $country['callingCodes'][0] . '">' . $country['name'] . ' (' . $country['callingCodes'][0] . ')</option>';
    }
    
    echo '</select>';
    
    echo '<br><br>';
    
    echo '<label for="number">Number:</label>';
    echo '<input type="text" id="number" name="number" pattern="\d{9}" title="Number should be exactly 9 digits">';
}

// Fetch country data from REST Countries API
function get_countries() {
    $response = wp_remote_get('https://restcountries.com/v3.1/all');
    $body = wp_remote_retrieve_body($response);
    
    $countries = array();
    if (!is_wp_error($response)) {
        $countries = json_decode($body, true);
    }
    
    return $countries;
}
