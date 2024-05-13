<?php
// Check if there's an error parameter in the URL
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo "<div class=\"alert alert-primary alert-dismissible fade show position-absolute top-0 m-2\" role=\"alert\">";
    echo "Invalid username or password. Please try again.";
    echo "<button type=\"button\" class=\"btn-close\" data-dismiss=\"alert\" aria-label=\"Close\"></button>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <!-- Font awsome link for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom css -->
    <link rel="stylesheet" href="style/general.css">
    <link rel="stylesheet" href="style/login.css">
</head>

<body class="d-flex justify-content-center align-items-center vh-md-auto vh-100">
    <script>
        <?php
        session_start(); // Start the session
        // Check if the user is already signed in
        // If yes, redirect to respective dashboard page
        if (isset($_SESSION["username"])) {
            if ($_SESSION["role"] == "student") {
                echo 'window.location.href = "student_dashboard.php";';
            } elseif ($_SESSION["role"] == "faculty") {
                echo 'window.location.href = "faculty_dashboard.php";';
            }
        }
        ?>
    </script>
    <!-- Whole Login box -->
    <div class="wrapper d-flex flex-md-row flex-column w-md-100 m-2 overflow-hidden">
        <!-- Left Container -->
        <div class="logo d-flex justify-content-center align-items-center p-md-5 p-2">
            <img src="numl_logo.png" alt="NUML Logo">
        </div>
        <!-- Right Container -->
        <div class="d-flex flex-column p-5" id="loginForm">
            <!-- Form heading and txt -->
            <h2 class="heading-2 m-0">Login</h2>
            <p class="txt-2">Please enter your login details</p>
            <!-- Main Form -->
            <form id="loginForm " action="login.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input class="m-0" type="password" name="password" placeholder="Password" required>
                <a href="#" class="txt-2 text-end d-block">Forget password?</a>
                <input type="submit" value="Login" class="btn py-1 px-3 my-2">
            </form>
            <!-- Scial icons -->
            <div class="txt-2 text-center mt-4">Get in touch</div>
            <div class="social-icons  d-flex justify-content-center align-items-center text-center">
                <i class="fa-brands fa-facebook-f"></i>
                <i class="fa-brands fa-twitter"></i>
                <i class="fa-solid fa-envelope"></i>
            </div>
        </div>
    </div>
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