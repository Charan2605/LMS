<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php'; // Ensure this path is correct

$course_id = $_GET['course_id'];

// Fetch course details
$course_stmt = $conn->prepare("SELECT * FROM courses WHERE id = ?");
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course = $course_stmt->get_result()->fetch_assoc();

// Fetch topics
$topic_stmt = $conn->prepare("SELECT * FROM topics WHERE course_id = ?");
$topic_stmt->bind_param("i", $course_id);
$topic_stmt->execute();
$topics = $topic_stmt->get_result();

// Fetch MCQs
$mcq_stmt = $conn->prepare("SELECT * FROM mcqs WHERE topic_id = ?");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['name']); ?> - Course Details</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($course['name']); ?></h1>
    <p><?php echo htmlspecialchars($course['description'] ?? ''); ?></p> <!-- Updated to handle null description -->

    <?php while ($topic = $topics->fetch_assoc()): ?>
        <div class="topic">
            <h2><?php echo htmlspecialchars($topic['name']); ?></h2>
            <p><?php echo htmlspecialchars($topic['description'] ?? ''); ?></p> <!-- Updated to handle null description -->
            
            <!-- YouTube video embed with autoplay -->
            <div class="video">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo htmlspecialchars($topic['video_link']); ?>?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
            
            <!-- Document link -->
            <?php if (!empty($topic['document_link'])): ?>
                <p><a href="<?php echo htmlspecialchars($topic['document_link']); ?>" target="_blank">View Document</a></p>
            <?php endif; ?>
            
            <!-- Display MCQs -->
            <form action="../db/submit_mcq.php" method="POST">
                <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                <?php
                $mcq_stmt->bind_param("i", $topic['id']);
                $mcq_stmt->execute();
                $mcqs = $mcq_stmt->get_result();
                $index = 1;
                while ($mcq = $mcqs->fetch_assoc()):
                ?>
                    <div class="mcq">
                        <p>Question <?php echo $index; ?>: <?php echo htmlspecialchars($mcq['question']); ?></p>
                        <label><input type="radio" name="answer[<?php echo $mcq['id']; ?>]" value="1" required> <?php echo htmlspecialchars($mcq['option1']); ?></label><br>
                        <label><input type="radio" name="answer[<?php echo $mcq['id']; ?>]" value="2"> <?php echo htmlspecialchars($mcq['option2']); ?></label><br>
                        <label><input type="radio" name="answer[<?php echo $mcq['id']; ?>]" value="3"> <?php echo htmlspecialchars($mcq['option3']); ?></label><br>
                        <label><input type="radio" name="answer[<?php echo $mcq['id']; ?>]" value="4"> <?php echo htmlspecialchars($mcq['option4']); ?></label><br>
                    </div>
                <?php
                $index++;
                endwhile;
                ?>
                <button type="submit">Submit MCQs</button>
            </form>

            <!-- Assignment submission -->
            <form action="../db/submit_assignment.php" method="POST">
                <input type="hidden" name="topic_id" value="<?php echo $topic['id']; ?>">
                <label for="assignment">Submit Assignment:</label><br>
                <textarea id="assignment" name="assignment" rows="4" cols="50" required></textarea><br>
                <button type="submit">Submit Assignment</button>
            </form>

            <hr>
        </div>
    <?php endwhile; ?>
    <?php
    $topic_stmt->close();
    $mcq_stmt->close();
    $conn->close();
    ?>
</body>
</html>


