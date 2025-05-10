<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $survey_id = $_POST['survey_id'];
    $responses = $_POST['responses'];

    foreach ($responses as $question_id => $response) {
        if (is_array($response)) {
            $response = implode(', ', $response); // Handle multiple-choice responses
        }
        $stmt = $pdo->prepare("INSERT INTO survey_responses (survey_id, question_id, response) VALUES (:survey_id, :question_id, :response)");
        $stmt->execute([
            ':survey_id' => $survey_id,
            ':question_id' => $question_id,
            ':response' => $response,
        ]);
    }

    echo "<script>alert('Survey submitted successfully!'); window.location.href = 'index.php';</script>";
}
?>