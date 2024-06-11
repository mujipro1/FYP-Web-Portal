// Option1.js
import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import { first } from 'lodash';

function Option1() {
    return (
        <div>
        <Container className='p-3 mt-3 section back-grey'>
            <Row>
                <Col className='text-center my-3'>
                    <h4>Create Form</h4>
                </Col>

                <form>
                    <Container>

                    <Row>
                        <Col  xs={7}>
                            <div className="d-flex justify-content-center align-items-center">
                                <label className='m-3'>Farm Name</label>
                                <input type='text' className='form-control w-75'/>
                            </div>

                            <div className="d-flex justify-content-center align-items-center">
                                <label className='m-3'>City</label>
                                <input type='text' className='form-control w-75'/>
                            </div>

                        </Col>
                        <Col className='box-cont'></Col>
                    </Row>
                    </Container>
                </form>
            </Row>
        </Container>

        </div>
    );
}

export default Option1;