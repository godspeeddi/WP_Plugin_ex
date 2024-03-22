<?php

/**
 *
 * Plugin Name: Person/Contact Manager
 * Plugin Description: People and contact CRUD manager 
 * Author: Diogo Gomes
 * Version: 1.0.0
 * Text Domain: person-contact-manager
 *
 */

if (!defined('ABSPATH')) {
    echo 'Wrong Way Human!';
    exit;
}

define('PERSON_CONTACT_MANAGER__PLUGIN_DIR', plugin_dir_path(__FILE__));

register_activation_hook(__FILE__, array('person_contact_manager', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('person_contact_manager', 'plugin_deactivation'));

require_once(PERSON_CONTACT_MANAGER__PLUGIN_DIR . 'person-contact-manager-setup.php');

add_action('init', array('person_contact_manager', 'init'));


/* add_action('admin_menu' , 'add_to_menu'); */
function add_to_menu()
{

    add_menu_page('Persons Contacts', 'Persons Contacts Manager', 'administrator', 'person-contact-manager', 'person_contact_manager_list_people_page', true, 'dashicons-admin-users', 17.23);

    add_submenu_page(
        'person-contact-manager',
        'Add Person',
        'Add a Person',
        'manage_options',
        'person-contact-manager-add-person',
        'person_contact_manager_add_person_page'
    );

    add_submenu_page(
        'person-contact-manager',
        'Edit Person',
        'Edit a Person',
        'manage_options',
        'person-contact-manager-edit-person',
        'person_contact_manager_edit_person_page'
    );
}

// Callback function for list people page
function person_contact_manager_list_people_page()
{
    // Include view file for listing people
    include_once plugin_dir_path(__FILE__) . 'views/list-person.php';
}

// Callback function for dashboard page
function person_contact_manager_add_person_page()
{
    // Include view file for the dashboard
    include_once plugin_dir_path(__FILE__) . 'views/add-person.php';
}

// Callback function for add new person page
function person_contact_manager_edit_person_page()
{
    // Include view file for adding a new person
    include_once plugin_dir_path(__FILE__) . 'views/edit-person.php';
}

// Hook menu creation function
add_action('admin_menu', 'add_to_menu');


// Register custom REST API endpoint for adding a person
function register_add_person_rest_route()
{
    register_rest_route('person-contact-manager', '/add-person', array(
        'methods' => 'POST',
        'callback' => 'handle_add_person_request',
    ));
}
add_action('rest_api_init', 'register_add_person_rest_route');

// Callback function to handle adding a person
function handle_add_person_request($request)
{
    $name = $request->get_param('name');
    $email = $request->get_param('email');

    // Perform necessary validation and database operations to add the person
    // Replace this with your actual logic
    if (strlen($name) > 5 && is_email($email)) {

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $persons_table = $wpdb->prefix . 'persons';
        $wpdb->insert(
            $persons_table,
            array(
                'name' => $name,
                'email' => $email
            ),
            array(
                '%s', // name
                '%s' // email
            )
        );
    } else {
        return new WP_Error('invalid_email', 'Invalid email address.', array('status' => 400));
    }

    // Return the ID of the added person
    return new WP_REST_Response($person_id, 200);
}
