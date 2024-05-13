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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_profile_picture"])) {

    // Check if the uploaded file size exceeds the capacity of a longblob field (approx. 4 GB)
    $max_file_size = 9 * 1024 * 1024; // 4 mb in bytes
    if ($_FILES['profile_picture']['size'] > $max_file_size) {
        // File size exceeds the limit, display an error message
        $_SESSION["message"] = "File size exceeds the maximum limit.";
        header("Location: student_edit.php");
        exit();
    } else {
        // File size is within the limit, proceed with uploading
        try {
            $profile_picture = file_get_contents($_FILES['profile_picture']['tmp_name']);
        } catch (\Throwable $th) {
            $_SESSION["message"] = "Select a picture first";
            header("Location: student_edit.php");
            exit();
        }

        $sql = "UPDATE studentinfo SET profile=? WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $profile_picture, $username);
        if ($stmt->execute()) {
            $_SESSION["message"] = "Profile picture uploaded successfully.";
            header("Location: student_edit.php");
            exit();
        } else {
            $_SESSION["message"] = "Error uploading profile picture: " . $conn->error;
            header("Location: student_edit.php");
            exit();
        }
    }
}

?>