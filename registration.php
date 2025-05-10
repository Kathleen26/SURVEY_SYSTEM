<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];

    if (!empty($age) && $gender !== 'Select' && $address !== 'Select' && $age >= 18) {
        $stmt = $pdo->prepare("INSERT INTO survey_responses (age, gender, address) VALUES (:age, :gender, :address)");
        $stmt->execute([
            ':age' => $age,
            ':gender' => $gender,
            ':address' => $address,
        ]);
        echo "<script>alert('Survey submitted successfully!'); window.location.href = 'survey_question.php';</script>";
    } else {
        echo "<script>alert('Please fill out all required fields correctly. Age must be 18 or above.');</script>";
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Form Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function validateForm(event) {
            const ageInput = document.getElementById('age');
            const age = parseInt(ageInput.value, 10);

            if (age < 18) {
                event.preventDefault();
                const alertBox = document.getElementById('age-alert');
                alertBox.style.display = 'block';
                ageInput.focus();
            }
        }
    </script>
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f7fbff] to-[#9cc7e1]">
    <header class="w-full h-12 flex items-center" style="background: linear-gradient(90deg, #4de0e0 0%, #0f3ea0 100%)"></header>
    <main class="flex justify-center items-center min-h-[calc(100vh-3rem)] px-4">
        <form method="POST" class="bg-[#a9c6e1] rounded-2xl p-16 w-full max-w-3xl" onsubmit="validateForm(event)">
            <h2 class="text-center font-bold text-lg mb-8">ENTER THE FOLLOWING *</h2>
            <div class="mb-6">
                <label for="age" class="block mb-1 text-[14px] tracking-widest">AGE *</label>
                <input id="age" name="age" type="text" class="w-full border border-black px-2 py-1" />
                <div id="age-alert" class="text-red-500 text-sm mt-1" style="display: none;">Age must be 18 or above.</div>
            </div>
            <div class="mb-6">
                <label for="gender" class="block mb-1 text-[14px] tracking-widest">GENDER *</label>
                <select id="gender" name="gender" class="w-full border border-black px-2 py-1">
                    <option>Select</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>
            <div class="mb-8">
                <label for="address" class="block mb-1 text-[14px] tracking-widest">ADDRESS (BARANGAY) *</label>
                <select id="address" name="address" class="w-full border border-black px-2 py-1">
                    <option>Select</option>
                    <option value="Barangka">Barangka</option>
                    <option value="Concepcion Dos">Concepcion Dos</option>
                    <option value="Concepcion Uno">Concepcion Uno</option>
                    <option value="Fortune">Fortune</option>
                    <option value="I.V.C">I.V.C</option>
                    <option value="Jesus dela Peña">Jesus dela Peña</option>
                    <option value="Kalumpang">Kalumpang</option>
                    <option value="Malanday">Malanday</option>
                    <option value="Marikina Heights">Marikina Heights</option>
                    <option value="Nangka">Nangka</option>
                    <option value="Parang">Parang</option>
                    <option value="San Roque">San Roque</option>
                    <option value="Sta. Elena">Sta. Elena</option>
                    <option value="Sto. Niño">Sto. Niño</option>
                    <option value="Tañong">Tañong</option>
                    <option value="Tumana">Tumana</option>
                </select>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="bg-[#1e90f0] text-white px-8 py-2 rounded-full text-sm font-semibold tracking-wide">ENTER</button>
            </div>
        </form>
    </main>
</body>
</html>