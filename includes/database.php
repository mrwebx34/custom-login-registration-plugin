<?php


function create_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';
    $charset_collate = $wpdb->get_charset_collate();

    // $sql = "CREATE TABLE $table_name (
    //     id mediumint(9) NOT NULL AUTO_INCREMENT,
    //     name varchar(50) NOT NULL,
    //     email varchar(100) NOT NULL,
    //     password varchar(255) NOT NULL,
    //     image varchar(255) NOT NULL DEFAULT '',
    //     document varchar(255) NOT NULL DEFAULT '',
    //     timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     restrict_admin_access tinyint(1) NOT NULL DEFAULT 0,
    //     PRIMARY KEY (id)
    // ) $charset_collate;";
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        name varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        password varchar(255) NOT NULL,
        image varchar(255) NOT NULL DEFAULT '',
        document varchar(255) NOT NULL DEFAULT '',
        timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        documents_submitted tinyint(1) DEFAULT 0,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    $wpdb->query("ALTER TABLE $table_name AUTO_INCREMENT = 2;");
}



function delete_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';

    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
?>
