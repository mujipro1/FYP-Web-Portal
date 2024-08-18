const questions = [
    { id: 1, question: "Could you please tell us your name?", key: "farmerName" },
    {id: 2, question: "Please enter your email address.", key: "email"},
    {id: 3, question: "Please enter your phone number.", key: "phone", type: "number" },
    { id: 4, question: "What is the name of your farm?", key: "farmName" },
    { id: 5, question: "Which city is your farm located in?", key: "farmCity", type: "dropdown" },
    { id: 6, question: "Could you provide the complete address of your farm?", key: "farmAddress" },
    { id: 7, question: "What is the total area of your farm in acres?", key: "farmArea", type: "number" },
    { id: 8, question: "Deras are defined as any subdivisions based on area (acres) in your farm. Does your farm have any deras or subdivisions?", key: "has_deras", type:"dropdown"},
    { id: 9, question: "Please enter the number of deras in your farm.", key: "deras", type: "number" },
    { id: 10, question: "Please enter the number of acres in each dera.", key: "deraAcres" },
    {id: 11, question: "If there is any additional information that you want to provide to us, please share.", key: "remarks"},
];

const chatContainer = document.getElementById('chat-container');
let currentQuestionIndex = 0;
const answers = {};

signupForm = document.getElementById('signup-form');

function handleGetStartedClick() {
    document.getElementById('get-started').classList.add('d-none');
    document.getElementById('questionnaire').classList.remove('d-none');    
    renderInputField(); // Render the first question input field immediately
}

function handleNextQuestion(event) {
    event.preventDefault();

    if (currentQuestionIndex === 7) {
        let has_deras = answers['has_deras'];
        console.log(has_deras);
        if (has_deras == 0){
            currentQuestionIndex += 2;
            answers['deras'] = 0;
            answers['deraAcres'] = '[]';
        }
    }

    if (currentQuestionIndex === 9) {
        let deraAcres = [];
        let deras = parseInt(answers['deras']);
        let allFilled = true;

        for (let i = 1; i <= deras; i++) {
            let value = document.getElementById(`dera${i}`).value.trim();
            if (!value) {
                allFilled = false;
                break;
            }
            deraAcres.push(value);
        }

        if (!allFilled) {
            alert("Please fill all the Dera acres inputs.");
            return;
        }

        let questionKey = questions[currentQuestionIndex].key;
        answers[questionKey] = deraAcres;

        // remove the previos dera acres input fields
        for (let i = 1; i <= deras; i++) {
            let element = document.getElementById(`dera${i}`).parentNode.parentNode;
            element.parentNode.removeChild(element);
        }

        chatContainer.innerHTML += deraAcres.map((acres, index) => 
            `<div class="d-flex justify-content-end">
                <p class='answer'><strong>Dera ${index + 1}: ${acres} acres</strong></p>
            </div>`
        ).join('')

        currentQuestionIndex++;

    } else {
        const answerInput = document.getElementById('answer-input');
        const answerValue = answerInput.value.trim();
        if (!answerValue) {
            alert("Please enter a value.");
            return;
        }

        let questionKey = questions[currentQuestionIndex].key;
        answers[questionKey] = answerValue;

        chatContainer.innerHTML += 
            `<div>
                <div class="d-flex justify-content-end">
                    <p class='answer'><strong>${answerValue}</strong></p>
                </div>
            </div>`
        ;

        currentQuestionIndex++;
    }
    if (currentQuestionIndex < questions.length) {
        renderInputField();
    } else {
        chatContainer.innerHTML += 
         `<div>
            <div class="d-flex justify-content-start">
                <p class='question'>Thankyou so much for your time! Please submit the 
                    chat. We will get back to you shortly!</p>
                </div>
        </div>`
        ;
        form = document.getElementById('question-form');
        form.innerHTML = 
            `<div class='d-flex'>
                <button type='button' class='btn btn-orange' onclick='submitAnswers()'>Submit</button>
            </div>`

        ;
    }

    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function renderInputField() {
    const question = questions[currentQuestionIndex];
    const { key, type } = question;
    form = document.getElementById('question-form');

    if (currentQuestionIndex === 9){
        let deras = parseInt(answers['deras']);
        form.innerHTML = ''; // Clear previous input field

        chatContainer.innerHTML += 
            `<div>
                <div class="d-flex justify-content-start">
                    <p class='question'>${questions[currentQuestionIndex].question}</p>
                </div>
            </div>`
        ;

        for (let i = 1; i <= deras; i++){
            chatContainer.innerHTML += `
                <div>
                    <div class="d-flex justify-content-end">
                        <p class='answer my-1'>Dera ${i}
                        <input type="number" id="dera${i}" class="form-control" autofocus>
                        </p>
                    </div>
                </div>
                `
            ;
        }
    } else {
        chatContainer.innerHTML += `
            <div>
                <div class="d-flex justify-content-start">
                    <p class='question'>${questions[currentQuestionIndex].question}</p>
                </div>
            </div>
            `
        ;

        const inputField = document.createElement(type === "dropdown" ? 'select' : 'input');
        inputField.id = 'answer-input';
        inputField.className = 'form-control';
        inputField.autofocus = true;

        if (type === "date") {
            inputField.setAttribute('type', 'date');
        } else if (type === "number") {
            inputField.setAttribute('type', 'number');
        } else if (type === "dropdown" && key == 'farmCity'){
            inputField.innerHTML = `<option value="">Select City</option>`;
            citydata.forEach((city) => {
                inputField.innerHTML += `<option value="${city}">${city}</option>`;
            });
            inputField.addEventListener('change', (e) => {
                answers[key] = e.target.value;
            });
        }
        else if (type === "dropdown" && key == 'has_deras'){
            inputField.innerHTML = `<option value="">Choose Option</option>`;
            inputField.innerHTML += `
            <option value="1">Yes</option>
            <option value="0">No</option>`;
            inputField.addEventListener('change', (e) => {
                answers[key] = e.target.value;
            });}
        else if (key == 'email'){
            inputField.setAttribute('type', 'email');
        }
        else if(key == 'phone'){
            inputField.setAttribute('type', 'phone');
        }
        else {
            inputField.setAttribute('type', 'text');
        }

        inputField.value = answers[key] || '';
        form.innerHTML = ''; // Clear previous input field
        form.appendChild(inputField);

        // focus on input field
        inputField.focus();
    }

    const nextButton = document.createElement('button');
    nextButton.textContent = 'Next';
    nextButton.onclick = handleNextQuestion;
    nextButton.innerHTML =`
    <svg xmlns="http://www.w3.org/2000/svg" class="svg" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="m.172,3.708C-.216,2.646.076,1.47.917.713,1.756-.041,2.951-.211,3.965.282l18.09,8.444c.97.454,1.664,1.283,1.945,2.273H4.048L.229,3.835c-.021-.041-.04-.084-.057-.127Zm3.89,9.292L.309,20.175c-.021.04-.039.08-.054.122-.387,1.063-.092,2.237.749,2.993.521.467,1.179.708,1.841.708.409,0,.819-.092,1.201-.279l18.011-8.438c.973-.456,1.666-1.288,1.945-2.28H4.062Z"/></svg>

    `
    nextButton.className = 'btn btn-orange2 width-20 mx-3';
    form.appendChild(nextButton);
}

function submitAnswers() {
    
    // create a hidden input field for each answer
    for (const key in answers) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = answers[key];
        signupForm.appendChild(input);
    }


    signupForm.submit();
}

