<?php
require_once 'db_connection.php';

if (!isset($_GET['survey_id']) || !is_numeric($_GET['survey_id'])) {
    // If no survey_id provided, try to get the first survey id from the database
    $stmt = $pdo->query("SELECT id FROM surveys LIMIT 1");
    $firstSurvey = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($firstSurvey) {
        $survey_id = (int)$firstSurvey['id'];
    } else {
        die('No surveys available.');
    }
} else {
    $survey_id = (int)$_GET['survey_id'];
}

// Fetch survey details
$stmt = $pdo->prepare("SELECT title, description FROM surveys WHERE id = ?");
$stmt->execute([$survey_id]);
$survey = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$survey) {
    die('Survey not found.');
}

// Fetch questions
$stmt = $pdo->prepare("SELECT id, question_text, question_type FROM s_questions WHERE survey_id = ?");
$stmt->execute([$survey_id]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch options for questions that need them
$options = [];
foreach ($questions as $question) {
    if ($question['question_type'] === 'multipleChoice' || $question['question_type'] === 'radio') {
        $stmt = $pdo->prepare("SELECT id, option_text FROM question_options WHERE question_id = ?");
        $stmt->execute([$question['id']]);
        $options[$question['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($survey['title']); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-[#7ea9d9] flex flex-col">
  <header class="bg-[#0ea5e9] h-12 w-full"></header>
  <main class="flex-grow flex justify-center items-start p-6">
    <form action="submit_answer.php" method="POST" class="bg-white bg-opacity-90 shadow-md rounded-md w-full max-w-5xl p-8 space-y-6">
      <h1 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($survey['title']); ?></h1>
      <p class="mb-6"><?php echo nl2br(htmlspecialchars($survey['description'])); ?></p>

      <input type="hidden" name="survey_id" value="<?php echo $survey_id; ?>" />

      <?php foreach ($questions as $index => $question): ?>
        <fieldset class="bg-gray-50 border border-gray-200 rounded-md p-4">
          <legend class="font-semibold mb-2">Question <?php echo $index + 1; ?>:</legend>
          <p class="mb-2"><?php echo htmlspecialchars($question['question_text']); ?></p>

          <?php if ($question['question_type'] === 'text'): ?>
            <textarea name="answers[<?php echo $question['id']; ?>]" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" required></textarea>

          <?php elseif ($question['question_type'] === 'multipleChoice'): ?>
            <?php if (isset($options[$question['id']])): ?>
              <?php foreach ($options[$question['id']] as $option): ?>
                <label class="inline-flex items-center gap-2 mb-2">
                  <input type="checkbox" name="answers[<?php echo $question['id']; ?>][]" value="<?php echo htmlspecialchars($option['option_text']); ?>" class="form-checkbox" />
                  <span><?php echo htmlspecialchars($option['option_text']); ?></span>
                </label><br/>
              <?php endforeach; ?>
            <?php endif; ?>

          <?php elseif ($question['question_type'] === 'radio'): ?>
            <?php if (isset($options[$question['id']])): ?>
              <?php foreach ($options[$question['id']] as $option): ?>
                <label class="inline-flex items-center gap-2 mb-2">
                  <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo htmlspecialchars($option['option_text']); ?>" class="form-radio" required />
                  <span><?php echo htmlspecialchars($option['option_text']); ?></span>
                </label><br/>
              <?php endforeach; ?>
            <?php endif; ?>

          <?php elseif ($question['question_type'] === 'rating'): ?>
            <div class="flex items-center gap-4">
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <label class="inline-flex items-center gap-1">
                  <input type="radio" name="answers[<?php echo $question['id']; ?>]" value="<?php echo $i; ?>" class="form-radio" required />
                  <span><?php echo $i; ?></span>
                </label>
              <?php endfor; ?>
            </div>
          <?php endif; ?>
        </fieldset>
      <?php endforeach; ?>

      <button type="submit" class="w-full bg-[#0ea5e9] text-white rounded-md py-3 text-lg hover:bg-[#0c87cc] transition">Submit Survey</button>
    </form>
  </main>
</body>
</html>
