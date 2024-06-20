import React, { useState } from 'react';
import NavBar from './NavBar';
import Footer from './Footer';
import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';

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
          <div className="col-md-6 d-flex justify-content-center align-items-center">
            <div className="text-center">  
              {!showQuestionnaire ? (
                <GetStarted onGetStartedClick={handleGetStartedClick} />
              ) : (
                <Questionnaire />
              )}
            </div>
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
      <h3 className=''>Get Registered!</h3>
      <p className='p-3'>Register your farm with us by answering some short questions by our agent Hina!</p>
      <button onClick={onGetStartedClick} className="text-light btn-brown">Start</button>
    </>
  );
};

const Questionnaire = () => {
  return (
    <>
      <h3>Questionnaire</h3>
      <p>Please answer the following questions:</p>
      {/* Add your questionnaire form here */}
    </>
  );
};

export default SignupForm;
