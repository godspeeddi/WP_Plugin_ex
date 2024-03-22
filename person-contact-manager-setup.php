<?php





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
            email varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        // SQL to create contacts table
        $sql_contacts = "CREATE TABLE IF NOT EXISTS $contacts_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            person_id mediumint(9) NOT NULL,
            country_code varchar(10) NOT NULL,
            is_active boolean NOT NULL DEFAULT 1,
            number varchar(9) NOT NULL,
            PRIMARY KEY  (id),
            FOREIGN KEY (person_id) REFERENCES $persons_table(id)
        ) $charset_collate;";

        // Execute SQL queries
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_persons);
        dbDelta($sql_contacts);
        
    } 
