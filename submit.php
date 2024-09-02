<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'earn');

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define correct answers
$correct_answers = [
    1 => 1, // Question 1, answer index 1 (90°)
    2 => 1, // Question 2, answer index 1 (Jupiter)
    3 => 1  // Question 3, answer index 1 (To measure temperature)
];

// Initialize score
$score = 0;
$reward_per_question = 100; // Reward per question in RWF

// Check user's answers and store results in the database
$user_id = $_SESSION['user_id']; // Assuming the user is logged in and user_id is stored in session

foreach ($correct_answers as $question_id => $correct_answer) {
    $user_answer = isset($_POST["question-$question_id"]) ? $_POST["question-$question_id"] : -1;
    $is_correct = ($user_answer == $correct_answer);
    $earned_amount = $is_correct ? $reward_per_question : 0;

    // Add to total score
    $score += $earned_amount;

    // Insert result into database
    $stmt = $conn->prepare("INSERT INTO quiz_results (user_id, question_id, user_answer, is_correct, earned_amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiii", $user_id, $question_id, $user_answer, $is_correct, $earned_amount);
    $stmt->execute();
    $stmt->close();
}

// Update user's total earnings
$stmt = $conn->prepare("UPDATE users SET total_earnings = total_earnings + ? WHERE id = ?");
$stmt->bind_param("ii", $score, $user_id);
$stmt->execute();
$stmt->close();

// Mark task as done and save the time
$_SESSION['task_done'] = true;
$_SESSION['last_attempt_time'] = time(); // Store the time of the last attempt

// Show alert with the money earned in the current quiz attempt and redirect back to a specific page
echo "<script>
    alert('You have earned RWF " . number_format($score, 2) . " in this quiz!');
    window.location.href = 'question.php'; // Redirect to the 'task already done' page
</script>";

// Close the connection
$conn->close();
?>
