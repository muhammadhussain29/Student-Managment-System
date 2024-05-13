<?php
session_start(); // Start the session

// database credentials
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "student_managment";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is already logged in
if (isset($_SESSION["username"])) {
    // Redirect to home page based on role
    if ($_SESSION["role"] == "student") {
        header("Location: student_dashboard.php");
        exit();
    } elseif ($_SESSION["role"] == "faculty") {
        header("Location: faculty_dashboard.php");
        exit();
    }
}

// Fetch username and password from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = strtolower($_POST["username"]);
    $password = strtolower($_POST["password"]);

    // SQL query to check if the username and password exist in the database
    $sql = "SELECT * FROM login WHERE username=? AND password=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch role from the database
        $row = $result->fetch_assoc();
        $role = $row["role"];

        // Store user details in session variables
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;

        // Redirect to appropriate dashboard based on role
        if ($role == "student") {
            header("Location: student_dashboard.php");
            exit();
        } elseif ($role == "faculty") {
            header("Location: faculty_dashboard.php");
            exit();
        }
    } else {
        // Redirect back to login page if username and password are incorrect
        header("Location: index.php?error=1");
        exit();
    }
}

// Close the connection
$conn->close();

?>