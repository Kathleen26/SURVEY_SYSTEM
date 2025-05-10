<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start();

require_once 'db_connection.php';

// Initialize variables
$survey_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$errors = [];
$success = false;

echo "Debug: Received survey ID: $survey_id<br>";

// Redirect if no valid survey ID provided
if ($survey_id <= 0) {
    echo "Debug: Invalid survey ID: $survey_id";
    header("Location: survey_list.php");
    exit();
}

// Handle POST request to update survey
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $survey_id = isset($_POST['survey_id']) ? intval($_POST['survey_id']) : 0;
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($title)) {
        $errors[] = "Survey title is required.";
    }

    if (empty($errors)) {
        // Update survey title and description
        $stmt = $pdo->prepare("UPDATE surveys SET title = ?, description = ? WHERE id = ?");
        if (!$stmt->execute([$title, $description, $survey_id])) {
            $errors[] = "Failed to update survey.";
        }

        // Verify survey exists before updating questions
        $checkStmt = $pdo->prepare("SELECT id FROM surveys WHERE id = ?");
        $checkStmt->execute([$survey_id]);
        if ($checkStmt->rowCount() === 0) {
            $errors[] = "Survey does not exist.";
        }

        // Update questions and options here when form supports it
        if (empty($errors) && $survey_id > 0 && isset($_POST['questions']) && is_array($_POST['questions'])) {
            $submittedQuestions = $_POST['questions'];

            // Fetch existing question IDs for this survey
            $existingQuestionIds = [];
            $q_stmt = $pdo->prepare("SELECT id FROM s_questions WHERE survey_id = ?");
            $q_stmt->execute([$survey_id]);
            $existingQuestions = $q_stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($existingQuestions as $row) {
                $existingQuestionIds[] = $row['id'];
            }

            $submittedQuestionIds = [];

            foreach ($submittedQuestions as $index => $qData) {
                $questionText = trim($qData['question_text'] ?? '');
                $questionType = $qData['question_type'] ?? 'text';
                $questionId = $qData['id'] ?? 0;

                if ($questionId && in_array($questionId, $existingQuestionIds)) {
                    // Update existing question
                    if ($questionType === 'rating' && isset($qData['rating'])) {
                        $rating = intval($qData['rating']);
                        $stmt = $pdo->prepare("UPDATE s_questions SET question_text = ?, question_type = ?, rating = ? WHERE id = ?");
                        $stmt->execute([$questionText, $questionType, $rating, $questionId]);
                    } else {
                        $stmt = $pdo->prepare("UPDATE s_questions SET question_text = ?, question_type = ? WHERE id = ?");
                        $stmt->execute([$questionText, $questionType, $questionId]);
                    }
                    $submittedQuestionIds[] = $questionId;
                } else {
                    // Insert new question only if survey_id is valid
                    if ($survey_id > 0) {
                        if ($questionType === 'rating' && isset($qData['rating'])) {
                            $rating = intval($qData['rating']);
                            $stmt = $pdo->prepare("INSERT INTO s_questions (survey_id, question_text, question_type, rating) VALUES (?, ?, ?, ?)");
                            $stmt->execute([$survey_id, $questionText, $questionType, $rating]);
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO s_questions (survey_id, question_text, question_type) VALUES (?, ?, ?)");
                            $stmt->execute([$survey_id, $questionText, $questionType]);
                        }
                        $questionId = $pdo->lastInsertId();
                        $submittedQuestionIds[] = $questionId;
                    } else {
                        $errors[] = "Invalid survey ID for inserting question.";
                    }
                }

                // Handle options for multipleChoice and radio types
                if (in_array($questionType, ['multipleChoice', 'radio'])) {
                    // Delete existing options for this question
                    $delStmt = $pdo->prepare("DELETE FROM question_options WHERE question_id = ?");
                    $delStmt->execute([$questionId]);

                    // Insert new options
                    if (isset($qData['options']) && is_array($qData['options'])) {
                        foreach ($qData['options'] as $optionText) {
                            $optionText = trim($optionText);
                            if ($optionText !== '') {
                                $optStmt = $pdo->prepare("INSERT INTO question_options (question_id, option_text) VALUES (?, ?)");
                                $optStmt->execute([$questionId, $optionText]);
                            }
                        }
                    }
                }
            }

            // Delete questions that were removed
            $questionsToDelete = array_diff($existingQuestionIds, $submittedQuestionIds);
            if (!empty($questionsToDelete)) {
                $placeholders = implode(',', array_fill(0, count($questionsToDelete), '?'));
                $delQuery = "DELETE FROM s_questions WHERE id IN ($placeholders)";
                $delStmt = $pdo->prepare($delQuery);
                $delStmt->execute($questionsToDelete);
            }
        }

        if (empty($errors)) {
            $success = true;
            // Redirect to survey list after update
            header("Location: survey_list.php");
            exit();
        }
    }
}

// Load survey data for GET request or if errors in POST
$survey = null;
$questions = [];

if ($survey_id > 0) {
    // Fetch survey
    $stmt = $pdo->prepare("SELECT id, title, description FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$survey) {
        echo "Debug: Survey not found for ID: $survey_id";
        // Redirect to survey list with error message if survey not found
        header("Location: survey_list.php?error=SurveyNotFound");
        exit();
    } else {
        echo "Debug: Survey found: " . htmlspecialchars($survey['title']) . "<br>";
    }

    // Fetch questions and options
    $questions = [];
    $q_stmt = $pdo->prepare("SELECT id, question_text, question_type FROM s_questions WHERE survey_id = ? ORDER BY id ASC");
    $q_stmt->execute([$survey_id]);
    $q_result = $q_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($q_result as $q_row) {
        $question_id = $q_row['id'];
        $options = [];
        $o_stmt = $pdo->prepare("SELECT id, option_text FROM question_options WHERE question_id = ? ORDER BY id ASC");
        $o_stmt->execute([$question_id]);
        $options = $o_stmt->fetchAll(PDO::FETCH_ASSOC);

        $questions[] = [
            'id' => $question_id,
            'question_text' => $q_row['question_text'],
            'question_type' => $q_row['question_type'],
            'options' => $options
        ];
    }
} else {
    die("Invalid survey ID.");
}
?>

<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Edit Survey</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-[#7ea9d9] flex flex-col">
  <header class="bg-[#0ea5e9] h-12 w-full"></header>
  <nav class="bg-white bg-opacity-90 shadow-md rounded-md w-full max-w-5xl p-4 mx-auto mt-4 mb-2 text-right">
    <a href="survey_list.php" class="text-[#0ea5e9] font-semibold hover:underline">View Survey List</a>
  </nav>
  <main class="flex-grow flex justify-center items-start p-6">
    <form id="editSurveyForm" action="edit_survey.php?id=<?php echo $survey_id; ?>" method="POST" class="bg-white bg-opacity-90 shadow-md rounded-md w-full max-w-5xl p-8 space-y-6">
      <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>" />
      <?php if (!empty($errors)): ?>
        <div class="text-red-600 font-semibold">
          <?php foreach ($errors as $error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div>
        <label for="title" class="font-semibold block mb-2">Survey Title:</label>
        <input id="title" name="title" type="text" value="<?php echo htmlspecialchars($survey['title']); ?>" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" required />
      </div>
      <div>
        <label for="description" class="font-semibold block mb-2">Survey Description:</label>
        <textarea id="description" name="description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]"><?php echo htmlspecialchars($survey['description']); ?></textarea>
      </div>
      <div id="questionsContainer">
        <?php if (!empty($questions)): ?>
          <?php foreach ($questions as $index => $question): ?>
            <fieldset class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-4">
              <legend class="font-semibold mb-2">Question <?php echo $index + 1; ?>:</legend>
              <input type="hidden" name="questions[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars($question['id']); ?>" />
              <input type="text" name="questions[<?php echo $index; ?>][question_text]" value="<?php echo htmlspecialchars($question['question_text']); ?>" placeholder="Enter question" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] mb-4 w-full" required />
              <div class="mt-4">
    <label for="questionType<?php echo $index; ?>" class="block mb-2 font-semibold">Select Question Type:</label>
    <select name="questions[<?php echo $index; ?>][question_type]" id="questionType<?php echo $index; ?>" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" onchange="updateQuestionType(this, <?php echo $index; ?>)">
        <option value="text" <?php echo $question['question_type'] === 'text' ? 'selected' : ''; ?>>Text Question</option>
        <option value="multipleChoice" <?php echo $question['question_type'] === 'multipleChoice' ? 'selected' : ''; ?>>Multiple Choice</option>
        <option value="radio" <?php echo $question['question_type'] === 'radio' ? 'selected' : ''; ?>>Single Answer (Radio Button)</option>
        <option value="rating" <?php echo $question['question_type'] === 'rating' ? 'selected' : ''; ?>>Rating Question</option>
         </select>
            </div>
              <div id="optionsContainer<?php echo $index; ?>" class="mt-4">
                <?php if (in_array($question['question_type'], ['multipleChoice', 'radio'])): ?>
                  <label class="block mb-2 font-semibold">Options:</label>
                  <div class="flex flex-col gap-2">
                    <?php foreach ($question['options'] as $optIndex => $option): ?>
                      <input type="text" name="questions[<?php echo $index; ?>][options][]" value="<?php echo htmlspecialchars($option['option_text']); ?>" placeholder="Option <?php echo $optIndex + 1; ?>" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
                    <?php endforeach; ?>
                  </div>
                  <button type="button" onclick="addOption(<?php echo $index; ?>)" class="mt-2 bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Add Option</button>
                <?php elseif ($question['question_type'] === 'rating'): ?>
                  <label class="block mb-2 font-semibold">Rating Scale:</label>
                  <div class="flex items-center gap-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                      <label class="text-gray-700"><?php echo $i; ?></label>
                      <input type="radio" name="questions[<?php echo $index; ?>][rating]" value="<?php echo $i; ?>" <?php if (isset($question['rating']) && $question['rating'] == $i) echo 'checked'; ?> class="mr-2" />
                    <?php endfor; ?>
                  </div>
                <?php endif; ?>
              </div>
            </fieldset>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No questions found for this survey.</p>
        <?php endif; ?>
      </div>
      <div class="flex justify-center">
        <button type="button" onclick="addQuestion()" class="bg-[#0ea5e9] text-white rounded-md px-6 py-2 hover:bg-[#0c87cc] transition">Add Another Question</button>
      </div>
      <button type="submit" class="w-full bg-[#0ea5e9] text-white rounded-md py-3 text-lg hover:bg-[#0c87cc] transition">Update Survey</button>
    </form>
  </main>

<script>
    let questionCount = <?php echo count($questions); ?>;

    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');

        const newQuestion = document.createElement('fieldset');
        newQuestion.className = 'bg-gray-50 border border-gray-200 rounded-md p-4 mt-4';
        newQuestion.innerHTML = `
      <legend class="font-semibold mb-2">Question ${questionCount}:</legend>
      <input type="text" name="questions[${questionCount - 1}][question_text]" placeholder="Enter question" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] mb-4 w-full" required />
      <div class="mt-4">
        <label for="questionType${questionCount - 1}" class="block mb-2 font-semibold">Select Question Type:</label>
        <select name="questions[${questionCount - 1}][question_type]" id="questionType${questionCount - 1}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" onchange="updateQuestionType(this, ${questionCount - 1})">
          <option value="text">Text Question</option>
          <option value="multipleChoice">Multiple Choice</option>
          <option value="radio">Radio Button Choices</option>
          <option value="rating">Rating Question</option>
        </select>
      </div>
      <div id="optionsContainer${questionCount - 1}" class="mt-4">
        <!-- Options will be dynamically added here based on the selected question type -->
      </div>
    `;

        questionsContainer.appendChild(newQuestion);
    }

    function updateQuestionType(selectElement, questionNumber) {
        const selectedType = selectElement.value;
        const optionsContainer = document.getElementById(`optionsContainer${questionNumber}`);
        optionsContainer.innerHTML = ''; // Clear previous options

        if (selectedType === 'multipleChoice') {
            optionsContainer.innerHTML = `
        <label class="block mb-2 font-semibold">Options:</label>
        <div class="flex flex-col gap-2">
          <input type="text" name="questions[${questionNumber}][options][]" placeholder="Option 1" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
          <input type="text" name="questions[${questionNumber}][options][]" placeholder="Option 2" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
        </div>
        <button type="button" onclick="addOption(${questionNumber})" class="mt-2 bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Add Option</button>
      `;
        } else if (selectedType === 'radio') {
            optionsContainer.innerHTML = `
        <label class="block mb-2 font-semibold">Radio Button Choices:</label>
        <div class="flex flex-col gap-2">
          <input type="text" name="questions[${questionNumber}][options][]" placeholder="Choice 1" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
          <input type="text" name="questions[${questionNumber}][options][]" placeholder="Choice 2" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
        </div>
        <button type="button" onclick="addOption(${questionNumber})" class="mt-2 bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Add Choice</button>
      `;
        } else if (selectedType === 'rating') {
            optionsContainer.innerHTML = `
        <label class="block mb-2 font-semibold">Rating Scale:</label>
        <div class="flex items-center gap-2">
          <label class="text-gray-700">1</label>
          <input type="radio" name="questions[${questionNumber}][rating]" value="1" class="mr-2" />
          <label class="text-gray-700">2</label>
          <input type="radio" name="questions[${questionNumber}][rating]" value="2" class="mr-2" />
          <label class="text-gray-700">3</label>
          <input type="radio" name="questions[${questionNumber}][rating]" value="3" class="mr-2" />
          <label class="text-gray-700">4</label>
          <input type="radio" name="questions[${questionNumber}][rating]" value="4" class="mr-2" />
          <label class="text-gray-700">5</label>
          <input type="radio" name="questions[${questionNumber}][rating]" value="5" class="mr-2" />
        </div>
      `;
        }
    }

    function addOption(questionNumber) {
        const optionsContainer = document.getElementById(`optionsContainer${questionNumber}`).querySelector('.flex.flex-col');
        const newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.name = `questions[${questionNumber}][options][]`;
        newOption.placeholder = `Option ${optionsContainer.children.length + 1}`;
        newOption.className = 'border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full';
        optionsContainer.appendChild(newOption);
    }
</script>
</body>
</html>
