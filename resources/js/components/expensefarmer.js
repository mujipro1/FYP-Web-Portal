// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import { Container, Col, Row } from 'react-bootstrap';

function Expensefarmer() {

    return (
        <>
            <NavBar />
            <Container fluid>
            <Row>
                
               
            <Footer />
            </Row>
            </Container>
        </>
    );
}




export default expensefarmer;

if (document.getElementById('expensefarmer')) {
    ReactDOM.render(<Expensefarmer />, document.getElementById('expensefarmer'));
}
