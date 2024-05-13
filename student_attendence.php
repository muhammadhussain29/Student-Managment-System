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

// Fetch user data from the database based on the username stored in the session
$username = $_SESSION["username"];

$sql = "SELECT * FROM studentinfo WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Output data of the user
    $row = $result->fetch_assoc();

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <!-- Font awsome link for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
            integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- bootstrap css -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Custom css -->
        <link rel="stylesheet" href="style/general.css">
        <link rel="stylesheet" href="style/student_course_attendence.css">
    </head>

    <body>
        <!-- Navbar Start -->
        <nav class="navbar bgcol-white ">
            <div class="container">
                <!-- logo image wraped in a tag to make it work like a link to index page -->
                <a class="nav-logo d-flex justify-content-center align-items-end text-decoration-none"
                    href="student_dashboard.php">
                    <img src="numl_logo.png" alt="logo" height="45">
                    <h3 class="logo-heading px-2 d-sm-block d-none">student dashboard</h3>
                </a>
                <!-- Button visible for small screens -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Nav links -->
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="nav-list d-flex flex-column flex-md-row justify-content-end mt-3">
                        <li><a class="nav-list-item d-block px-3 px-md-auto py-1" href="student_edit.php">Edit Profile</a>
                        </li>
                        <hr class="d-md-none d-block m-0">
                        <li><a class="nav-list-item d-block px-3 px-md-auto py-1"
                                href="student_attendence.php">Attendence</a></li>
                        <hr class="d-md-none d-block m-0">
                        <li><a class="nav-list-item d-block px-3 px-md-auto py-1" href="student_course.php">Update
                                Course</a></li>
                        <hr class="d-md-none d-block m-0">
                        <li>
                            <form action="logout.php" method="post"><button
                                    class="nav-list-item d-block px-3 px-md-auto py-1" type="submit">Logout</button></form>
                        </li>
                        <hr class="d-md-none d-block m-0">
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Navbar End -->

        <!-- Profile starts-->

        <div class="container my-3">
            <div class="inf-content p-2">
                <div class="row">
                    <div class="col-md-4 d-flex my-3 justify-content-center align-items-center">
                        <?php
                        if ($row["profile"]) {
                            echo '<img class="img-circle img-thumbnail" src="data:image/jpeg;base64,' . base64_encode($row["profile"]) . '" alt="profile">';
                        } else {
                            // If no profile picture is found in the database, display a default image
                            echo '<img class="img-circle img-thumbnail" src="profile.jpg" alt="profile">';
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <strong class="heading-2 col-blue">Student Info</strong><br>
                        <div class="table-responsive">
                            <table class="table my-2">
                                <tbody>
                                    <tr>
                                        <td><strong> Username </strong></td>
                                        <td class="txt-1 col-blue"> <?php echo $row["username"] ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong> Name </strong></td>
                                        <td class="txt-1 col-blue"><?php echo $row["name"] ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong> Department </strong></td>
                                        <td class="txt-1 col-blue"> <?php echo $row["department"] ?> </td>
                                    </tr>
                                    <tr>
                                        <td><strong> Semester </strong></td>
                                        <td class="txt-1 col-blue"> <?php echo $row["semester"] ?>th </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile ends -->
        <?php

        $course = array();
        if ($row["course1"] != null) {
            array_push($course, $row["course1"]);
        }
        if ($row["course2"] != null) {
            array_push($course, $row["course2"]);
        }
        if ($row["course3"] != null) {
            array_push($course, $row["course3"]);
        }
        if ($row["course4"] != null) {
            array_push($course, $row["course4"]);
        }
        if ($row["course5"] != null) {
            array_push($course, $row["course5"]);
        }
        if ($row["course6"] != null) {
            array_push($course, $row["course6"]);
        }

} else {
    echo "No user found with the username: " . $username;
}

for ($i = 0; $i < count($course); $i++) {



    ?>
        <!-- Attendence Chart Start -->
        <section class="container my-4">
            <h3 class="heading-2 col-blue m-0"><?php echo $course[$i] ?></h3>
            <div class="table__body">
                <table>
                    <thead>
                        <?php
                        $sql = "SELECT * FROM attendence WHERE username=? AND courseid=?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ss", $username, $course[$i]);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<tr>";
                            echo "<th>Course Name<span class=\"icon-arrow\">&UpArrow;</span></th>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<th>" . $row["date"] . "<br>" . $row["timing"] . "<span class=\"icon-arrow\">&UpArrow;</span></th>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </thead>
                    <tbody>

                        <?php
                        $stmt->execute();
                        $result = $stmt->get_result();
                        // Check if there are any rows returned
                        if ($result->num_rows > 0) {
                            // Output data of each row
                            echo '<tr>';
                            echo "<td>" . $course[$i] . "</td>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<td>" . strtoupper($row["status"]) . "</td>";
                            }
                            echo "</tr>";
                        } else {
                            echo "<tr><td>No Record Found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
        <?php
}
?>
    <!-- Attendence Chart End -->
    <!-- Footer -->
    <footer class="container mt-4 mb-3">
        <hr class="m-0">
        <div class="txt-1">&#169;Copyrights 2024 by Muhammad Hussain | All Rights Reserved</div>
    </footer>
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>

</html>