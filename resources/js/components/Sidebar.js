import React from 'react';
import { Link } from 'react-router-dom';

function Sidebar() {
    return (
        <div className="sidebar">
            <ul>
                <li><Link to="/superadmin/home">Home</Link></li>
                <li><Link to="/superadmin/createfarm">Create Farm</Link></li>
                <li><Link to="/superadmin/option2">Option 2</Link></li>
                <li><Link to="/superadmin/option3">Option 3</Link></li>
                {/* Add more links as needed */}
            </ul>
        </div>
    );
}

export default Sidebar;
