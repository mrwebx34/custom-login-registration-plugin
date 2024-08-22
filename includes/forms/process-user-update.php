<?php
$wp_load_path = trailingslashit(ABSPATH) . 'wp-load.php';

if (file_exists($wp_load_path)) {
    include_once($wp_load_path);
} else {
    echo '<p style="color: red;">Error: wp-load.php not found in the WordPress root directory.</p>';
    echo '<p>Full Path Attempted: ' . esc_html($wp_load_path) . '</p>';
    echo '<p>Check file permissions and the correct WordPress root directory.</p>';
    exit;
}

// session_start();
if (isset($_POST['submit_update'])) {
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);

    $user_id = get_user_id_by_email($email);

    if (!$user_id) {
        echo '<p style="color: red;">Error: Invalid email address. Please try again.</p>';
        exit;
    }
    $_SESSION['sessionkey'] = $user_id;
    $upload_dir = wp_upload_dir();
    $image_file = $_FILES['image'];
    if ($user_id == 1) {
        $_SESSION['restrict_admin_access'] = true;
    }
   
    $image_path = '';
    $document_path = '';

    // Handle image upload
    // if ($image_file['error'] === 0) {
    //     $image_path = $upload_dir['path'] . '/' . basename($image_file['name']);
    //     move_uploaded_file($image_file['tmp_name'], $image_path);
    // }
    if ($image_file['error'] === 0) {
        $image_path = $upload_dir['path'] . '/' . basename($image_file['name']);
        move_uploaded_file($image_file['tmp_name'], $image_path);
    }
    $document_file = $_FILES['document'];

    // Handle document upload
    // if ($document_file['error'] === 0) {
    //     $document_path = $upload_dir['path'] . '/' . basename($document_file['name']);
    //     move_uploaded_file($document_file['tmp_name'], $document_path);
    // }

    if ($document_file['error'] === 0) {
        $document_path = $upload_dir['path'] . '/' . basename($document_file['name']);
        move_uploaded_file($document_file['tmp_name'], $document_path);
    }

    $user_data = array(
        'ID' => $user_id,
        'display_name' => $name,
        'user_email' => $email,
    );

    update_or_insert_user_data($user_data, $image_path, $document_path);
    $_SESSION['form_submitted'] = true;
    echo '<script>
    alert("Form submitted successfully!");
    setTimeout(function() {
        window.location.href = "' . site_url('/') . '";
    }, 500);
</script>';
    exit;
}

function get_user_id_by_email($email) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data';

    $user_id = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT id FROM $table_name WHERE email = %s",
            $email
        )
    );

    return $user_id;
}


function update_or_insert_user_data($user_data, $image_path = '', $document_path = '') {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data';

    // Check if the user record already exists in the database
    $user_exists = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE id = %d",
            $user_data['ID']
        )
    );

    // Update or insert the user data based on whether the user exists
    if ($user_exists) {
        update_user_data($user_data, $image_path, $document_path);
    } else {
        insert_user_data($user_data, $image_path, $document_path);
    }
}

function update_user_data($user_data, $image_path = '', $document_path = '') {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data';

    $update_data = array(
        'name' => $user_data['display_name'],
        'email' => $user_data['user_email'],
    );

  
    if ($image_path !== '') {
        $update_data['image'] = ltrim($image_path, '/');
    }

    if ($document_path !== '') {
        $update_data['document'] = ltrim($document_path, '/');
    }

    $wpdb->update(
        $table_name,
        $update_data,
        array('id' => $user_data['ID']),
        array('%s', '%s', '%s', '%s'),
        array('%d')
    );
}

// Custom function to insert user data
function insert_user_data($user_data, $image_path = '', $document_path = '') {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data';

    // Get the base URL for uploads
    $upload_base_url = wp_upload_dir()['baseurl'];

    // Construct URLs using the base URL
    $image_url = $image_path ? esc_url($upload_base_url . '/' . pathinfo($image_path, PATHINFO_BASENAME)) : '';
    $document_url = $document_path ? esc_url($upload_base_url . '/' . pathinfo($document_path, PATHINFO_BASENAME)) : '';

    $wpdb->insert(
        $table_name,
        array(
            'id' => $user_data['ID'],
            'name' => $user_data['display_name'],
            'email' => $user_data['user_email'],
            'image' => $image_url,
            'document' => $document_url,
            'timestamp' => current_time('mysql'), // assuming you have a timestamp column
        ),
        array('%d', '%s', '%s', '%s', '%s', '%s')
    );
}



function get_user_data_from_custom_table($user_id)
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data';


    $user_data = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $user_id),
        ARRAY_A
    );

    return $user_data;
}

?>
