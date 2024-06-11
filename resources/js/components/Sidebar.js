import React from 'react';
import { Link } from 'react-router-dom';

function Sidebar() {
    return (
        <div className="sidebar">
            <ul>
                <li><Link to="/option1">Option 1</Link></li>
                <li><Link to="/option2">Option 2</Link></li>
                <li><Link to="/option3">Option 3</Link></li>
                {/* Add more links as needed */}
            </ul>
        </div>
    );
}

export default Sidebar;
