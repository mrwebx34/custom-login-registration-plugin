<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// process-registration.php

$wp_load_path = trailingslashit(ABSPATH) . 'wp-load.php';

// Check if the file exists before including it
if (file_exists($wp_load_path)) {
    include_once($wp_load_path);
} else {
    // Display an error message and additional information
    echo '<p style="color: red;">Error: wp-load.php not found in the WordPress root directory.</p>';
    echo '<p>Full Path Attempted: ' . esc_html($wp_load_path) . '</p>';
    echo '<p>Check file permissions and the correct WordPress root directory.</p>';
    exit;
}
add_action('init', 'process_registration');
function process_registration() {
if (isset($_POST['register_submit'])) {
    // Sanitize and validate input
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        echo '<p style="color: red;">Please fill in all fields.</p>';
    } else {
        // Save data to the database
        save_user_data($name, $email, md5($password));

       
        echo '<p style="color: green;">Registration successful! You can now log in with your email and password.</p>';
        echo '<script>setTimeout(function(){ window.location.href = "' . site_url('/login') . '"; }, 500);</script>';
        
        exit;
    }
}
}
function generate_custom_user_id() {
  
    return uniqid('custom_user_', true);
}
// Function to save user data to the database
function save_user_data($name, $email, $password) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';
    $custom_user_id = generate_custom_user_id();

    $wpdb->insert(
        $table_name,
        array(
             'id' =>  $custom_user_id,
            'name' => $name,
            'email' => $email,
            'password' => $password,
        )
    );
}
?>
