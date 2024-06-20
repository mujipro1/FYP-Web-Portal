import React, { useState, useEffect, useRef } from 'react';
import NavBar from './NavBar';
import Footer from './Footer';

const SignupForm = () => {
  const [showQuestionnaire, setShowQuestionnaire] = useState(false);

  const handleGetStartedClick = () => {
    setShowQuestionnaire(true);
  };

  return (
    <>
      <NavBar />
      <div className="container my-5">
        <div className="row box-cont">
          <div className="col-md-6">
            <img src='images/bgimage.jpg' alt="Farm Background" id='signup-image' />
          </div>
          <div className="col-md-6 ">
              {!showQuestionnaire ? (
                <GetStarted onGetStartedClick={handleGetStartedClick} />
              ) : (
                <ChatStyleQuestionnaire />
              )}
          </div>
        </div>
      </div>
      <Footer />
    </>
  );
};

const GetStarted = ({ onGetStartedClick }) => {
  return (
    <>
    <div className="d-flex justify-content-center align-items-center flex-column min-height">

      <h3 className=''>Get Registered!</h3>
      <p className='text-center p-3'>Register your farm with us by answering some short questions by our agent Hina!</p>
      <button onClick={onGetStartedClick} className="text-light btn-brown">Start</button>
    </div>
    </>
  );
};


const questions = [
  { id: 1, question: "Could you please tell us your name?", key: "farmerName" },
  { id: 2, question: "What is the name of your farm?", key: "farmName" },
  { id: 3, question: "Which city is your farm located in?", key: "farmCity" },
  { id: 4, question: "Could you provide the complete address of your farm?", key: "farmAddress" },
  { id: 5, question: "What is the total area of your farm in square meters?", key: "farmArea" },
  { id: 6, question: "For which year are we recording the crop details?", key: "cropYear" },
  { id: 7, question: "When did you sow the crops? (Please provide the date)", key: "sowingDate" },
  { id: 8, question: "When did you harvest the crops? (Please provide the date)", key: "harvestingDate" },
  { id: 9, question: "Can you list the names of the crops that are currently planted on your farm?", key: "cropNames" },
  { id: 10, question: "For each crop, please provide the details of expenses such as labor, machinery, rent, etc. For example: Wheat got the expenses of Labour, machinery and rent.", key: "cropExpenses" }
];

const ChatStyleQuestionnaire = () => {
  const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
  const [answers, setAnswers] = useState({});
  const [showQuestions, setShowQuestions] = useState([0]);
  const chatContainerRef = useRef(null);

  const handleAnswerChange = (e) => {
    setAnswers({
      ...answers,
      [questions[currentQuestionIndex].key]: e.target.value,
    });
  };

  const handleNextQuestion = (e) => {
    e.preventDefault();
    if (answers[questions[currentQuestionIndex].key]) {
      setCurrentQuestionIndex(currentQuestionIndex + 1);
      setShowQuestions([...showQuestions, currentQuestionIndex + 1]);
    }
  };

  useEffect(() => {
    // Scroll to the bottom of the chat container whenever a new question/answer is added
    if (chatContainerRef.current) {
      chatContainerRef.current.scrollTop = chatContainerRef.current.scrollHeight;
    }
  }, [showQuestions]);

  return (
    <div className='container'>
      <div className="row ">
        <div className="d-flex justify-content-start mb-3  align-items-center">
          <img src='images/farm1.jpg' alt="Hina" id='profile-image' />
          <h6 className='mx-3 mt-2'>Hina</h6>
        </div>
        <div className="questionnaire" ref={chatContainerRef}>
        <div className="d-flex justify-content-start">
            <p className='question'>Hello, I am Hina and I will be asking you some questions, So lets get started!</p>
        </div>

        {showQuestions.map((index) => (
          <div key={questions[index].id} className=''>
            <div className="d-flex justify-content-start">
            <p className='question'>{questions[index].question}</p>
            </div>
            {index < currentQuestionIndex && (
            <div className="d-flex justify-content-end">
              <p className='answer'><strong>{answers[questions[index].key]}</strong></p>
            </div>
            )}
          </div>
        ))}
        {currentQuestionIndex === questions.length && (
          <div>
            <h3>All questions answered!</h3>
            <button onClick={() => console.log(answers)}>Submit</button>
          </div>
        )}
      </div>
      {currentQuestionIndex < questions.length && (
        <form onSubmit={handleNextQuestion} className='fixed-form'>
          <input
            type="text"
            value={answers[questions[currentQuestionIndex]?.key] || ''}
            onChange={handleAnswerChange}
            autoFocus
            />
          <button type="submit" className='next-button'>Next</button>
        </form>
      )}
      </div>
    </div>
  );
};

export default SignupForm;
