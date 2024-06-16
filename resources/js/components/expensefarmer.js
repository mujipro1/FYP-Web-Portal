import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import { Container, Col, Row, Form, Button } from 'react-bootstrap';

function Expensefarmer() {
    const [step, setStep] = useState(1);

    const handleNext = () => {
        setStep(step + 1);
    };

    const handleBack = () => {
        setStep(1);
    };

    const handleFarmClick = () => {
        setStep(2);
    };

    return (
        <>
            <NavBar />
            {step === 1 ? (
                <Expensefarmerstep1 onFarmClick={handleFarmClick} />
            ) : (
                <Expensefarmerstep2 onNext={handleNext} onBack={handleBack} />
            )}
            <Footer />
        </>
    );
}
function Expensefarmerstep1({ onFarmClick }) {
    return (
        <Container fluid className="text-center my-4">
            <Row className="justify-content-center">
                <Col>
                    <h1 className="bold-text large-text color">Welcome Ali</h1>
                </Col>
            </Row>
            <Row className="justify-content-center mt-4">
                <Col xs={12} md={8} lg={6}>
                    <div className="farm-container">
                        <div className="farm-item" onClick={onFarmClick}>
                            <img
                                src={require('../../css/farm1.jpg').default}
                                alt="Farm 1"
                                className="farm-image"
                            />
                            <h3 className="bold-text mt-2 color size">Farm 1</h3>
                        </div>
                        <div className="farm-item" onClick={onFarmClick}>
                            <img
                                src={require('../../css/farm2.avif').default}
                                alt="Farm 2"
                                className="farm-image"
                            />
                            <h3 className="bold-text mt-2 color size">Farm 2</h3>
                        </div>
                        <div className="farm-item" onClick={onFarmClick}>
                            <img
                                src={require('../../css/farm3.jpg').default}
                                alt="Farm 3"
                                className="farm-image"
                            />
                            <h3 className="bold-text mt-2 color size">Farm 3</h3>
                        </div>
                    </div>
                </Col>
            </Row>
        </Container>
    );
}


function Expensefarmerstep2({ onNext, onBack }) {
    return (
        <Container fluid className="text-center my-4">
            <Row className="justify-content-center">
                <Col>
                    <h2 className="bold-text color">Add Expenses</h2>
                </Col>
            </Row>
            <Row className="justify-content-center mt-4">
                <Col xs={12} md={6} lg={4}>
                    <Form>
                        <Form.Group controlId="selectYear">
                            <Form.Label className="bold-text color">Select year</Form.Label>
                            <Form.Control as="select" defaultValue="">
                                <option value="" disabled>Select year</option>
                                <option>2021</option>
                                <option>2022</option>
                                <option>2023</option>
                                <option>2024</option>
                            </Form.Control>
                        </Form.Group>
                        <Form.Group controlId="selectCrop" className="mt-3">
                            <Form.Label className="bold-text color">Select crop</Form.Label>
                            <Form.Control as="select" defaultValue="">
                                <option value="" disabled>Select crop</option>
                                <option>Wheat</option>
                                <option>Rice</option>
                                <option>Corn</option>
                                <option>Barley</option>
                            </Form.Control>
                        </Form.Group>
                    </Form>
                </Col>
            </Row>
            <Row className="justify-content-center mt-4">
                <Col xs={6} className="text-right">
                    <Button onClick={onBack} variant="secondary">Back</Button>
                </Col>
                <Col xs={6} className="text-left">
                    <Button onClick={onNext} variant="primary">Next</Button>
                </Col>
            </Row>
        </Container>
    );
}

export default Expensefarmer;

if (document.getElementById('expensefarmer')) {
    ReactDOM.render(<Expensefarmer />, document.getElementById('expensefarmer'));
}
