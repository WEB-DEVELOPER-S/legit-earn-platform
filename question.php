<?php
session_start();

// Check if the quiz was completed and if 24 hours have passed
$task_done = isset($_SESSION['task_done']) ? $_SESSION['task_done'] : false;
$time_passed = isset($_SESSION['last_attempt_time']) ? (time() - $_SESSION['last_attempt_time'] >= 86400) : true;

// Reset task_done if 24 hours have passed
if ($task_done && $time_passed) {
    $_SESSION['task_done'] = false;
    $task_done = false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Application</title>
    <style>
          body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
     
        .quiz-container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .question {
            margin-bottom: 10px;
        }
        .options label {
            display: block;
            margin-bottom: 8px;
        }
        .submit-button, .task-done-button, .start-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background-color: #ff9800;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .start-button {
            background-color: #4CAF50;
        }
        .task-done-button {
            background-color: #d32f2f;
        }
        .back-button{
            color:white;
            text-decoration:none;
        }
              /* Header Section */
              .header {
            background-color: #5a2fcf;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header img {
            height: 40px;
        }

        .header-title {
            display: flex;
            align-items: center;
            font-size: 1.5em;
        }

        .header .menu-icon,
        .header .settings-icon {
            cursor: pointer;
        }

        .breadcrumb {
            margin: 10px 20px;
            font-size: 0.9em;
            color: #666;
            text-align:center;
        
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            margin-right: 5px;
         
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #666;
            padding: 20px 0;
            background-color: #f8f8f8;
            margin-top: 20px;
        }

        .footer a {
            color: #5a2fcf;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
        
    </style>
    <script>
        let timeLeft = 30; // countdown in seconds
        let countdown;

        function startCountdown() {
            countdown = setInterval(() => {
                timeLeft--;
                document.getElementById('time').textContent = timeLeft;
                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    document.getElementById('quiz-form').submit(); // auto-submit when time is up
                }
            }, 300);
        }

        function showQuiz() {
            document.getElementById('start-screen').style.display = 'none';
            document.getElementById('quiz-screen').style.display = 'block';
            startCountdown();
        }

        window.onload = function() {
            <?php if ($task_done): ?>
                document.getElementById('start-screen').style.display = 'none';
                document.getElementById('task-done-screen').style.display = 'block';
            <?php else: ?>
                document.getElementById('quiz-screen').style.display = 'none';
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <!-- Header Section -->
    <div class="header">
    <a href="javascript:history.back()" class="back-button">☰</a>
        <div class="header-title">
            
            LEGIT💲EARN
        </div>
        <div class="settings-icon">&nbsp;</div>
    </div>

    <!-- Breadcrumb Navigation -->
    <main class="breadcrumb">
        <a href="legit.php" style="font-weight:bold;">Home</a> / <a href="#">proceed</a>
    </main>

<div class="quiz-container" id="start-screen" style="<?php echo $task_done ? 'display: none;' : ''; ?>">
    <h2>SURVEY</h2>
    <p>You only have few seconds to tackle the questions. If time elapses, you will be credited 0 points.</p>
    <div>Time left: <span id="time">30</span> seconds</div>
    <button class="start-button" onclick="showQuiz()">START QUIZ</button>
</div>

<div class="quiz-container" id="quiz-screen" style="display: none;">
    <?php
    // Questions and answers array
    $questions = [
        1 => ['What is a right Angle?', ['0°', '90°', '360°', '180°'], 1],
        2 => ['Which is the largest planet in the solar system?', ['Earth', 'Jupiter', 'Saturn', 'Pluto'], 1],
        3 => ['What is a thermometer used for?', ['To measure speed of wind', 'To measure temperature', 'To measure humidity', 'To record sunshine'], 1]
    ];

    echo '<form id="quiz-form" action="submit.php" method="post">';
    foreach ($questions as $id => $q) {
        echo "<div class='question'><strong>$id - {$q[0]}</strong></div>";
        echo '<div class="options">';
        foreach ($q[1] as $key => $option) {
            echo "<label><input type='radio' name='question-$id' value='$key'> $option</label>";
        }
        echo '</div>';
    }
    echo '<button type="submit" class="submit-button">Submit Quiz</button>';
    echo '</form>';
    ?>
</div>

<div class="quiz-container" id="task-done-screen" style="display: none;">
    <h2>SURVEY</h2>
    <p>You only have few seconds to tackle the questions. If time elapses, you will be credited 0 points.</p>
    <div>Time left: <span id="time">30</span> seconds</div>
    <button class='task-done-button'>ALREADY SUBMITTED</button>
</div>

<!-- Footer Section -->
<div class="footer">
        Copyright © 2025 <a href="legit.php">LEGIT💲EARN</a>. Designed by <a href="#">web developers Limited</a> All rights reserved.
    </div>

</body>
</html>
