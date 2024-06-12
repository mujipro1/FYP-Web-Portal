import React from 'react';
import SignupForm from './SignupForm';
import ReactDOM from 'react-dom';
import Home  from './Home';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';

function App() {
        return (
          <Router>
            <Routes>
              <Route path="/" exact element={<Home/>} />
              <Route path="/signup" element={<SignupForm/>} />
            </Routes>
          </Router>
        );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}