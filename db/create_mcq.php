<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; // Adjust the path as per your directory structure

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $topic_id = $_POST['topic_id'];
    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_option = $_POST['correct_option'];
    $time_limit = $_POST['time_limit'];

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO mcqs (topic_id, question, option1, option2, option3, option4, correct_option, time_limit) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiii", $topic_id, $question, $option1, $option2, $option3, $option4, $correct_option, $time_limit);

    if ($stmt->execute()) {
        echo "MCQ created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
