<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: views/admin_dashboard.php");
        exit();
    } else {
        header("Location: views/user_dashboard.php");
        exit();
    }
} else {
    // Display login and registration links
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Welcome</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <h1>Welcome to the Self-Learning Software</h1>
        <a href="views/login.html">Login</a> | <a href="views/registration.html">Register</a>
    </body>
    </html>
    <?php
}
?>
