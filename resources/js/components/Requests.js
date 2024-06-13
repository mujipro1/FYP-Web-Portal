// Option1.js
import React, { useState } from 'react';
import { Modal, Button } from 'react-bootstrap';
import RequestModal from './RequestModal';


function RequestComponent() {
    const [status, setStatus] = useState('pending');
    const [modalShow, setModalShow] = useState(false);
    const [selectedRequest, setSelectedRequest] = useState(null);

    const handleShowModal = (request) => {
        setSelectedRequest(request);
        setModalShow(true);
    };

    const handleCloseModal = () => {
        setModalShow(false);
        setSelectedRequest(null);
    };

    const [requests, setRequests] = useState([
        {
            name: "M M Fraz",
            date: "19-feb-1913",
            desc: "I am a poor farmer and I need help (in general)",
            status: "pending"
        },
        {
            name: "Ayesha Bibi",
            date: "05-mar-1920",
            desc: "My crops have failed due to drought, and I need assistance with irrigation equipment.",
            status: "pending"
        },
        {
            name: "Ali Hassan",
            date: "12-apr-1945",
            desc: "I require fertilizers and seeds for the upcoming planting season.",
            status: "completed"
        },
        {
            name: "Sara Khan",
            date: "30-jun-1955",
            desc: "Looking for pest control solutions to protect my crops from insects.",
            status: "pending"
        }
    ]);

    const handleChange = (e) => {
        const newStatus = e.target.value;
        setStatus(newStatus);
        onStatusChange(newStatus);
    };

    return (
        <>
            <div className="container m-2">
                <div className="text-center">
                    <h3>Requests</h3>
                </div>

                <div className="row px-3 mt-3">
                    {/* <div className="box-cont p-3"> */}
                    {/* <div className='text-center'><h5>Select Status</h5></div> */}
                    <div className="row">
                        <div className="col-md-6">
                            <div className="mt-4 labelcontainer">
                                <label className='w-50'>Status</label>
                                <select
                                    className='form-control ml-3'
                                    value={status}
                                    onChange={handleChange}
                                >
                                    <option value='pending'>Pending</option>
                                    <option value='completed'>Completed</option>
                                    <option value='all'>All</option>
                                </select>
                            </div>
                            {/* </div> */}
                        </div>
                    </div>
                </div>
                <div className="row p-3">
                    <div className="col-md-10 p-3 offset-md-1">
                        <div className="request-list">
                            {(status == "all" ? requests : requests.filter(e => e.status === status)).map((request, index) => (
                                <div key={index} style={{display: "flex", justifyContent: "space-between", alignItems: "center"}} className="box-cont p-3 request-cont m-3 d-flex justify-content-between align-items-center">
                                    <div>
                                        <p>{request.name}</p>
                                        <p>{request.date}</p>
                                        <p>{request.desc}</p>
                                    </div>
                                    <div>
                                        <p>{request.status.charAt(0).toUpperCase() + request.status.slice(1)}</p>
                                        <button
                                            type="button"
                                            className="btn text-light btn-brown"
                                            onClick={() => handleShowModal(request)}
                                        >
                                            Details
                                        </button>
                                    </div>
                                </div>
                            ))}
                            {selectedRequest && (
                                <RequestModal
                                    show={modalShow}
                                    onHide={handleCloseModal}
                                    request={selectedRequest}
                                />
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>

    );
};


export default RequestComponent;