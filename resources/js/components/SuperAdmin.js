// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import Sidebar from './Sidebar';
import Option1 from './SideBarComponents';
import { Container, Col, Row } from 'react-bootstrap';

function SuperAdmin() {
    return (
        <>
            <NavBar />
            <Container fluid>
            <Row>
                
                <Router>
                <Col xs={2}>
                    <Sidebar />
                    </Col>
                <Col>
                    <Routes>
                        <Route path="/option1" element={<Option1/>} />
                    </Routes>
                </Col>
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
