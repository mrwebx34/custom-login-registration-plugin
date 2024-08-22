<?php
// functions.php

function restrict_admin_access($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_user_data';

    $restrict_admin_access = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT restrict_admin_access FROM $table_name WHERE id = %d",
            $user_id
        )
    );

    return isset($restrict_admin_access) ? (bool)$restrict_admin_access : false;
}
?>
