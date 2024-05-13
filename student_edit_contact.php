<?php
session_start(); // Start the session

// Check if user is not logged in
if (!isset($_SESSION["username"])) {
    // Redirect to login page
    header("Location: index.php");
    exit();
}

// Check if user has the student role
if ($_SESSION["role"] !== "student") {
    // Redirect to unauthorized page
    header("Location: unauthorized.php");
    exit();
}
// Assuming your database credentials
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "student_managment"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch user data from the database based on the username stored in the session
$username = $_SESSION["username"];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user details in the database
    $username = $_SESSION["username"];
    $new_email = $_POST["email"];
    $new_phone = $_POST["phone"];
    $new_current_address = $_POST["current_address"];
    $new_postal_address = $_POST["postal_address"];

    $sql = "UPDATE studentinfo SET email=?, phone=?, address=?, postal=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $new_email, $new_phone, $new_current_address, $new_postal_address, $username);

    if ($stmt->execute()) {
        $_SESSION["message"] = "Contact details updated successfully.";
        header("Location: student_edit.php");
        exit();
    } else {
        $_SESSION["message"] = "Error updating Conatct details: " . $conn->error;
        header("Location: student_edit?error=1");
        exit();
    }
}

// Close the connection
$conn->close();
?>