// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import Sidebar from './Sidebar';
import CreateFarm from './CreateFarm';
import { Container, Col, Row } from 'react-bootstrap';


function SuperAdmin() {
    return (
        <>
            <NavBar />
            <Container fluid>
            <Row>
                
                <Router>
                    <div className="container">
                        <div className="row">
                            <div className="col-md-3 mt-3 sidebarcol">
                                <Sidebar />
                            </div>
                            <div className="col-md-9 ">
                                <Routes>
                                    <Route path="/superadmin/createfarm" element={<CreateFarm/>} />
                                </Routes>
                            </div>
                        </div>
                    </div>
                    </Router> 
            <Footer />
            </Row>
            </Container>
        </>
    );
}




export default SuperAdmin;

if (document.getElementById('SuperAdmin')) {
    ReactDOM.render(<SuperAdmin />, document.getElementById('SuperAdmin'));
}
