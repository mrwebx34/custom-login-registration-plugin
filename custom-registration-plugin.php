<?php
/*
Plugin Name: Custom Registration Plugin
Description: A custom registration plugin for WordPress.Dont delete  user update page  which was created in your admin.
Version: 1.0
Author: Ranjan

*/
include_once(plugin_dir_path(__FILE__) . 'includes/database.php');


register_activation_hook(__FILE__, 'custom_registration_plugin_activate');
register_deactivation_hook(__FILE__, 'custom_registration_plugin_deactivate');


register_uninstall_hook(__FILE__, 'delete_custom_table');


// function custom_registration_plugin_activate() {

//     create_custom_table();


//     update_option('custom_registration_plugin_activated', true);
// }
function custom_registration_plugin_activate()
{
    create_custom_table();

    $page_title = 'User Updates';
    $page_content = '[custom_user_update_form]';
    $page_slug = 'user-updates';


    $page_check = get_page_by_path($page_slug);

    // If the page doesn't exist, create it
    if (!$page_check) {
        $page = array(
            'post_title'   => $page_title,
            'post_content' => $page_content,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_name'    => $page_slug,
        );

        wp_insert_post($page);
    }
}

// Activation hook
register_activation_hook(__FILE__, 'custom_registration_plugin_activate');




function custom_registration_plugin_deactivate()
{

    delete_option('custom_registration_plugin_activated');
}
add_action('admin_menu', 'custom_registration_plugin_menu');

function custom_registration_plugin_menu()
{
    add_menu_page(
        'Custom Registration Plugin',
        'Custom Registration',
        'manage_options',
        'custom-registration-plugin',
        'custom_registration_plugin_page',


    );

    add_submenu_page(
        'custom-registration-plugin',
        'Settings',
        'Settings',
        'manage_options',
        'custom-registration-settings',
        'custom_registration_plugin_settings_page'
    );
}
function custom_registration_plugin_settings_page()
{
?>
    <div class="wrap">
        <h1>Custom Registration Plugin Settings</h1>
        <p>Configure the settings for the Custom Registration Plugin.</p>

        <h2>Login Shortcode</h2>
        <p>Use the following shortcode to display the login form on a page or post:</p>
        <pre>[login_form]</pre>

        <h2>Register Shortcode</h2>
        <p>Use the following shortcode to display the registration form on a page or post:</p>
        <pre>[registration_form]</pre>
    </div>
<?php
}

function custom_registration_plugin_page()
{
?>
    <div class="wrap">
        <h1>Registered User Details</h1>

        <?php display_registered_users(); ?>
    </div>
    <?php
}


function display_registered_users()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';

    $users = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

    if (!empty($users)) {
    ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Image</th>
                    <th>Document</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo esc_html($user['name']); ?></td>
                        <td><?php echo esc_html($user['email']); ?></td>
                        <td><?php echo !empty($user['image']) ? '<img src="' . esc_url(wp_upload_dir()['baseurl'] . '/' . basename($user['image'])) . '" style="max-width: 100px; max-height: 100px;" />' : ''; ?></td>
                        <td><?php echo !empty($user['document']) ? '<a href="' . esc_url(wp_upload_dir()['baseurl'] . '/' . basename($user['document'])) . '" target="_blank">View Document</a>' : ''; ?></td>

                        <td><?php echo esc_html($user['timestamp']); ?></td>
                        <td><a href="javascript:void(0);" class="delete-user" data-userid="<?php echo esc_attr($user['id']); ?>">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <script>
            jQuery(document).ready(function($) {
                // Add click event for delete action
                $('.delete-user').on('click', function() {
                    var userId = $(this).data('userid');
                    if (confirm('Are you sure you want to delete this user?')) {

                        $.ajax({
                            type: 'POST',
                            url: ajaxurl, // WordPress AJAX URL
                            data: {
                                action: 'delete_user_action',
                                user_id: userId,
                                security: '<?php echo wp_create_nonce("delete_user_nonce"); ?>',
                            },
                            success: function(response) {
                                // Reload the page after successful deletion
                                location.reload();
                            }
                        });
                    }
                });
            });
        </script>
<?php
    } else {
        echo '<p>No registered users yet.</p>';
    }
}




// Add AJAX action for user deletion
add_action('wp_ajax_delete_user_action', 'delete_user_action_callback');

function delete_user_action_callback()
{
    check_ajax_referer('delete_user_nonce', 'security');

    if (isset($_POST['user_id'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_user_data';

        // Sanitize and get the user ID
        $user_id = absint($_POST['user_id']);

        // Perform your delete operation here
        $wpdb->delete($table_name, array('id' => $user_id), array('%d'));

        echo 'success';
    }

    wp_die();
}


// Modify this code in your main plugin file

add_action('init', 'check_admin_access');

function check_admin_access()
{
    // Start the session
    if (!session_id()) {
        session_start();
    }

    // Check if the user is logged in and has the custom_user_id session variable
    if (is_user_logged_in() && isset($_SESSION['custom_user_id'])) {
        // Get the user ID from the session
        $user_id = $_SESSION['custom_user_id'];

        // Check if the user should be restricted from accessing the admin area
        if (restrict_admin_access($user_id)) {
            // Redirect the user away from the admin area
            wp_redirect(site_url());
            exit;
        }
    }
}
add_filter('upload_dir', 'custom_upload_dir');
function custom_upload_dir($uploads)
{
    $uploads['subdir'] = '';
    $uploads['path'] = $uploads['basedir'] . '/';
    $uploads['url'] = $uploads['baseurl'] . '/';
    return $uploads;
}


function custom_enqueue_scripts()
{

    wp_enqueue_script('custom-registration', plugin_dir_url(__FILE__) . 'js/custom.js', array('jquery'), null, true);


    wp_localize_script('custom-registration', 'customAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
    wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js', array(), '3.7.1', true);
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '4.3.1', true);
    wp_enqueue_script('custom-registration', plugin_dir_url(__FILE__) . 'js/custom.js', array('jquery'), null, true);
    wp_enqueue_style('custom-registration', plugin_dir_url(__FILE__) . 'css/custom.css');
}

add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');


include_once(plugin_dir_path(__FILE__) . 'includes/shortcodes.php');
include_once(plugin_dir_path(__FILE__) . 'includes/forms.php');
include_once(plugin_dir_path(__FILE__) . 'includes/display.php');
include_once(plugin_dir_path(__FILE__) . 'includes/forms/process-registration.php');
include_once(plugin_dir_path(__FILE__) . 'includes/forms/process-login.php');
include_once(plugin_dir_path(__FILE__) . 'includes/forms/process-user-update.php');
include_once(plugin_dir_path(__FILE__) . 'includes/forms/functions.php');

?>