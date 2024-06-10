import React from 'react';
import ReactDOM from 'react-dom';
import Home  from './Home';

function App() {
    return (
        <>
            <Home />
        </>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}