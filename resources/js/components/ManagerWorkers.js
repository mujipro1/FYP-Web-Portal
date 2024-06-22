import React from "react";
import ReactDOM from "react-dom";
import { useLocation } from "react-router-dom";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

const ManagerWorkers = () => {
    const location = useLocation();
    const { worker } = location.state;
    
    return (

        <>
        <div className="container">
            <div className="row mt-4">
                <div className="col-md-12 px-4">
                    <div className="d-flex justify-content-start">
                        <img src='/images/profile.jpg' className="profile-image" />
                        <div className="text-start mx-4 my-3">
                        <h4 className="pt-1">{worker.name}</h4>
                        <div style={{transform:'translateY(-10px)'}} className="light">Worker</div>
                        </div>
                    </div>
                </div>

                <div className="col-md-6 my-3 py-4">
                    <div className="box-cont">
                        <h5 className='light'>Details</h5>
                        <div className="mt-4 d-flex text-start justify-content-center">
                            <div className='light w-50'>Name</div>
                            <div className="w-50">{worker.name}</div>
                        </div>
                        <div className="d-flex text-start justify-content-center">
                            <div className='light w-50'>Type</div>
                            <div className="w-50">{worker.type}</div>
                        </div>
                    </div>
                </div>
                <div className="col-md-6 my-3  py-4">
                    <div className="box-cont">
                        <h5 className='light'>Permissions</h5>
                    </div>
                </div>
   
            </div>
        </div>
        </>
    );
}


export default ManagerWorkers;