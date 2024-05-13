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
        <link rel="stylesheet" href="style/student_dashboard.css">
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
                                Courses</a></li>
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
        <!-- Hero Section Start -->
        <div class="container p-5">
            <div class="row">
                <div class="img-container col-lg-4 text-center ">
                    <!-- PHP code to display image -->
                    <?php
                    if ($row["profile"]) {
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($row["profile"]) . '" alt="profile">';
                    } else {
                        // If no profile picture is found in the database, display a default image
                        echo '<img src="profile.jpg" alt="profile">';
                    }
                    ?>
                    <div class="heading-1 col-blue">Student Info</div>
                    <hr class="d-lg-none d-block">
                </div>
                <div class="std-details col-lg-8 mt-lg-4">
                    <!-- Row 1 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="txt-1 ">Registrarion No : <span><?php echo $row["username"] ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt-1">CNIC No : <span><?php echo $row["cnic"] ?></span></div>
                        </div>
                    </div>
                    <hr class="d-md-block d-none">
                    <!-- Row 2 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="txt-1 font-weight-bold">Name : <span><?php echo $row["name"] ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt-1">Father Name : <span><?php echo $row["fname"] ?></span></div>
                        </div>
                    </div>
                    <hr class="d-md-block d-none">
                    <!-- Row 3 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="txt-1 font-weight-bold">Department : <span><?php echo $row["department"] ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt-1">Semester : <span><?php echo $row["semester"] ?>th</span></div>
                        </div>
                    </div>
                    <hr class="d-md-block d-none">
                    <!-- Row 4 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="txt-1 font-weight-bold">CGPA : <span><?php echo $row["cgpa"] ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt-1">Attendence Status : <span><?php echo $row["attendence"] ?></span></div>
                        </div>
                    </div>
                    <hr class="d-md-block d-none">
                    <!-- Row 5 -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="txt-1 font-weight-bold">Email : <span><?php echo $row["email"] ?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="txt-1">Phone No : <span><?php echo $row["phone"] ?></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $course = array($row["course1"], $row["course2"], $row["course3"], $row["course4"], $row["course5"], $row["course6"]);
} else {
    echo "No user found with the username: " . $username;
}

$sql = "SELECT * FROM courses WHERE code=? OR code=? OR code=? OR code=? OR code=? OR code=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $course[0], $course[1], $course[2], $course[3], $course[4], $course[5]);
$stmt->execute();
$result = $stmt->get_result();

?>
    <!-- Hero Section End -->
    <!-- Enrolled Courses Start-->
    <section class="container my-4">
        <h3 class="heading-2 col-blue m-0">Enrolled Courses</h3>
        <p class="txt-2 m-0">Currently enrolled in the following courses:</p>
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Sno <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Code <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Cr hr's <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        $counter = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . $row['code'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['credit'] . "</td>";
                            echo "</tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr><td>No courses found.</td></tr>";
                    }

                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Enrolled Courses End-->

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