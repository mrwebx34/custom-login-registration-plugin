<?php
// process-login.php

// Start the session
if (!session_id()) {
    session_start();
}

$wp_load_path = trailingslashit(ABSPATH) . 'wp-load.php';

if (file_exists($wp_load_path)) {
    include_once($wp_load_path);
} else {
    echo '<p style="color: red;">Error: wp-load.php not found in the WordPress root directory.</p>';
    echo '<p>Full Path Attempted: ' . esc_html($wp_load_path) . '</p>';
    echo '<p>Check file permissions and the correct WordPress root directory.</p>';
    exit;
}
include_once(plugin_dir_path(__FILE__) . 'functions.php');
add_action('init', 'process_login');
 

function process_login() {
    if (isset($_POST['login_submit'])) {
        $login_email = sanitize_email($_POST['login_email']);
        $login_password = sanitize_text_field($_POST['login_password']);

        if (!empty($login_email) && !empty($login_password)) {
            // Use your custom function to authenticate against the custom table
            $user = custom_authenticate_user($login_email, $login_password);

            if ($user) {
                // Successfully authenticated
                $_SESSION['custom_user_id'] = $user['id'];

                if (restrict_admin_access($user['id'])) {
                    // Restrict admin access
                    wp_redirect(site_url()); // Redirect to home page or another non-admin page
                } else {
                    // Allow admin access
                    wp_set_auth_cookie($user['id'], true);
                    wp_redirect(site_url('user-updates'));
                }
                exit;
            } else {
                // Login failed
                echo '<p style="color: red;">Invalid credentials. Please try again.</p>';
            }
        } else {
            // Email or password is empty
            echo '<p style="color: red;">Please provide both email and password.</p>';
        }
    }
}

// Custom function to authenticate users against the custom table
function custom_authenticate_user($email, $password) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';

    $user = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT * FROM $table_name WHERE email = %s AND password = %s",
            $email,
            $password
        ),
        ARRAY_A
    );

    return $user;
}
?>
