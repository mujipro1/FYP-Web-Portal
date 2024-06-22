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
            name: "Hassan Ali",
            date: "19-feb-1913",
            desc: "Make my Farm, I need assistance with irrigation equipment.",
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
            <div className="container ">
                <div className="text-center py-4">
                    <h3>Requests</h3>
                </div>

                <div className="row px-3 mt-3">
                    <div className="col-md-5 offset-md-1 px-3">
                        <div className="mx-4 labelcontainer">
                            <label className='w-25'>Status</label>
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
                    </div>


                    <div className="col-md-5 px-3">
                        <div className="mx-4 labelcontainer">
                            <label className='w-25'>Year</label>
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
                    </div>


                </div>
                <div className="row p-3">
                    <div className="col-md-10 p-3 offset-md-1">
                        <div className="request-list ">
                            {(status == "all" ? requests : requests.filter(e => e.status === status)).map((request, index) => (
                                <div key={index}  className="box-cont row my-4">
                                    <div className="col-md-9">
                                        <h5 className='px-3 pt-3'>{request.name}</h5>
                                        <p className=' px-3 light'>{request.date}</p>
                                        <p className='px-3'>{request.desc}</p>
                                    </div>
                                    <div className='col-md-3 py-4 text-center'>
                                        <div className="d-flex justify-content-center">

                                    {request.status === 'pending' ? (
                                        <svg className='mb-3 mx-2' xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M12,24C5.383,24,0,18.617,0,12S5.383,0,12,0s12,5.383,12,12-5.383,12-12,12Zm0-22C6.486,2,2,6.486,2,12s4.486,10,10,10,10-4.486,10-10S17.514,2,12,2Zm5,10c0-.553-.447-1-1-1h-3V6c0-.553-.448-1-1-1s-1,.447-1,1v6c0,.553,.448,1,1,1h4c.553,0,1-.447,1-1Z"/></svg>
                                    ) : (
                                        <p className='mx-2' style={{color:"brown"}}>✔</p>
                                    )}
                                    
                                        <p>{request.status.charAt(0).toUpperCase() + request.status.slice(1)}</p>
                                    </div>
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