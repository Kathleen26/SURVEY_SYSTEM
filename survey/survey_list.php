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

// Fetch surveys
$sql = "SELECT id, title, description, created_at FROM surveys ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">Survey List</h1>
    <a href="create_survey.php" class="bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Create New Survey</a>
    <div class="mt-6">
        <?php if ($result->num_rows > 0): ?>
            <ul class="space-y-4">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <li class="bg-white shadow-md rounded-md p-4">
                        <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($row['title']); ?></h2>
                        <p class="text-gray-700"><?php echo htmlspecialchars($row['description']); ?></p>
                        <small class="text-gray-500">Created at: <?php echo $row['created_at']; ?></small>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-700">No surveys found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>