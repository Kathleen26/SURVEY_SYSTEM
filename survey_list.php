<?php
require_once 'db_connection.php';

try {
$stmt = $pdo->query("SELECT id, title, description FROM surveys");
    $surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survey List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-[#7ea9d9] flex flex-col">
    <header class="bg-[#0ea5e9] h-12 w-full"></header>
    <main class="flex-grow flex justify-center items-start p-6">
        <div class="bg-white bg-opacity-90 shadow-md rounded-md w-full max-w-5xl p-8 space-y-6">
            <h1 class="text-2xl font-bold mb-4">Survey List</h1>
            <div class="mb-4 text-right">
                <a href="create_survey.php" class="inline-block bg-[#0ea5e9] text-white rounded-md px-6 py-2 hover:bg-[#0c87cc] transition">Create New Survey</a>
            </div>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Title</th>
                        <th class="border border-gray-300 px-4 py-2">Description</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($surveys) > 0): ?>
                        <?php foreach ($surveys as $row): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $row['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['description']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="edit_survey.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:underline">Edit</a>
                                    <a href="delete_survey.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:underline ml-4" onclick="return confirm('Are you sure you want to delete this survey?');">Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="bg-gray-50 px-4 py-2">
                                    <strong>Questions and Options:</strong>
                                    <ul class="list-disc list-inside mt-2">
                                    <?php
                                        $survey_id = $row['id'];
$questions_sql = "SELECT id, question_text, question_type FROM s_questions WHERE survey_id = ?";
                                        $questions_stmt = $pdo->prepare($questions_sql);
                                        $questions_stmt->execute([$survey_id]);
                                        $questions_result = $questions_stmt->fetchAll(PDO::FETCH_ASSOC);
                                        if (count($questions_result) > 0) {
                                            foreach ($questions_result as $question) {
                                                echo "<li><strong>" . htmlspecialchars($question['question_text']) . "</strong> (" . htmlspecialchars($question['question_type']) . ")";
                                                $qtype = strtolower($question['question_type']);
                                                $qtype = preg_replace('/[^a-z0-9]/', '', $qtype);
                                                if ($qtype === 'multiplechoice' || $qtype === 'radio') {
                                                    $options_sql = "SELECT option_text FROM question_options WHERE question_id = ?";
                                                    $options_stmt = $pdo->prepare($options_sql);
                                                    $options_stmt->execute([$question['id']]);
                                                    $options_result = $options_stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    if (count($options_result) > 0) {
                                                        echo "<ul class='list-none list-inside ml-6'>";
                                                        foreach ($options_result as $option) {
                                                            if ($qtype === 'multiplechoice') {
                                                                echo "<li><label><input type='checkbox' disabled> " . htmlspecialchars($option['option_text']) . "</label></li>";
                                                            } else if ($qtype === 'radio') {
                                                                echo "<li><label><input type='radio' disabled> " . htmlspecialchars($option['option_text']) . "</label></li>";
                                                            }
                                                        }
                                                        echo "</ul>";
                                                    } else {
                                                        echo "<p class='ml-6 text-sm text-gray-500'>No options found.</p>";
                                                    }
                                                } else if ($qtype === 'rating') {
                                                    // Display rating scale options 1 to 5 as radio buttons disabled
                                                    echo "<ul class='list-none list-inside ml-6 flex gap-4'>";
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo "<li><label><input type='radio' disabled> " . $i . "</label></li>";
                                                    }
                                                    echo "</ul>";
                                                } else if ($qtype === 'text') {
                                                    // Display a disabled text input for text question type
                                                    echo "<input type='text' disabled class='border border-gray-300 rounded px-2 py-1 mt-2 w-full' placeholder='Text answer' />";
                                                }
                                                echo "</li>";
                                            }
                                        } else {
                                            echo "<li>No questions found.</li>";
                                        }
                                    ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4">No surveys found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
       
        </div>
    </main>
</body>
</html>
