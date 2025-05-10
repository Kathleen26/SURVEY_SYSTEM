<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Opinion Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col">
  <div class="h-10 w-full" style="background: linear-gradient(to right, #4dc6d9, #004a9f);"></div>
<main class="flex-grow bg-gradient-to-b from-[#f3f8fc] to-[#a9c9e8] flex flex-col items-center pt-48 pb-20 px-4">
  <form action="save_response.php" method="POST" class="w-full max-w-lg flex flex-col">
    <h1 class="text-gray-700 font-semibold text-xl md:text-2xl mb-6">Your opinion matters to us.</h1>
    <label for="answer" class="block text-gray-400 font-semibold mb-2 text-lg md:text-xl">Please enter your concerns:</label>
    <textarea id="answer" name="answer" rows="8" class="w-full max-w-lg border border-gray-700 resize-none mb-8"></textarea>
    <button type="submit" class="bg-blue-500 text-white text-sm font-semibold px-5 py-1 rounded-full hover:bg-blue-600 transition">SUBMIT</button>
  </form>
</main>
</body>
</html>