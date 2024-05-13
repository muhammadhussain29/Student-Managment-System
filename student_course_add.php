<?php
session_start(); // Start the session

// Check if user is not logged in
if (!isset($_SESSION["username"])) {
    // Return an error response
    echo json_encode(array('error' => 'User not logged in.'));
    exit();
}

// Check if user has the student role
if ($_SESSION["role"] !== "student") {
    // Return an error response
    echo json_encode(array('error' => 'Unauthorized access.'));
    exit();
}

// Check if all required course details are provided via POST request
if (isset($_POST['code']) && isset($_POST['name']) && isset($_POST['semester'])) {
    // Extract course details from POST data
    $code = $_POST['code'];
    $name = $_POST['name'];
    $semester = $_POST['semester'];

    // Database credentials
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "student_managment";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        // Return an error response
        echo json_encode(array('error' => 'Connection failed: ' . $conn->connect_error));
        exit();
    }

    // Fetch username from the session
    $username = $_SESSION["username"];

    // Check if there is space to add another course
    $sql_check_space = "SELECT course1,course2,course3,course4,course5,course6 FROM studentinfo WHERE username=?";
    $stmt_check_space = $conn->prepare($sql_check_space);
    $stmt_check_space->bind_param("s", $username);
    $stmt_check_space->execute();
    $result_check_space = $stmt_check_space->get_result();

    if ($result_check_space->num_rows > 0) {
        $row_check_space = $result_check_space->fetch_assoc();
        $courses = array(
            "course1" => $row_check_space["course1"],
            "course2" => $row_check_space["course2"],
            "course3" => $row_check_space["course3"],
            "course4" => $row_check_space["course4"],
            "course5" => $row_check_space["course5"],
            "course6" => $row_check_space["course6"]
        );
        // Check if any course slot is available
        if (in_array($code, $courses)) {
            // Course is already subscribed by the user
            $_SESSION["message"] = "You are already subscribed to this course.";
            // echo json_encode(array('error' => 'You are already subscribed to this course.'));
            exit();
        }
        if (in_array(null, $courses)) {

            // Prepare and execute the SQL statement to add the course to the student's enrolled courses
            $sql_add_course = "UPDATE studentinfo SET ";
            foreach ($courses as $key => $value) {
                if ($value == null) {
                    $sql_add_course .= $key . "=?";
                    break;
                }
            }
            $sql_add_course .= " WHERE username=?";
            $stmt_add_course = $conn->prepare($sql_add_course);
            $stmt_add_course->bind_param("ss", $code, $username);

            if ($stmt_add_course->execute()) {
                // Course added successfully
                $_SESSION["message"] = "Course Added";
                // echo json_encode(array('success' => true));
            } else {
                // Error occurred while adding the course
                $_SESSION["message"] = "Failed to add course.";
                // echo json_encode(array('error' => 'Failed to add course.'));
            }

            // Close statement
            $stmt_add_course->close();
        } else {
            // Maximum number of courses reached
            $_SESSION["message"] = "You have selected the maximum number of courses.";
            // echo json_encode(array('error' => 'You have selected the maximum number of courses.'));
        }
    } else {
        // No user found with the username
        $_SESSION["message"] = "No user found with the username:" . $username;
        // echo json_encode(array('error' => 'No user found with the username: ' . $username));
    }

    $stmt_check_course->close();
    // Close statement
    $stmt_check_space->close();
    // Close database connection
    $conn->close();
} else {
    // Course details not provided
    echo json_encode(array('error' => 'Incomplete course details.'));
}
?>