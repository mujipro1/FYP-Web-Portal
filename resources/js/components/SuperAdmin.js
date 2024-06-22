// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import Sidebar from './Sidebar';
import CreateFarm from './CreateFarm';
import { Container, Col, Row } from 'react-bootstrap';
import  RequestComponent from './Requests';
import { useState } from 'react';

function SuperAdmin() {
  const [crops, setCrops] = useState(['Maize', 'Wheat', 'Rice']);

    return (
        <>
            <NavBar />
            <Container fluid>
            <Row>
                
                <Router>
                    <div className="container">
                        <div className="row">
                            <div className="col-md-2 mt-3 sidebarcol">
                                <Sidebar />
                            </div>
                            <div className="col-md-10 ">
                                <Routes>
                                    <Route path="/superadmin/" element={<SuperAdminHome/>} />
                                    <Route path="/superadmin/createfarm" element={<CreateFarm/>} />
                                    <Route path="/superadmin/requests" element={<RequestComponent/>} />
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

const SuperAdminHome = () => {
    return (
        <>
        <row>
            <Col>
                <div className="container">
                    <div className="row">
                        <div className="col-md-12 my-3">
                            <div className="text-center">
                                <h2 className='mx-4'>Welcome Hassan!</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </Col>
        </row>
        </>
    );
};


export default SuperAdmin;

if (document.getElementById('SuperAdmin')) {
    ReactDOM.render(<SuperAdmin />, document.getElementById('SuperAdmin'));
}
