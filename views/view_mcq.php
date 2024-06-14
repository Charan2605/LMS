<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php';

echo "Step 1: Included config<br>";

if (isset($_GET['topic_id'])) {
    $topic_id = $_GET['topic_id'];
    echo "Step 2: Topic ID is $topic_id<br>";

    $stmt = $conn->prepare("SELECT * FROM mcqs WHERE topic_id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    echo "Step 3: Prepare statement<br>";

    $stmt->bind_param("i", $topic_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    echo "Step 4: Execute statement<br>";

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("No MCQs found for this topic.");
    }

    echo "Step 5: Fetched results<br>";

    $time_limit = 0;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Attempt MCQs</title>
        <link rel="stylesheet" type="text/css" href="../css/styles.css">
        <script>
            // JavaScript to handle the timer
            let timer;
            function startTimer(duration, display) {
                let start = Date.now(),
                    diff,
                    minutes,
                    seconds;
                function timerFunction() {
                    diff = duration - (((Date.now() - start) / 1000) | 0);
                    minutes = (diff / 60) | 0;
                    seconds = (diff % 60) | 0;
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
                    display.textContent = minutes + ":" + seconds;
                    if (diff <= 0) {
                        clearInterval(timer);
                        document.getElementById("mcq_form").submit();
                    }
                }
                timerFunction();
                timer = setInterval(timerFunction, 1000);
            }

            window.onload = function () {
                const timeLimit = document.getElementById("time_limit").value;
                const display = document.querySelector('#time');
                startTimer(timeLimit, display);
            };
        </script>
    </head>
    <body>
        <h1>Attempt MCQs</h1>
        <div id="timer">
            <span>Time Remaining: </span><span id="time">00:00</span>
        </div>
        <form id="mcq_form" action="../db/submit_mcq.php" method="POST">
            <input type="hidden" id="time_limit" value="<?= $time_limit ?>" />
            <?php
            $index = 1;
            while ($row = $result->fetch_assoc()) {
                echo "Step 6: Displaying question<br>";
                echo "<div class='mcq'>";
                echo "<p>Question {$index}: " . htmlspecialchars($row['question']) . "</p>";
                echo "<label><input type='radio' name='answer[{$row['id']}]' value='1' required> " . htmlspecialchars($row['option1']) . "</label><br>";
                echo "<label><input type='radio' name='answer[{$row['id']}]' value='2'> " . htmlspecialchars($row['option2']) . "</label><br>";
                echo "<label><input type='radio' name='answer[{$row['id']}]' value='3'> " . htmlspecialchars($row['option3']) . "</label><br>";
                echo "<label><input type='radio' name='answer[{$row['id']}]' value='4'> " . htmlspecialchars($row['option4']) . "</label><br>";
                echo "</div>";
                $index++;
                $time_limit = $row['time_limit'];
            }
            ?>
            <button type="submit">Submit Answers</button>
        </form>
    </body>
    </html>
    <?php
    $stmt->close();
} else {
    echo "Error: Topic ID not provided.";
}

$conn->close();
?>
