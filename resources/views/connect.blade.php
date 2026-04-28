<?php
$host = "localhost";
$Admin = "root";
$password = ""; 
$dbname = "user_auth"; 

$conn = new mysqli($host, $Admin, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>