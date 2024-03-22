<?php


// Shortcode to display the form
function add_person_form_shortcode() {
    ob_start();
    return ob_get_clean();
}
add_shortcode('person-contact-manager', 'add_person_form_shortcode');

// Function to generate the form HTML
?>
<br>
<h1>Add Person</h1>
<br>
<form id='custom-form' method='post'>
    <div class='form-group'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' required>
    </div>
    <br>
    <div class='form-group'>
        <label for='email'>Email:</label>
        <input type='email' id='email' name='email' required>
    </div>
    <br>
    <button type='submit' name='submit'>Submit</button>
</form>
<br>
<a href='admin.php?page=person-contact-manager-add-contact'>Add Contact</a>
    
    
<?php

// Enqueue JavaScript to handle form submission and intertwine with "Add Contact" button
function add_person_form_scripts() {
    // Enqueue jQuery dependency
    wp_enqueue_script('jquery');

    // Enqueue custom JavaScript
    wp_enqueue_script('custom-script', plugin_dir_url(__FILE__) . '_inc/js/index.js', array('jquery'), '1.0', true);

    // Pass REST API endpoint URL and nonce to JavaScript
    wp_localize_script('custom-script', 'custom_vars', array(
        'rest_url' => esc_url_raw(rest_url('person-contact-manager/add-person')),
        'nonce' => wp_create_nonce('wp_rest'),
    ));
}
add_action('wp_enqueue_scripts', 'add_person_form_scripts');
?>