<?php
include '../auth/check_admin.php';
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $video_link = $_POST['video_link'];
    $document_link = $_POST['document_link'];
    $description = $_POST['description'];
    $course_id = $_POST['course_id'];

    $stmt = $conn->prepare("INSERT INTO topics (name, video_link, document_link, description, course_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $name, $video_link, $document_link, $description, $course_id);
    if ($stmt->execute()) {
        echo "Topic added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
