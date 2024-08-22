<?php
// Function to display user data after login
function display_user_data() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_user_data';

        $user_data = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id = $user_id");

        if ($user_data) {
            // Display user data
            echo '<h2>Your User Data</h2>';
            echo '<p><strong>Name:</strong> ' . $user_data->name . '</p>';
            echo '<p><strong>Email:</strong> ' . $user_data->email . '</p>';
  
            // Add more fields as needed
        } else {
            echo '<p>No user data found.</p>';
        }
    }
}

// Function to display admin-approved user data
function display_approved_user_data() {
    if (current_user_can('manage_options')) { // Check if the current user is an admin
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_user_data';

        $approved_users = $wpdb->get_results("SELECT * FROM $table_name WHERE form_status = 'approved'");

        if ($approved_users) {
            // Display approved user data
            echo '<h2>Approved User Data</h2>';
            foreach ($approved_users as $user_data) {
                echo '<p><strong>Name:</strong> ' . $user_data->name . '</p>';
                echo '<p><strong>Email:</strong> ' . $user_data->email . '</p>';
               
                // Add more fields as needed
                echo '<hr>';
            }
        } else {
            echo '<p>No approved user data found.</p>';
        }
    }
}
?>
