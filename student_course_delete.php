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

// Database credentials
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

// Fetch user data from the database based on the username stored in the session
$username = $_SESSION["username"];

// Check if course ID is provided via POST request
if (isset($_POST['courseId'])) {
    $courseId = $_POST['courseId'];


    $sql = "SELECT * FROM studentinfo WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output data of the user
        $row = $result->fetch_assoc();
        $course = array($row["course1"], $row["course2"], $row["course3"], $row["course4"], $row["course5"], $row["course6"]);
    } else {
    }
    $courseNo;
    if ($courseId == $course[0]) {
        $courseNo = "course1";
    } elseif ($courseId == $course[1]) {
        $courseNo = "course2";
    } elseif ($courseId == $course[2]) {
        $courseNo = "course3";
    } elseif ($courseId == $course[3]) {
        $courseNo = "course4";
    } elseif ($courseId == $course[4]) {
        $courseNo = "course5";
    } elseif ($courseId == $course[5]) {
        $courseNo = "course6";
    } else {
    }

    // Prepare and execute the SQL statement to update the course to null
    $sql = "UPDATE studentinfo SET " . $courseNo . " = NULL WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        // Course updated successfully
        $_SESSION["message"] = "Course Deleted";
        echo json_encode(array('success' => true));
    } else {
        // Error occurred while updating the course
        $_SESSION["message"] = "Failed to update course.";
        // echo json_encode(array('error' => 'Failed to update course.'));
    }

    // Close statement
    $stmt->close();
} else {
    // Course ID not provided
    $_SESSION["message"] = "Error";
    // echo json_encode(array('error' => 'Course ID not provided.'));
}

// Close database connection
$conn->close();
?>