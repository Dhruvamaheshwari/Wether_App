<?php
include 'connect.php';

// Register new user
if (isset($_POST['signUp'])) {
    $firstName = $conn->real_escape_string($_POST['fName']);
    $lastName = $conn->real_escape_string($_POST['lName']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $password = md5($password); 

    $checkEmail = "SELECT * FROM user WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "<script>showAlert('Email already exists!', 'error');</script>";
    } else {
        $insertQuery = "INSERT INTO user (firstName, lastName, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: index.php?registration=success");
            exit();
        }else {
            echo "<script>showAlert('Error: " . addslashes($conn->error) . "', 'error');</script>";
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $password = md5($password); 

    $sql = "SELECT * FROM user WHERE email='$email' and password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['show_login_alert'] = true;  // Seting flag here to show alert
        header("Location: homepage.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!', 'error');</script>";
        echo "<script> window.location.href = 'index.php';</script>";
    }
}
?>