<?php
// contact.php

// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$dbname = "user_support";

// Establish database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data to prevent security issues
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    // Basic validation: Check if any of the fields are empty
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "error: Please fill in all the fields.";
        exit(); // Stop execution if any field is empty
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "error: Invalid email format.";
        exit(); // Stop execution if email is invalid
    }

    // Constraint: Check if the name contains numbers
    if (preg_match('/\d/', $name)) {
        echo "error: Name cannot contain numbers.";
        exit();
    }

    // SQL query to insert data into the 'users' table
    $sql = "INSERT INTO users (Name, email, subject, Message) VALUES (?, ?, ?, ?)";

    // Prepare the SQL statement.  This is crucial for preventing SQL Injection attacks.
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind the parameters to the prepared statement.  'ssss' indicates that all parameters are strings.
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        // Execute the prepared statement
        if ($stmt->execute()) {
            echo "success"; // Indicate successful data insertion
        } else {
            // Display specific error message from the database if insertion fails
            echo "error: " . $stmt->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Display specific error message from the database if statement preparation fails
        echo "error: " . $conn->error;
    }
} else {
    // If the script is accessed directly without a POST request, return an error
    echo "error: Invalid request.";
}

// Close the database connection
$conn->close();
?>
