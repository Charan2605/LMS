<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start(); // Start the session

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        die("You must be logged in to submit MCQs.");
    }

    $user_id = $_SESSION['user_id'];
    $topic_id = $_POST['topic_id'];
    $answers = $_POST['answer'];

    foreach ($answers as $mcq_id => $answer) {
        $stmt = $conn->prepare("INSERT INTO mcq_answers (user_id, mcq_id, answer) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $mcq_id, $answer);

        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    echo "MCQ answers submitted successfully.";
}

$conn->close();
?>

