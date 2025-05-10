<?php
// save_survey.php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "survey_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($title)) {
        die("Survey title is required.");
    }

// Insert survey into surveys
$stmt = $conn->prepare("INSERT INTO surveys (title, description) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $description);

    if ($stmt->execute()) {
        $survey_id = $stmt->insert_id;

        // Save questions and options
        if (isset($_POST['questions']) && is_array($_POST['questions'])) {
foreach ($_POST['questions'] as $question) {
    $question_text = isset($question['text']) ? trim($question['text']) : '';
    $question_type = isset($question['type']) ? $question['type'] : 'text';

    if ($question_text !== '') {
// Insert question
$q_stmt = $conn->prepare("INSERT INTO s_questions (survey_id, question_text, question_type) VALUES (?, ?, ?)");
$q_stmt->bind_param("iss", $survey_id, $question_text, $question_type);
        $q_stmt->execute();
        $question_id = $q_stmt->insert_id;
        $q_stmt->close();

        // Insert options if applicable
        if (($question_type === 'multipleChoice' || $question_type === 'radio') && isset($question['options']) && is_array($question['options'])) {
            foreach ($question['options'] as $option_text) {
                $option_text = trim($option_text);
                if ($option_text !== '') {
                    $o_stmt = $conn->prepare("INSERT INTO question_options (question_id, option_text) VALUES (?, ?)");
                    $o_stmt->bind_param("is", $question_id, $option_text);
                    $o_stmt->execute();
                    $o_stmt->close();
                }
            }
        }
    }
}
        }

        // Redirect to survey list page
        header("Location: survey_list.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
