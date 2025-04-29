Get Started

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "survey_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch survey data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT title FROM surveys WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $survey = $result->fetch_assoc();
    } else {
        echo "Survey not found.";
        exit();
    }
}

// Update survey title
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $title = $conn->real_escape_string($_POST['title']);

    $sql = "UPDATE surveys SET title = '$title' WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: survey_list.php");
        exit();
    } else {
        echo "Error updating survey: " . $conn->error;
    }
}

// Delete survey and related data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['id']);

    // Delete related options
    $sql = "DELETE FROM options WHERE question_id IN (SELECT id FROM questions WHERE survey_id = $id)";
    $conn->query($sql);

    // Delete related questions
    $sql = "DELETE FROM questions WHERE survey_id = $id";
    $conn->query($sql);

    // Delete the survey
    $sql = "DELETE FROM surveys WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: survey_list.php");
        exit();
    } else {
        echo "Error deleting survey: " . $conn->error;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Survey</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Survey</h1>
    <form action="edit_survey.php" method="POST" class="bg-white shadow-md rounded-md p-6 max-w-lg">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        <div class="mb-4">
            <label for="title" class="block text-lg font-semibold mb-2">Survey Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($survey['title']); ?>" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" required>
        </div>
        <div class="flex justify-between">
            <button type="submit" name="update" class="bg-[#0ea5e9] text-white rounded-md px-6 py-2 hover:bg-[#0c87cc] transition">Save Changes</button>
            <button type="submit" name="delete" class="bg-red-500 text-white rounded-md px-6 py-2 hover:bg-red-700 transition" onclick="return confirm('Are you sure you want to delete this survey?');">Delete Survey</button>
        </div>
    </form>
</body>
</html>