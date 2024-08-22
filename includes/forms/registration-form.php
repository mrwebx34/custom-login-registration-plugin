<!-- registration-form.php -->
<div class="registration-form py-md-5 py-4">
    <div class="container">
        <div class="col-9 mx-auto">
            <form id="registration-form" action="" method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" class="form-control">

                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control">

                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control">

                <button type="submit" name="register_submit" class="btn btn-primary mt-3 rounded-0">Register</button>
            </form>
        </div>
    </div>
</div>
<!-- <script>

document.addEventListener("DOMContentLoaded", function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const registerSubmitButton = document.getElementById('register_submit');

    function enableSubmitButton() {
        registerSubmitButton.disabled = !(nameInput.value && emailInput.value && passwordInput.value);
    }

    nameInput.addEventListener('input', enableSubmitButton);
    emailInput.addEventListener('input', enableSubmitButton);
    passwordInput.addEventListener('input', enableSubmitButton);
});
</script> -->