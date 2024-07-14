const questions = [
    { id: 1, question: "Could you please tell us your name?", key: "farmerName" },
    { id: 2, question: "What is the name of your farm?", key: "farmName" },
    { id: 3, question: "Which city is your farm located in?", key: "farmCity", type: "dropdown" },
    { id: 4, question: "Could you provide the complete address of your farm?", key: "farmAddress" },
    { id: 5, question: "What is the total area of your farm in square meters?", key: "farmArea", type: "number" },
    { id: 6, question: "For which year are we recording the crop details?", key: "cropYear", type: "number" },
    { id: 7, question: "When did you sow the crops? (Please provide the date)", key: "sowingDate", type: "date" },
    { id: 8, question: "When did you harvest the crops? (Please provide the date)", key: "harvestingDate", type: "date" },
    { id: 9, question: "Can you list the names of the crops that are currently planted on your farm?", key: "cropNames" },
    { id: 10, question: "For each crop, please provide the details of expenses such as labor, machinery, rent, etc. For example: Wheat got the expenses of Labour, machinery and rent.", key: "cropExpenses" }
];

let currentQuestionIndex = 0;
const answers = {};
const showQuestions = [0];
const citydata = ["City 1", "City 2", "City 3"]; // Example cities

function handleGetStartedClick() {
    document.getElementById('get-started').classList.add('d-none');
    document.getElementById('questionnaire').classList.remove('d-none');
}

function handleNextQuestion(event) {
    event.preventDefault();
    const answerInput = document.getElementById('answer-input');
    const answerValue = answerInput.value;
    if (answerValue.trim()) {
        const questionKey = questions[currentQuestionIndex].key;
        answers[questionKey] = answerValue;

        const chatContainer = document.getElementById('chat-container');
        chatContainer.innerHTML += `
            <div>
                <div class="d-flex justify-content-start">
                    <p class='question'>${questions[currentQuestionIndex].question}</p>
                </div>
                <div class="d-flex justify-content-end">
                    <p class='answer'><strong>${answerValue}</strong></p>
                </div>
            </div>
        `;
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            renderInputField();
        } else {
            chatContainer.innerHTML += `<div><h3>All questions answered!</h3><button onclick="submitAnswers()">Submit</button></div>`;
            document.getElementById('question-form').classList.add('d-none');
        }
        answerInput.value = '';
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

function renderInputField() {
    const question = questions[currentQuestionIndex];
    const { key, type } = question;
    const answerInput = document.getElementById('answer-input');

    if (type === "date") {
        answerInput.setAttribute('type', 'date');
    } else if (type === "number") {
        answerInput.setAttribute('type', 'number');
    } else if (type === "dropdown") {
        const dropdown = document.createElement('select');
        dropdown.className = 'form-control';
        dropdown.innerHTML = `<option value="">Select City</option>`;
        citydata.forEach((city, index) => {
            dropdown.innerHTML += `<option value="${city}">${city}</option>`;
        });
        document.getElementById('question-form').replaceChild(dropdown, answerInput);
        dropdown.addEventListener('change', (e) => {
            answers[key] = e.target.value;
        });
        return;
    } else {
        answerInput.setAttribute('type', 'text');
    }
    answerInput.value = answers[key] || '';
}

function submitAnswers() {
    console.log(answers);
    // Submit answers to the server or handle them as needed
}