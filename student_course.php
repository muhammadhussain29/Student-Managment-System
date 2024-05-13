<?php
session_start(); // Start the session

// Check if there's an error parameter in the URL
if (isset($_SESSION["message"])) {
    echo "<div class=\"vw-100 d-flex justify-content-center align-items-center position-absolute z-3 top-0\">";
    echo "<div class=\"alert alert-primary alert-dismissible fade show\" role=\"alert\">";
    echo $_SESSION["message"];
    echo "<button type=\"button\" class=\"btn-close\" data-dismiss=\"alert\" aria-label=\"Close\"></button>";
    echo "</div>";
    echo "</div>";
    unset($_SESSION["message"]);
}

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
                <div class="row m-2 d-flex justify-content-center">
                    <div class="col-md-3 my-1">
                        <input type="text" id="filterSemester" class="form-control" placeholder="Filter by Semester">
                    </div>
                    <div class="col-md-3 my-1">
                        <input type="text" id="filterCode" class="form-control" placeholder="Filter by Code">
                    </div>
                    <div class="col-md-3 my-1">
                        <input type="text" id="filterName" class="form-control" placeholder="Filter by Name">
                    </div>
                    <div class="col-md-3 my-1 d-flex justify-content-center">
                        <button id="applyFilter" class="btn py-1 px-3 mx-1">Filter</button>
                        <button id="resetFilter" class="btn py-1 px-3 mx-1">Reset</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profile ends -->

        <?php
} else {
    echo "No user found with the username: " . $username;
}
?>
    <!-- Hero Section End -->
    <!-- Enrolled Courses Start-->
    <section class="container my-4">
        <h3 class="heading-2 col-blue m-0">Enrolled Courses</h3>
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Sno <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Code <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Cr hr's <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Drop Course <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $course = array($row["course1"], $row["course2"], $row["course3"], $row["course4"], $row["course5"], $row["course6"]);
                    $sql = "SELECT * FROM courses WHERE code=? OR code=? OR code=? OR code=? OR code=? OR code=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssss", $course[0], $course[1], $course[2], $course[3], $course[4], $course[5]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        $counter = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr class="course-item-enrolled" data-course-id="' . $row["code"] . '">';
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . $row['code'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['credit'] . "</td>";
                            echo "<td> <button class=\"btn px-2 py-1 delete-course\">Drop Course</button> </td>";
                            echo "</tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr><td>No courses found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Enrolled Courses End-->
    <!-- Courses Start-->
    <section class="container my-4">
        <h3 class="heading-2 col-blue m-0">Courses</h3>
        <div class="table__body">
            <table>
                <thead>
                    <tr>
                        <th> Sno <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Code <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Course Name <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Semester <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Cr hr's <span class="icon-arrow">&UpArrow;</span></th>
                        <th> Add Course <span class="icon-arrow">&UpArrow;</span></th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $sql = "SELECT * FROM courses ORDER BY semester";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    // Check if there are any rows returned
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        $counter = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr class="course-item" data-semester="' . $row['semester'] . '" data-code="' . $row['code'] . '" data-name="' . $row['name'] . '">';
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . $row['code'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['semester'] . "</td>";
                            echo "<td>" . $row['credit'] . "</td>";
                            echo "<td> <button class=\"btn px-2 py-1 add-course\">Add Course</button> </td>";
                            echo "</tr>";
                            $counter++;
                        }
                    } else {
                        echo "<tr><td>No courses found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <!-- Courses End-->


    <!-- JavaScript (jQuery) for filtering -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        // Add Course Button
        $(document).ready(function () {
            jQuery.noConflict();
            // Click event for Add Course button
            $('.add-course').on('click', function () {
                // Get the course details from the data attributes of the parent course item
                var code = $(this).closest('.course-item').data('code');
                var name = $(this).closest('.course-item').data('name');
                var semester = $(this).closest('.course-item').data('semester');

                // Send an AJAX request to add the course
                $.ajax({
                    type: 'POST',
                    url: 'student_course_add.php', // Update with your PHP script URL
                    data: { code: code, name: name, semester: semester }, // Send course details to the server
                    success: function (response) {
                        // Handle the success response from the server
                        console.log('Course added successfully.');
                        // Optionally, you can display a success message or refresh the course list
                        location.reload(); // Reload the page to reflect the updated course list
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors that occur during the AJAX request
                        console.error('Error adding course:', error);
                        // Optionally, you can display an error message to the user
                    }
                });
            });
        });

        // Delete Course Button
        $(document).ready(function () {
            // Click event for delete button
            $('.delete-course').on('click', function () {
                // Get the course ID from the data attribute of the parent course item
                var courseId = $(this).closest('.course-item-enrolled').data('course-id');

                // Confirm deletion with the user
                // if (confirm('Are you sure you want to delete this course?')) {
                // Send an AJAX request to delete the course
                $.ajax({
                    type: 'POST',
                    url: 'student_course_delete.php', // Update with your PHP script URL
                    data: { courseId: courseId }, // Send the course ID to the server
                    success: function (response) {
                        // Handle the success response from the server
                        console.log('Course deleted successfully.');
                        // Optionally, you can remove the course item from the UI
                        // $(this).closest('.course-item').remove();
                        // Reload the page upon successful deletion
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Handle any errors that occur during the AJAX request
                        console.error('Error deleting course:', error);
                    }
                });
                // }
            });
        });

        // Filter
        $(document).ready(function () {
            // Apply filter button click event
            $('#applyFilter').click(function () {
                var semester = $('#filterSemester').val().trim().toLowerCase();
                var code = $('#filterCode').val().trim().toLowerCase();
                var name = $('#filterName').val().trim().toLowerCase();

                // Filter the course items based on the input values
                $('.course-item').each(function () {
                    // Convert data attribute values to string and then apply toLowerCase()
                    var semesterMatch = semester === '' || String($(this).data('semester')).toLowerCase().indexOf(semester) !== -1;
                    var codeMatch = code === '' || String($(this).data('code')).toLowerCase().indexOf(code) !== -1;
                    var nameMatch = name === '' || String($(this).data('name')).toLowerCase().indexOf(name) !== -1;

                    if (semesterMatch && codeMatch && nameMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Reset filter button click event
            $('#resetFilter').click(function () {
                $('#filterSemester').val('');
                $('#filterCode').val('');
                $('#filterName').val('');
                $('.course-item').show();
            });
        });
    </script>
    <?php
    // Close the connection
    $conn->close();
    ?>
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