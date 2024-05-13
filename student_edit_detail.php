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
    $new_name = $_POST["name"];
    $new_father_name = $_POST["father_name"];
    $new_semester = $_POST["semester"];
    $new_birthday = $_POST["birthday"];

    $sql = "UPDATE studentinfo SET name=?, fname=?, semester=?, dob=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $new_name, $new_father_name, $new_semester, $new_birthday, $username);

    if ($stmt->execute()) {
        $_SESSION["message"] = "User details updated successfully.";
        header("Location: student_edit.php");
        exit();
    } else {
        $_SESSION["message"] = "Error updating user details: " . $conn->error;
        header("Location: student_edit.php");
        exit();
    }
}

// Close the connection
$conn->close();
?>