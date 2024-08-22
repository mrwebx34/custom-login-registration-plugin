<!-- login-form.php -->
<div class="login-form py-md-5 py-4">
    <div class="container">
        <div class="col-9 mx-auto">
            <form id="login-form" action="" method="post">
                <label for="login_email">Email:</label>
                <input type="text" class="form-control" name="login_email" required>

                <label for="login_password">Password:</label>
                <input type="password" class="form-control" name="login_password" required>

                <input type="submit" name="login_submit" class="btn btn-primary mt-3 rounded-0" value="Login">
            </form>

        </div>
    </div>
</div>
<?php
// Add your login form processing code here, such as handling form submissions and user authentication
?>