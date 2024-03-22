<?php


function plugin_activation() {
    if ( ! current_user_can("manage_options") ) {
        return;
    }
}

function plugin_deactivation() {
    if ( ! current_user_can("manage_options") ) {
        return;
    }
}


add_action('init', 'create_custom_tables');


 /**
  * Create custom tables in the WordPress database.
  */
/* register_activation_hook( __FILE__, 'create_custom_tables' ); */
 function create_custom_tables(){
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        // Table names
        $persons_table = $wpdb->prefix . 'persons';
        $contacts_table = $wpdb->prefix . 'contacts';

        $sql_persons = "CREATE TABLE IF NOT EXISTS $persons_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            email varchar(255) NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // SQL to create contacts table
        $sql_contacts = "CREATE TABLE IF NOT EXISTS $contacts_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            person_id mediumint(9) NOT NULL,
            country_code varchar(10) NOT NULL,
            number varchar(9) NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY (person_id) REFERENCES $persons_table(id)
        ) $charset_collate;";

        // Execute SQL queries
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_persons);
        dbDelta($sql_contacts);
        
    } 
/**
 * Inserts a person into the custom table.
 *
 * @param string $name The name of the person.
 * @param string $email The email of the person.
 * @throws Exception If there is an error inserting the person.
 * @return void
 */
function insert_person_custom_tables($name, $email){
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
}
function update_person_custom_tables($id, $name, $email){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $persons_table = $wpdb->prefix . 'persons';
    $wpdb->update(
        $persons_table,
        array(
            'name' => $name,
            'email' => $email
        ),
        array(
            'id' => $id
        ),
        array(
            '%s', // name
            '%s' // email
        )
    );
}

function delete_person_custom_tables($id){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $persons_table = $wpdb->prefix . 'persons';
    $wpdb->delete(
        $persons_table,
        array(
            'id' => $id
        )
    );
}

function get_person_custom_tables($id){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $persons_table = $wpdb->prefix . 'persons';
    $persons = $wpdb->get_row("SELECT * FROM $persons_table WHERE id = $id");
    return $persons;
}