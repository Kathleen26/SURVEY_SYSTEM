<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Survey Form</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-[#f0f7ff] to-[#7ea9d9] flex flex-col">
  <header class="bg-[#0ea5e9] h-12 w-full"></header>
<main class="flex-grow flex justify-center items-start p-6">
   <form id="surveyForm" action="save_survey.php" method="POST" class="bg-white bg-opacity-90 shadow-md rounded-md w-full max-w-5xl p-8 space-y-6">
    <div>
        <label for="title" class="font-semibold block mb-2">Survey Title:</label>
        <input id="title" name="title" type="text" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" required />
    </div>
    <div>
        <label for="description" class="font-semibold block mb-2">Survey Description:</label>
        <textarea id="description" name="description" rows="5" class="w-full border border-gray-300 rounded-md px-3 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]"></textarea>
    </div>
    <div id="questionsContainer">
      <fieldset class="bg-gray-50 border border-gray-200 rounded-md p-4">
        <legend class="font-semibold mb-2">Question 1:</legend>
        <input type="text" placeholder="Enter question" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] mb-4 w-full" />
        <div class="mt-4">
          <label for="questionType1" class="block mb-2 font-semibold">Select Question Type:</label>
          <select id="questionType1" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" onchange="updateQuestionType(this, 1)">
            <option value="text">Text Question</option>
            <option value="multipleChoice">Multiple Choice</option>
            <option value="radio">Radio Button Choices</option>
            <option value="rating">Rating Question</option>
          </select>
        </div>
        <div id="optionsContainer1" class="mt-4">
          <!-- Options will be dynamically added here based on the selected question type -->
        </div>
      </fieldset>
    </div>
    <div class="flex justify-center">
      <button type="button" onclick="addQuestion()" class="bg-[#0ea5e9] text-white rounded-md px-6 py-2 hover:bg-[#0c87cc] transition">Add Another Question</button>
    </div>
    <button type="submit" class="w-full bg-[#0ea5e9] text-white rounded-md py-3 text-lg hover:bg-[#0c87cc] transition">Create Survey</button>
</form>
</main>
<script>
    let questionCount = 1;

    function addQuestion() {
        questionCount++;
        const questionsContainer = document.getElementById('questionsContainer');

        const newQuestion = document.createElement('fieldset');
        newQuestion.className = 'bg-gray-50 border border-gray-200 rounded-md p-4 mt-4';
        newQuestion.innerHTML = `
      <legend class="font-semibold mb-2">Question ${questionCount}:</legend>
      <input type="text" placeholder="Enter question" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] mb-4 w-full" />
      <div class="mt-4">
        <label for="questionType${questionCount}" class="block mb-2 font-semibold">Select Question Type:</label>
        <select id="questionType${questionCount}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9]" onchange="updateQuestionType(this, ${questionCount})">
          <option value="text">Text Question</option>
          <option value="multipleChoice">Multiple Choice</option>
          <option value="radio">Radio Button Choices</option>
          <option value="rating">Rating Question</option>
        </select>
      </div>
      <div id="optionsContainer${questionCount}" class="mt-4">
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
          <input type="text" placeholder="Option 1" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
          <input type="text" placeholder="Option 2" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
        </div>
        <button type="button" onclick="addOption(${questionNumber})" class="mt-2 bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Add Option</button>
      `;
        } else if (selectedType === 'radio') {
            optionsContainer.innerHTML = `
        <label class="block mb-2 font-semibold">Radio Button Choices:</label>
        <div class="flex flex-col gap-2">
          <input type="text" placeholder="Choice 1" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
          <input type="text" placeholder="Choice 2" class="border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full" />
        </div>
        <button type="button" onclick="addOption(${questionNumber})" class="mt-2 bg-[#0ea5e9] text-white rounded-md px-4 py-2 hover:bg-[#0c87cc] transition">Add Choice</button>
      `;
        } else if (selectedType === 'rating') {
            optionsContainer.innerHTML = `
        <label class="block mb-2 font-semibold">Rating Scale:</label>
        <div class="flex items-center gap-2">
          <label class="text-gray-700">1</label>
          <input type="radio" name="rating${questionNumber}" value="1" class="mr-2" />
          <label class="text-gray-700">2</label>
          <input type="radio" name="rating${questionNumber}" value="2" class="mr-2" />
          <label class="text-gray-700">3</label>
          <input type="radio" name="rating${questionNumber}" value="3" class="mr-2" />
          <label class="text-gray-700">4</label>
          <input type="radio" name="rating${questionNumber}" value="4" class="mr-2" />
          <label class="text-gray-700">5</label>
          <input type="radio" name="rating${questionNumber}" value="5" class="mr-2" />
        </div>
      `;
        }
    }

    function addOption(questionNumber) {
        const optionsContainer = document.getElementById(`optionsContainer${questionNumber}`).querySelector('.flex');
        const newOption = document.createElement('input');
        newOption.type = 'text';
        newOption.placeholder = `Option ${optionsContainer.children.length + 1}`;
        newOption.className = 'border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-[#0ea5e9] w-full';
        optionsContainer.appendChild(newOption);
    }
</script>
</body>
</html>