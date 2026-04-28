<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignIn</title>
    <link rel="stylesheet" href="Login_styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="background">
        <img src="Images\1598226.jpg" alt="background-image">
        <div class="login-container" id="signIn">
            <div class="login-image">
                <img src="Images\IMG1.png" alt="login-image">
            </div>
            <div>
                <h2>Sign In</h2>
                <form method="post" action="register.php">
                    <div class="input-group">
                        <input type="email" name="email" id="email" placeholder="Email">
                        <span class="icon">&#128231;</span>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password">
                        <span class="icon">🔒</span>
                        <span class="pass-see" id="togglePassword">
                            <!-- From Uiverse.io by catraco -->
                            <label class="container">
                                <input type="checkbox" checked="checked" id="togglePasswordCheckbox">
                                <!--checked="checked"-->
                                <svg class="eye" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                    <path
                                        d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                        style="display: inline;"></path>
                                </svg>
                                <svg class="eye-slash" xmlns="http://www.w3.org/2000/svg" height="1em"
                                    viewBox="0 0 640 512">
                                    <path
                                        d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"
                                        style="display: inline;"></path>
                                </svg>
                            </label>
                        </span>
                    </div>
                    <div class="options">
                        <label class="label-style"><input type="checkbox"> Remember me</label>
                        <a href="forgot.php">Forgot password?</a>
                    </div>
                    <input type="submit" class="btn" value="Sign In" name="signIn">
                    <div class="links">
                        <p>Don't have account yet?</p>
                        <button id="signUpButton">Sign Up</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="login-container1" id="signup" style="display:none;">
            <h2>Register</h2>
            <form method="post" action="register.php">
                <div class="input-group">
                    <input type="text" name="fName" id="fName" placeholder="First Name" required>
                    <span class="icon">&#128100;</span>
                </div>
                <div class="input-group">
                    <input type="text" name="lName" id="lName" placeholder="Last Name" required>
                    <span class="icon">&#128100;</span>
                </div>
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <span class="icon">&#128231;</span>
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="icon">&#128274;</span>
                    <span class="pass-see" id="togglePassword">
                        <!-- From Uiverse.io by catraco -->
                        <label class="container">
                            <input type="checkbox" checked="checked" id="togglePasswordCheckbox">
                            <!--checked="checked"-->
                            <svg class="eye" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 576 512">
                                <path
                                    d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"
                                    style="display: inline;"></path>
                            </svg>
                            <svg class="eye-slash" xmlns="http://www.w3.org/2000/svg" height="1em"
                                viewBox="0 0 640 512">
                                <path
                                    d="M38.8 5.1C28.4-3.1 13.3-1.2 5.1 9.2S-1.2 34.7 9.2 42.9l592 464c10.4 8.2 25.5 6.3 33.7-4.1s6.3-25.5-4.1-33.7L525.6 386.7c39.6-40.6 66.4-86.1 79.9-118.4c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C465.5 68.8 400.8 32 320 32c-68.2 0-125 26.3-169.3 60.8L38.8 5.1zM223.1 149.5C248.6 126.2 282.7 112 320 112c79.5 0 144 64.5 144 144c0 24.9-6.3 48.3-17.4 68.7L408 294.5c8.4-19.3 10.6-41.4 4.8-63.3c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3c0 10.2-2.4 19.8-6.6 28.3l-90.3-70.8zM373 389.9c-16.4 6.5-34.3 10.1-53 10.1c-79.5 0-144-64.5-144-144c0-6.9 .5-13.6 1.4-20.2L83.1 161.5C60.3 191.2 44 220.8 34.5 243.7c-3.3 7.9-3.3 16.7 0 24.6c14.9 35.7 46.2 87.7 93 131.1C174.5 443.2 239.2 480 320 480c47.8 0 89.9-12.9 126.2-32.5L373 389.9z"
                                    style="display: inline;"></path>
                            </svg>
                        </label>
                    </span>
                </div>
                <input type="submit" class="btn" value="Sign Up" name="signUp">
            </form>
            <div class="links">
                <p>Already Have an Account?</p>
                <button id="signInButton">Sign In</button>
            </div>
            <!-- </div> -->
        </div>
    </div>
    <script src="script.js"></script>
    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    const eyeIcon = document.querySelector('.eye');
    const eyeSlashIcon = document.querySelector('.eye-slash');

    passwordField.type = 'password';
    eyeIcon.style.display = 'none';
    eyeSlashIcon.style.display = 'inline';

    document.getElementById('togglePasswordCheckbox').addEventListener('change', function() {
        if (this.checked) {
            passwordField.type = 'password';
            eyeIcon.style.display = 'none';
            eyeSlashIcon.style.display = 'inline';
        } else {
            passwordField.type = 'text';
            eyeIcon.style.display = 'inline';
            eyeSlashIcon.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Select password fields and their corresponding checkboxes
        const passwordFields = document.querySelectorAll('[type="password"]');
        const toggleCheckboxes = document.querySelectorAll('[id$="togglePasswordCheckbox"]');

        toggleCheckboxes.forEach((checkbox, index) => {
            const eyeIcon = checkbox.parentElement.querySelector('.eye');
            const eyeSlashIcon = checkbox.parentElement.querySelector('.eye-slash');

            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    passwordFields[index].type = 'password';
                    eyeIcon.style.display = 'none';
                    eyeSlashIcon.style.display = 'inline';
                } else {
                    passwordFields[index].type = 'text';
                    eyeIcon.style.display = 'inline';
                    eyeSlashIcon.style.display = 'none';
                }
            });
        });
    });
    </script>
   <?php if (isset($_GET['registration']) && $_GET['registration'] === 'success'): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Great!',
            text: 'Registration successful! Please log in.',
            confirmButtonText: 'OK',
        confirmButtonColor: '#2ecc71', // Green color for the OK button
        width: '480px', // Smaller width for the Swal box
        });
    </script>
<?php endif; ?>
</body>

</html>