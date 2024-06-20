// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import { Container, Col, Row } from 'react-bootstrap'
import { useState } from 'react';

function Manager() {
    return (
        <>
            <NavBar />
            <ChatStyleQuestionnaire/>
          <Footer />
        </>
    );
}




const questions = [
  { id: 1, question: "Could you please tell us your name?", key: "farmerName" },
  { id: 2, question: "What is the name of your farm?", key: "farmName" },
  { 
    id: 3, 
    question: "Which city is your farm located in?", 
    key: "farmCity" 
  },
  { 
    id: 4, 
    question: "Could you provide the complete address of your farm?", 
    key: "farmAddress" 
  },
  { id: 5, question: "What is the total area of your farm in square meters?", key: "farmArea" },
  { id: 6, question: "For which year are we recording the crop details?", key: "cropYear" },
  { id: 7, question: "When did you sow the crops? (Please provide the date)", key: "sowingDate" },
  { id: 8, question: "When did you harvest the crops? (Please provide the date)", key: "harvestingDate" },
  { id: 9, question: "Can you list the names of the crops that are currently planted on your farm?", key: "cropNames" },
  { 
    id: 10, 
    question: "For each crop, please provide the details of expenses such as labor, machinery, rent, etc. For example: Wheat got the expenses of Labour, machinery and rent.", 
    key: "cropExpenses" 
  }
];


const ChatStyleQuestionnaire = () => {
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [answers, setAnswers] = useState({});
    const [showQuestions, setShowQuestions] = useState([0]);
  
    const handleAnswerChange = (e) => {
      setAnswers({
        ...answers,
        [questions[currentQuestionIndex].key]: e.target.value,
      });
    };
  
    const handleNextQuestion = (e) => {
      e.preventDefault();
      setCurrentQuestionIndex(currentQuestionIndex + 1);
      setShowQuestions([...showQuestions, currentQuestionIndex + 1]);
    };
  
    return (
      <div className='container'>
        <div className="row">

        <h2>Farm Questionnaire</h2>
        <div style={{ display: 'flex', flexDirection: 'column' }}>
          {showQuestions.map((index) => (
            <div key={questions[index].id} style={{ margin: '10px 0' }}>
              <p>{questions[index].question}</p>
              {index === currentQuestionIndex ? (
                <form onSubmit={handleNextQuestion}>
                  <input
                    type="text"
                    value={answers[questions[index].key] || ''}
                    onChange={handleAnswerChange}
                    autoFocus
                  />
                  <button type="submit" style={{ marginLeft: '10px' }}>
                    Next
                  </button>
                </form>
              ) : (
                  <p><strong>{answers[questions[index].key]}</strong></p>
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
      </div>
    </div>
    );
  };


if (document.getElementById('Manager')) {
    ReactDOM.render(<Manager />, document.getElementById('Manager'));
}
