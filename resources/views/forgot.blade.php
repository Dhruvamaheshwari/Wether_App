  
<?php
require("connect.php");


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendMail($email,$token){
    require 'PHPMailer/PHPMailer.php';
    require 'PHPMailer/SMTP.php';
    require 'PHPMailer/Exception.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = '';                     //SMTP username
        $mail->Password   = '';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom('your-email', 'Admin');
        $mail->addAddress($email, 'User');     //Add a recipient
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = 'We got a Request from you to reset the <b>password</b> <br> 
        Click on the link to reset your password: <a href="http://localhost/K23BG/INT220_PROJECT/Login/reset.php?email='.$email.'&token='.$token.'">Reset Password</a>';
        ;
    
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // return false;
    }

}


if (isset($_POST['forgot'])) {
    $email = $_POST['email'];

    // Check if email exists in database
    $query = "SELECT * FROM user WHERE email = '$email'";
    // $result = $conn->query($query); OOp concept
    $result = mysqli_query($conn,$query);

    if ($result)
    {
        if (mysqli_num_rows($result) == 1) {
            $token = bin2hex(random_bytes(16)); // bin2hex is used to convert random bytes to hexadecimal to store in database
            date_default_timezone_set('Asia/Kolkata');
            $date = date("Y-m-d");
    
            // Update user's password reset token
            // $query = "UPDATE user SET password_reset_token = '$token' WHERE email = '$email'";
            $query = "UPDATE `user` SET `resettoken`='$token',`resettokenexpire`='$date' WHERE `email`='$email'";
            if (mysqli_query($conn,$query) && sendMail($email,$token)) {
                echo"
                <script>
                alert('Password reset Link sent to email');
                window.location.href='index.php';
                </script>
                ";
            }
            else{
                echo"
                <script>
                alert('Password reset Link not sent to email');
                window.location.href='index.php';
                </script>
                ";
            }
        }
    } else {
        echo "Email does not exist in our database.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot_Password_Request | FarmForecast</title>
    <link rel="stylesheet" href="Login_styles.css">
</head>
<body>
    <div class="background">
    <img src="Images\1598226.jpg" alt="background-image">
        <div class="login-container1">
            <h1 style="color:#2d5986">Reset Request</h1>
            <form method="post">
                <div class="input-group">
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <span class="icon">&#128231;</span>
                </div>
                <input type="submit" class="btn" name="forgot" value="Forgot Password">
            </form>
        </div>
    </div>
</body>
</html>
