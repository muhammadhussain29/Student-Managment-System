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

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $username = $_SESSION["username"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];

    // Verify if current password matches the password stored in the database
    $sql = "SELECT password FROM login WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row["password"];
        if ($current_password == $stored_password) {
            // Update password in the database
            $sql_update = "UPDATE login SET password=? WHERE username=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $new_password, $username);
            if ($stmt_update->execute()) {
                $_SESSION["message"] = "Password updated successfully.";
                header("Location: student_edit.php");
                exit();
            } else {
                $_SESSION["message"] = "Error updating password: " . $conn->error;
                header("Location: student_edit.php");
                exit();
            }
        } else {
            $_SESSION["message"] = "Current password is incorrect." . $conn->error;
            header("Location: student_edit.php");
            exit();
        }
    } else {
        $_SESSION["message"] = "Error: User not found.";
        header("Location: student_edit.php");
        exit();
    }
}

// Close the connection
$conn->close();
?>