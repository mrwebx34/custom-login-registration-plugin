<?php 


// process-user-update.php

if (isset($_POST['submit_update'])) {
  
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);


    $current_user = wp_get_current_user();
    if ($current_user->user_email !== $email) {
       
        echo '<p style="color: red;">Error: Invalid email address. Please try again.</p>';
        exit;
    }


    $user_id = $current_user->ID;
    $user_data = array(
        'ID'           => $user_id,
        'display_name' => $name,
        'user_email'   => $email,
    );

    wp_update_user($user_data);

    
    $upload_dir = wp_upload_dir();
    $photo_file = $_FILES['photo'];
    $document_file = $_FILES['document'];

    if ($photo_file['error'] === 0) {
        $photo_path = $upload_dir['path'] . '/' . basename($photo_file['name']);
        move_uploaded_file($photo_file['tmp_name'], $photo_path);
    
        update_user_meta($user_id, 'photo_path', $photo_path);
    }

    // Handle document upload
    if ($document_file['error'] === 0) {
        $document_path = $upload_dir['path'] . '/' . basename($document_file['name']);
        move_uploaded_file($document_file['tmp_name'], $document_path);
      
        ($user_id, 'document_path', $document_path);
    }

    // Redirect the user after successful update
    // wp_redirect(site_url('/user-updates?success=true'));
    exit;
}
