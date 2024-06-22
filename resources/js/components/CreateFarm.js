// Option1.js
import React, { useState, useEffect, useRef } from "react";
import { Container, Row, Col } from "react-bootstrap";
import { useLocation } from 'react-router-dom';
import crops from './cropdata'; // Import an array of crop data with names and image paths
import citydata from './citydata'; // Import an array of crop data with names and image paths
import ExpenseDetails from "./ExpenseComponent";

const CreateFarm = () => {
    const location = useLocation();
    const { request } = location.state || {};
    console.log(request);

    const [stage, setStage] = useState(1);
    const [initialDetails, setInitialDetails] = useState({});
    const [cropDetails, setCropDetails] = useState({});
    const [expenseDetails, setExpenseDetails] = useState({});

    const nextStage = () => setStage(prev => prev + 1);
    const prevStage = () => setStage(prev => prev - 1);

    const handleInitialDetailsSubmit = (data) => {
        setInitialDetails(data);
        nextStage();
    };

    const handleCropDetailsSubmit = (data) => {
        setCropDetails(data);
        nextStage();
    };

    const handleExpenseDetailsSubmit = (data) => {
        setExpenseDetails(data);
        // Here you can handle the final submission logic
        console.log('Initial Details:', initialDetails);
        console.log('Crop Details:', cropDetails);
        console.log('Expense Details:', expenseDetails);
    };

    switch(stage) {
        case 1:
            return <InitialDetails request={request} onSubmit={handleInitialDetailsSubmit} />;
        case 2:
            return <CropDetails initialValues={initialDetails} onSubmit={handleCropDetailsSubmit} prevStage={prevStage} />;
        case 3:
            return <ExpenseDetails initialValues={cropDetails} onSubmit={handleExpenseDetailsSubmit} prevStage={prevStage} />;
        default:
            return <div>Error: Invalid stage</div>;
    }
};

const InitialDetails = ({ request, onSubmit }) => {
    const [data, setData] = useState({
        farmName: '',
        location: '',
        area: '',
        address: '',
        farmDate: new Date().getFullYear(),
        sowingMonth: '',
        harvestMonth: '',
    });

    const handleChange = (e) => {
        setData({
            ...data,
            [e.target.name]: e.target.value
        });
    };

    const isValid = () => {
        return data.farmName && data.location && data.area && data.address && data.sowingMonth && data.harvestMonth;
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (isValid()) {
            onSubmit(data);
        } else {
            alert("Please fill in all fields.");
        }
    };

    return(
        <div>
            <Container className="p-3 mt-3 section ">
                <Row>
                    <Col className="text-center mb-1">
                        <h4>Create Farm</h4>
                    </Col>
                    <form onSubmit={handleSubmit}>
                        <div className="container px-3">
                            <div className="row px-3">
                                <div className="my-4 px-5">
                                    <div className="progress">
                                        <div className="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style={{width:"33%"}}>
                                            33%
                                        </div>
                                    </div>
                                </div>
                                <div className="col-md-6">
                                    <div className=" p-3 mb-3">
                                        <div className="box-cont">
                                            <div className="text-center">
                                                Request Status
                                            </div>
                                            <div className="d-flex justify-content-between light pt-3 px-4">
                                                <label className="">
                                                    Request ID
                                                </label>
                                                <label className="">
                                                    KS3240234
                                                </label>
                                            </div>
                                            <div className="d-flex justify-content-between light px-4">
                                                <label className="">
                                                    Farmer{" "}
                                                </label>
                                                <label className="">
                                                    {request.name}
                                                </label>
                                            </div>
                                            <div className="d-flex justify-content-between light px-4">
                                                <label className="">Date</label>
                                                <label className="">
                                                    {request.date}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="">
                                        <div className="labelcontainer">
                                            <label className="">
                                                Farm Name
                                            </label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                name="farmName"
                                                value={data.farmName}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="labelcontainer">
                                            <label className="">City</label>
                                            <select className="form-control ml-3" name="location" value={data.location} onChange={handleChange}>
                                                <option value="">Select City</option>
                                                {citydata.map((city, index) => (
                                                    <option key={index} value={city}>{city}</option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="labelcontainer">
                                            <label className="">Area</label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                name="area"
                                                value={data.area}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="labelcontainer mb-3">
                                            <label className="">Address</label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                name="address"
                                                value={data.address}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="text-center mb-2 light">Configure Date</div>
                                        <div className="labelcontainer">
                                            <label className="">Year</label>
                                            <select className="form-control ml-3" name="farmDate" value={data.farmDate} onChange={handleChange}>
                                                {Array.from({length: new Date().getFullYear() - 1970 + 1}, (_, i) => 1970 + i).map(year => (
                                                    <option key={year} value={year}>{year}</option>
                                                ))}
                                            </select>
                                        </div>
                                        <div className="labelcontainer">
                                            <label className="">Sowing Month</label>
                                            <input
                                                type="month"
                                                className="form-control ml-3"
                                                name="sowingMonth"
                                                value={data.sowingMonth}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="labelcontainer">
                                            <label className="">Harvest Month</label>
                                            <input
                                                type="month"
                                                className="form-control ml-3"
                                                name="harvestMonth"
                                                value={data.harvestMonth}
                                                onChange={handleChange}
                                            />
                                        </div>
                                        <div className="text-center mt-3 ">
                                            <button className="btn text-light btn-brown" type="submit">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <div className="col-md-6 p-3  mb-3">
                                    <div className="box-cont ">
                                        <div className="text-center">
                                            Request
                                        </div>
                                        <div className="overflow-y text-justify mt-4 light pt-3 px-4">
                                            {
                                                request ? (
                                                    <>
                                                    <p>{request.desc}</p>
                                                    </>
                                                ) : (
                                                    <div className="text-center">
                                                        No request data found
                                                    </div>
                                                )
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </Row>
            </Container>
        </div>
    );
};

const CropDetails = ({ initialValues, onSubmit, prevStage }) => {
    const [data, setData] = useState({
        ...initialValues,
        crops: []
    });

    const popularCrops = crops.slice(0, 4);

    const handleSelectCrop = (crop) => {
        // check if crop already exists
        if (data.crops.some(c => c.name === crop.name)) {
            return;
        }
        setData(prevData => ({
            ...prevData,
            crops: [...prevData.crops, crop]
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (data.crops.length > 0) {
            onSubmit(data);
        } else {
            alert("Please add at least one crop.");
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="container">
                <div className="row px-3">
                    <div className="my-3 text-center">
                        <h3>Select Crops</h3>
                    </div>
                    <div className="my-4 px-5">
                        <div className="progress">
                            <div className="progress-bar progress-bar-striped active" role="progressbar"
                                 aria-valuenow="66" aria-valuemin="0" aria-valuemax="100" style={{width:"66%"}}>
                                66%
                            </div>
                        </div>
                    </div>
                    <div className="col-md-9 p-4">
                        <CropDropdown onSelectCrop={handleSelectCrop} />
                        <div className="box-cont container selected-crops my-4 p-3">
                            <div className="row">
                                <p className='light'>Selected Crops</p>
                                {data.crops.map((crop, index) => (
                                    <div key={index} className="col-md-4 my-2">
                                        <div className="selected-crop">
                                            <img src={crop.image} alt={crop.name} className="selected-crop-image" />
                                            <div className="d-flex justify-content-between"> 
                                                <span className="mx-2">{crop.name}</span>
                                                <button className="cross btn" type="button" onClick={() => setData(prevData => ({ ...prevData, crops: prevData.crops.filter(c => c.name !== crop.name) }))}>
                                                    &times;
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        <div className="d-flex my-3 justify-content-center align-items-center">
                            <button className='mx-2 btn btn-brown text-light' type="button" onClick={prevStage}>Back</button>
                            <button className='mx-2 btn btn-brown text-light' type="submit">Next</button>
                        </div>
                    </div>
                    <div className="col-md-3  my-4 p-3">
                        <p className='light'>Popular Crops</p>
                        <div className="">
                            {popularCrops.map((crop, index) => (
                                <div key={index} className="popular-crop">
                                    <span className="mx-3">{crop.name}</span>
                                    <button className="btn cross" onClick={() => handleSelectCrop(crop)} type="button">+</button>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    );
};

const CropDropdown = ({ onSelectCrop }) => {
    const [searchTerm, setSearchTerm] = useState('');
    const [filteredCrops, setFilteredCrops] = useState(crops);
    const dropdownRef = useRef(null);

    const handleChange = (e) => {
        const term = e.target.value;
        setSearchTerm(term);
        if (term.length > 0) {
            setFilteredCrops(crops.filter(crop => crop.name.toLowerCase().includes(term.toLowerCase())));
        } else {
            setFilteredCrops(crops);
        }
    };

    const handleSelectCrop = (crop) => {
        onSelectCrop(crop);
        setSearchTerm('');
        setFilteredCrops(crops);
    };

    const handleClickOutside = (event) => {
        if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
            setSearchTerm('');
            setFilteredCrops(crops);
        }
    };

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    return (
        <div className="dropdown" ref={dropdownRef}>
            <input
                className="form-control"
                type="text"
                value={searchTerm}
                onChange={handleChange}
                placeholder="Type to search crops..."
            />
            {searchTerm.length > 0 && (
                <div className="dropdown-box">
                    {filteredCrops.map(crop => (
                        <div key={crop.name} className="dd-item" onClick={() => handleSelectCrop(crop)}>
                            <img src={crop.image} alt={crop.name} className="crop-image" />
                            <span className="mx-4">{crop.name}</span>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
};

export default CreateFarm;
