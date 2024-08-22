<?php
// user-update-form.php

if (isset($_SESSION['form_submitted']) && $_SESSION['form_submitted'] === true) {
    echo '<p style="color: green;">Form submitted successfully!</p>';
    echo '<a href="' . site_url('/') . '">Logout</a>';
} else {
    $user_id = isset($_SESSION['custom_user_id']) ? $_SESSION['custom_user_id'] : 0;
    $user_data = get_user_data_from_custom_table($user_id);

    if ($user_data) {
        $name = esc_attr($user_data['name']);
        $email = esc_attr($user_data['email']);
    } else {
        $name = '';
        $email = '';
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-9 mx-auto">
            <form id="user-update-form" method="post" enctype="multipart/form-data">

                <label for="name">Name:</label>
                <?php if ($user_data) : ?>
                    <input type="text" class="form-control" name="name" value="<?php echo esc_attr($user_data['name']); ?>">
                <?php endif; ?>

                <label for="email">Email:</label>
                <?php if ($user_data) : ?>
                    <input type="email" class="form-control" name="email" value="<?php echo esc_attr($user_data['email']); ?>">
                <?php endif; ?>


                <label for="photo">Photo:</label>
                <input type="file" class="form-control" name="image">

                <label for="document">Document:</label>
                <input type="file" class="form-control" name="document">

                <input type="submit" name="submit_update" class="btn btn-primary mt-3 rounded-0" value="Submit Update">
            </form>
            <p class="text-danger"> You Cannot Change  Email id</p>
        </div>
    </div>
</div>
<?php


?>