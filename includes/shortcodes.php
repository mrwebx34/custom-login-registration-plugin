<?php
// Registration shortcode
function registration_form_shortcode() {
    ob_start();
    // Your registration form HTML here
    include(plugin_dir_path(__FILE__) . 'forms/registration-form.php');
    return ob_get_clean();
}

add_shortcode('registration_form', 'registration_form_shortcode');

// Login shortcode
function login_form_shortcode() {
    ob_start();
    // Your login form HTML here
    include(plugin_dir_path(__FILE__) . 'forms/login-form.php');
    return ob_get_clean();
}

add_shortcode('login_form', 'login_form_shortcode');



function custom_user_update_form_shortcode() {
    ob_start();

    // Use plugin_dir_path to get the correct path
    $form_file_path = plugin_dir_path(__FILE__) . 'forms/user-update-form.php';

    // Check if the file exists before including it
    if (file_exists($form_file_path)) {
        include($form_file_path);
    } else {
        echo '<p style="color: red;">Error: user-update-form.php not found.</p>';
    }

    return ob_get_clean();
}
add_shortcode('custom_user_update_form', 'custom_user_update_form_shortcode');

?>
