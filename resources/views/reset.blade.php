<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | FarmForecast</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg,rgb(194, 218, 240), #00f2fe); /* Gradient background */
            color: #333;
        }

        /* Central Box Styling */
        form {
            background: #ffffff; /* White background for contrast */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Subtle shadow */
            width: 100%;
            max-width: 400px;
            text-align: center; /* Center-align contents */
        }

        /* Form Heading */
        form h1 {
            font-size: 1.8rem;
            color: #007bff; /* Blue color for heading */
            margin-bottom: 20px;
        }

        /* Circular Icon */
        form .icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #007bff; /* Blue circle */
            margin: 0 auto 20px; /* Center and add margin */
            display: flex;
            justify-content: center;
            align-items: center;
            color: white; /* White icon */
            font-size: 2rem; /* Icon size */
        }

        .input-container {
            position: relative;
            width: 90%;
            margin: 10px auto;
        }

        .input-container input {
            width: 100%;
            padding: 12px;
            padding-right: 40px; /* Add space for the eye icon */
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .input-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        /* Input Fields */
        form input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box; /* Consistent padding */
        }
        form button {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            box-sizing: border-box; /* Consistent padding */
        }

        form input[type="password"]:focus {
            border-color: #4facfe; /* Focus state color */
            outline: none;
            box-shadow: 0 0 5px rgba(79, 172, 254, 0.5); /* Subtle glow effect */
        }

        /* Buttons */
        form button {
            width: 90%;
            background-color: #007bff; /* Blue button */
            border: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
            border-radius: 5px;
            padding: 12px;
        }

        form button.cancel {
            background: #ddd; /* Gray button */
            color: #333; /* Dark gray text */
        }

        form button:hover {
            background-color: #0056b3; /* Darker blue hover */
            transform: scale(1.05); /* Slight zoom effect */
        }

        form button.cancel:hover {
            background: #bbb; /* Hover effect for cancel button */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                width: 90%;
            }
        }

    </style>
</head>

<body>
    <?php
        require("connect.php");
    if(isset($_GET['email']) && isset($_GET['token'])){
        date_default_timezone_set('Asia/Kolkata');
        $date = date('Y-m-d');
        $email = $_GET['email'];
        $token = $_GET['token'];
        $query = "SELECT * FROM user WHERE email='$email' AND resettoken='$token' AND resettokenexpire = '$date'";
        $result = mysqli_query($conn,$query);
        if($result){
            if(mysqli_num_rows($result) == 1){
                echo "
                <form method='post'>
                    <div class='icon'>
                        <i class='fas fa-key'></i> <!-- Font Awesome key icon -->
                    </div>
                    <h1>Reset Password</h1>
                    <!-- Password Field -->
                    <div class='input-container'>
                        <input type='password' name='password' id='password' placeholder='🔒 New Password'>
                        <i class='fas fa-eye' id='togglePassword'></i>
                    </div>
                    <!-- Confirm Password Field -->
                    <div class='input-container'>
                        <input type='password' name='cpassword' id='cpassword' placeholder='🔒 Confirm Password'>
                    </div>
                    <button type='submit' name='reset'>UPDATE</button>
                    <input type='hidden' name='email' value='$_GET[email]'>
                    <button type='button' class='cancel' onclick=\"window.location.href='index.php'\">Cancel</button>
                </form>
                ";
            }
            else{
                echo "
                <script>
                alert('Invalid or Link Expired!');
                window.location.href='index.php';
                </script>
                ";
            }
        }
        else {
            echo "
            <script>
            alert('Server Down! try again later');
            window.location.href='index.php';
            </script>
            ";
        }
    }
    ?>

<?php
 if(isset($_POST['reset'])){
     $email = $_POST['email'];
     $password = $_POST['password'];
     $cpassword = $_POST['cpassword'];
     if($password == $cpassword){
         $password = md5($password);
         $query = "UPDATE user SET password='$password',resettoken=NULL,resettokenexpire=NULL WHERE email='$email'";
         if(mysqli_query($conn,$query)){
             echo "
             <script>
             alert('Password Updated Successfully!');
             window.location.href='index.php';
             </script>
             ";
         }
         else{
             echo "
             <script>
             alert('Password not Updated!');
             window.location.href='index.php';
             </script>
             ";
         }
     }
     else{
         echo "
         <script>
         alert('Password not matched!');
         window.location.href='index.php';
         </script>
         ";
     }
 }
?>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function () {
        const type = password.type === 'password' ? 'text' : 'password';
        password.type = type;
        this.classList.toggle('fa-eye-slash');
    });
</script>
</body>

</html>