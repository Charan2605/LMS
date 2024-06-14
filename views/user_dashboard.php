<?php
session_start();
if ($_SESSION['role'] != 'user') {
    echo "Access denied";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>User Dashboard</h1>
    <form method="GET" action="view_course.php">
        <input type="text" name="course_id" placeholder="Enter Course ID" required>
        <button type="submit">View Course</button>
    </form>
    <a href="../auth/logout.php">Logout</a>
</body>
</html>

