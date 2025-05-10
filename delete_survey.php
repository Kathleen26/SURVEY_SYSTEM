<?php
// delete_survey.php

require_once 'db_connection.php';

// Get survey ID from query parameter
$survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($survey_id > 0) {
    try {
// Delete related questions
$stmt_questions = $pdo->prepare("DELETE FROM s_questions WHERE survey_id = ?");
$stmt_questions->execute([$survey_id]);

// Delete the survey from the correct table name
$stmt_survey = $pdo->prepare("DELETE FROM surveys WHERE id = ?");
$stmt_survey->execute([$survey_id]);
    } catch (PDOException $e) {
        die("Deletion failed: " . $e->getMessage());
    }
}

// Redirect back to survey list
header("Location: survey_list.php");
exit();
