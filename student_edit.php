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
        <link rel="stylesheet" href="style/student_edit.css">
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
                                Courses</a>
                        </li>
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
        <!-- Edit Form Start-->
        <div class="container-xl px-4 mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header heading-2 col-blue">Profile Picture</div>
                        <div class="card-body text-center">
                            <!-- Profile picture image-->
                            <?php
                            if ($row["profile"]) {
                                echo '<img class="img-account-profile rounded-circle mb-2" src="data:image/jpeg;base64,' . base64_encode($row["profile"]) . '" alt="profile">';
                            } else {
                                // If no profile picture is found in the database, display a default image
                                echo '<img class="img-account-profile rounded-circle mb-2" src="profile.jpg" alt="profile">';
                            }
                            ?>
                            <!-- Profile picture help block-->
                            <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 10 MB</div>
                            <!-- Profile picture upload form-->
                            <form method="post" enctype="multipart/form-data" action="student_edit_profile.php">
                                <input type="file" name="profile_picture" accept="image/png, image/jpeg" />
                                <button class="btn py-1 px-3" type="submit" name="upload_profile_picture">Upload new
                                    image</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <!-- User details card-->
                    <div class="card mb-4">
                        <div class="card-header heading-2 col-blue">User Details</div>
                        <div class="card-body">
                            <form method="post" action="student_edit_detail.php">
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputUsername">Username</label>
                                        <input class="form-control" id="inputUsername" readonly type="text"
                                            value="<?php echo $row["username"] ?>">
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputCNIC">CNIC</label>
                                        <input class="form-control" id="inputCNIC" readonly type="text"
                                            value="<?php echo $row["cnic"] ?>">
                                    </div>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputFirstName">Name</label>
                                        <input class="form-control" id="inputFirstName" type="text" name="name"
                                            placeholder="Enter your name" value="<?php echo $row["name"] ?>">
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLastName">Father name</label>
                                        <input class="form-control" id="inputLastName" type="text" name="father_name"
                                            placeholder="Enter your father name" value="<?php echo $row["fname"] ?>">
                                    </div>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (Semester number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputSemester">Semester No:</label>
                                        <input class="form-control" id="inputSemester" type="text" name="semester"
                                            placeholder="Enter your Semester" value="<?php echo $row["semester"] ?>">
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputBirthday">Birthday</label>
                                        <input class="form-control" id="inputBirthday" type="text" name="birthday"
                                            placeholder="Enter your Date of Birth" value="<?php echo $row["dob"] ?>">
                                    </div>
                                </div>
                                <!-- Save changes button-->
                                <input class="btn py-1 px-3" type="submit" value="Save changes">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <!-- Contact card-->
                    <div class="card mb-4">
                        <div class="card-header  heading-2 col-blue">Contact Details</div>
                        <div class="card-body">
                            <form method="post" action="student_edit_contact.php">
                                <!-- Form Row -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputEmail">Email address</label>
                                        <input class="form-control" id="inputEmail" type="text" name="email"
                                            placeholder="Enter your Email Address" value="<?php echo $row["email"] ?>">
                                    </div>
                                    <!-- Form Group (phone)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Phone No:</label>
                                        <input class="form-control" id="inputPhone" type="text" name="phone"
                                            placeholder="Enter your Phone no" value="<?php echo $row["phone"] ?>">
                                    </div>
                                </div>
                                <!-- Form Group (current address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputCurrentAddress">Current Address</label>
                                    <input class="form-control" id="inputCurrentAddress" type="text" name="current_address"
                                        placeholder="Enter your current address" value="<?php echo $row["address"] ?>">
                                </div>
                                <!-- Form Group (postal address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputPostalAddress">Postal Address</label>
                                    <input class="form-control" id="inputPostalAddress" type="text" name="postal_address"
                                        placeholder="Enter your postal address" value="<?php echo $row["postal"] ?>">
                                </div>
                                <!-- Save changes button-->
                                <input class="btn py-1 px-3" type="submit" value="Save changes">
                            </form>
                        </div>
                    </div>
                </div>
                <?php
} else {
    echo "No user found with the username: " . $username;
}
?>
            <div class="col-12">
                <!-- Password card-->
                <div class="card mb-4">
                    <div class="card-header heading-2 col-blue">Change Password</div>
                    <div class="card-body">
                        <form method="POST" action="student_edit_password.php">
                            <!-- Form Row -->
                            <div class="row gx-3 mb-3">
                                <!-- Form Group (organization name)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputCurrentPassword">Current Password</label>
                                    <input class="form-control" id="inputCurrentPassword" type="password"
                                        placeholder="Enter Current Password" name="current_password">
                                </div>
                                <!-- Form Group (phone)-->
                                <div class="col-md-6">
                                    <label class="small mb-1" for="inputNewPassword">New Password</label>
                                    <input class="form-control" id="inputNewPassword" type="password"
                                        placeholder="Enter New Password" name="new_password">
                                </div>
                            </div>
                            <!-- Save changes button-->
                            <input class="btn py-1 px-3" type="submit" value="Update Password">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Form End-->
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
<?php
// Close the connection
$conn->close();
?>