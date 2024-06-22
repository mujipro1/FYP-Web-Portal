import React from 'react';
import { useNavigate } from 'react-router-dom';
import { Modal } from 'react-bootstrap';

const RequestModal = ({ show, onHide, request, onConfirm, confirmMessage, modalType, cropName, expenseName }) => {
  const navigate = useNavigate();

  const handleCreateFarm = () => {
    navigate('/superadmin/createfarm', { state: { request } });
  };

  const handleConfirm = () => {
    if (modalType === 'delete') {
      onConfirm();
    } else {
      handleCreateFarm();
    }
  };

  return (
    <Modal show={show} onHide={onHide}>
      <Modal.Header closeButton>
        <Modal.Title>
          {modalType === 'delete' ? 'Confirm Delete' : 'Request Details'}
        </Modal.Title>
      </Modal.Header>
      <Modal.Body>
        {modalType === 'delete' ? (
          <p>{confirmMessage} for <strong>{expenseName}</strong> in <strong>{cropName}</strong>?</p>
        ) : (
          <>
            <p><strong>Name:</strong> {request.name}</p>
            <p><strong>Date:</strong> {request.date}</p>
            <p><strong>Description:</strong> {request.desc}</p>
            <p><strong>Status:</strong> {request.status.charAt(0).toUpperCase() + request.status.slice(1)}</p>
          </>
        )}
      </Modal.Body>
      <Modal.Footer>
        <button
          className="text-light btn-brown"
          onClick={handleConfirm}
        >
          {modalType === 'delete' ? 'Delete' : 'Create Farm'}
        </button>
        <button
          className="text-light btn-brown"
          onClick={onHide}
        >
          Close
        </button>
      </Modal.Footer>
    </Modal>
  );
};

export default RequestModal;
