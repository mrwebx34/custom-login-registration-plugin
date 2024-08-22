<?php
// Include any necessary dependencies or configurations

// Define functions related to forms
function render_registration_form() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'registration-form.php');
    return ob_get_clean();
}

function render_login_form() {
    ob_start();
    include(plugin_dir_path(__FILE__) . 'login-form.php');
    return ob_get_clean();
}
?>
