document.getElementById('surveyForm').addEventListener('submit', function(event) {
    const ageInput = document.getElementById('age');
    const ageWarning = document.getElementById('ageWarning');

    if (ageInput.value < 18) {
        ageWarning.style.display = 'block';
        event.preventDefault(); 
    } else {
        ageWarning.style.display = 'none';
    }
});
