<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; // Adjust the path as per your directory structure

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $course_name = $_POST['name']; // Adjust the key according to your form data

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO courses (name) VALUES (?)");
    $stmt->bind_param("s", $course_name);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Course created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    echo "Error: Course name not provided.";
}

// Close connection
$conn->close();
?>
