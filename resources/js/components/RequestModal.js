import React from 'react';
import { Modal, Button } from 'react-bootstrap';

const RequestModal = ({ show, onHide, request }) => (
  <Modal show={show} onHide={onHide}>
    <Modal.Header closeButton>
      <Modal.Title>Request Details</Modal.Title>
    </Modal.Header>
    <Modal.Body>
      <p><strong>Name:</strong> {request.name}</p>
      <p><strong>Date:</strong> {request.date}</p>
      <p><strong>Description:</strong> {request.desc}</p>
      <p><strong>Status:</strong> {request.status.charAt(0).toUpperCase() + request.status.slice(1)}</p>
    </Modal.Body>
    <Modal.Footer><Button 
        className="btn text-light btn-brown" 
        onClick={() => alert('Create Farm clicked!')}
      >
        Create Farm
      </Button>
      <Button 
        className="btn text-light btn-brown" 
        onClick={onHide}
      >
        Close
      </Button>
    </Modal.Footer>
  </Modal>
);

export default RequestModal;
