<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assignment = $_POST['assignment'];
    $topic_id = $_POST['topic_id'];
    $user_id = $_SESSION['user_id']; // Assuming you have user sessions

    $stmt = $conn->prepare("INSERT INTO assignments (topic_id, user_id, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $topic_id, $user_id, $assignment);

    if ($stmt->execute()) {
        echo "Assignment submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
