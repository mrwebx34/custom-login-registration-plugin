// Add this in your display_registered_users function, or in your JavaScript file

// custom.js

jQuery(document).ready(function($) {
    $('.delete-user').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        var confirmation = confirm('Are you sure you want to delete this user?');

        if (confirmation) {
            // Send an AJAX request to the WordPress AJAX handler
            $.ajax({
                type: 'POST',
                url: customAjax.ajaxurl, // Use the AJAX URL passed from wp_localize_script
                data: {
                    action: 'delete_user_data', // Action name for the AJAX handler
                    user_id: userId,
                },
                success: function(response) {
                    // Handle the response from the server
                    var data = $.parseJSON(response);
                    if (data.success) {
                        // Reload the page or update the UI as needed
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                }
            });
        }
    });
});

