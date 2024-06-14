// Option1.js
import React from "react";
import { Container, Row, Col } from "react-bootstrap";
import { useState,useEffect  } from "react";
import { Link } from "react-router-dom";
import crops from './cropdata'; // Import an array of crop data with names and image paths



const CreateFarm = () => {
    const [crops, setCrops] = useState(["Maize", "Wheat", "Rice"]);
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
            return <InitialDetails onSubmit={handleInitialDetailsSubmit} />;
        case 2:
            return <CropDetails initialValues={initialDetails} onSubmit={handleCropDetailsSubmit} prevStage={prevStage} />;
        // case 3:
        //     return <ExpenseDetails initialValues={cropDetails} onSubmit={handleExpenseDetailsSubmit} prevStage={prevStage} />;
        // default:
        //     return <div>Error: Invalid stage</div>;
    }
};



const InitialDetails = ({ onSubmit }) => {

    const [data, setData] = useState({
        farmName: '',
        location: '',
        area: '',
        address: '',

        // other initial details fields
    });

    const handleChange = (e) => {
        setData({
            ...data,
            [e.target.name]: e.target.value
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onSubmit(data);
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
                                                    Ali Hassan
                                                </label>
                                            </div>
                                            <div className="d-flex justify-content-between light px-4">
                                                <label className="">Date</label>
                                                <label className="">
                                                    20-02-24
                                                </label>
                                            </div>
                                            <div className="d-flex justify-content-between light px-4">
                                                <label className="">Date</label>
                                                <label className="">
                                                    20-02-24
                                                </label>
                                            </div>
                                            <div className="d-flex justify-content-between light px-4">
                                                <label className="">Date</label>
                                                <label className="">
                                                    20-02-24
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
                                                value={data.farmName}
                                            />
                                        </div>

                                        <div className="labelcontainer">
                                            <label className="">City</label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                value={data.location}
                                            />
                                        </div>

                                        <div className="labelcontainer">
                                            <label className="">Area</label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                value={data.area}
                                            />
                                        </div>

                                        <div className="labelcontainer mb-3">
                                            <label className="">Address</label>
                                            <input
                                                type="text"
                                                className="form-control ml-3"
                                                value={data.address}
                                            />
                                        </div>

                                        <div className="text-center ">
                                            <button className="btn text-light btn-brown" type="submit">Next</button>
                                        </div>

                                    </div>
                                </div>

                                <div className="col-md-6 p-3  mb-3">
                                        <div className="box-cont ">
                                        <div className="text-center">
                                            Request
                                        </div>

                                        <div className="overflow-y mt-4 light pt-3 px-4">
                                                Lorem ipsum dolor sit amet,consectetur adipiscing elit.
                                                Sed do eiusmod tempor incididunt ut labore et
                                                dolore magna aliqua. Sed do eiusmod tempor incididunt ut
                                                labore et dolore magna aliqua. Sed do eiusmod
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                                tempor incididunt ut laboreet dolore magna aliqua. Sed
                                        </div>
                                    </div>
                                </div>


                            </div>

                            {/* <EntryComponent
                                title="Crop"
                                initialEntries={crops}
                                onEntriesChange={setCrops}
                            />

                            <EntryComponent
                                title="Misc. Expenses"
                                initialEntries={miscExpenses}
                                onEntriesChange={setMiscExpenses}
                            /> */}

                        </div>
                    </form>
                </Row>
            </Container>
        </div>
    );
}

const CropDestails = ({ title, initialEntries, onEntriesChange }) => {
    const [entryName, setEntryName] = useState("");
    const [entries, setEntries] = useState(initialEntries);

    const handleInputChange = (e) => {
        setEntryName(e.target.value);
    };

    const handleAddEntry = (e) => {
        e.preventDefault();
        if (entryName.trim() !== "") {
            const updatedEntries = [...entries, entryName];
            setEntries(updatedEntries);
            setEntryName("");
            onEntriesChange(updatedEntries); // Save selected crops to shared state
        }
    };

    const handleDeleteEntry = (indexToRemove) => {
        const updatedEntries = entries.filter(
            (_, index) => index !== indexToRemove
        );
        setEntries(updatedEntries);
        onEntriesChange(updatedEntries); // Update selected crops in shared state
    };

    return (
        <div className="row px-3 mt-3">
            <div className="box-cont p-3">
                <div className="text-center">
                    <h5>Add {title}</h5>
                </div>
                <div className="row">
                    <div className="col-md-6">
                        <div className="mt-4 labelcontainer">
                            <label className="w-50">{title} Name</label>
                            <input
                                type="text"
                                className="form-control ml-3"
                                value={entryName}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div className="mt-3 text-end mx-1">
                            <button
                                type="button"
                                className="btn text-light btn-brown"
                                onClick={handleAddEntry}
                            >
                                Add
                            </button>
                        </div>
                    </div>
                    <div className="col-md-6 p-3">
                        <div className="box-cont">
                            <div className="text-center">
                                <h5>Added {title}s</h5>
                            </div>
                            {entries.map((entry, index) => (
                                <div key={index} className="addedcrops  px-3">
                                    <span>{entry}</span>
                                    <button
                                        type="button"
                                        className="btn cross"
                                        onClick={() => handleDeleteEntry(index)}
                                    >
                                        &times;
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

const CropDetails = ({ initialValues, onSubmit, prevStage }) => {
    const [data, setData] = useState({
        ...initialValues,
        crops: []
    });

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
        onSubmit(data);
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="container">
                <div className="row">
                    <div className="col-md-10 p-4 offset-md-1">
                        <div className="my-3 text-center">
                            <h3>Select Crops</h3>
                        </div>
                        <CropDropdown onSelectCrop={handleSelectCrop} />
                        <div className="box-cont selected-crops my-4 p-3">
                            <p className='light'>Selected Crops</p>
                                {data.crops.map((crop, index) => (
                                        <div key={index} className="selected-crop">
                                            <img src={crop.image} alt={crop.name} className="selected-crop-image" />
                                            <div className="d-flex justify-content-between"> 
                                            <span className="mx-2">{crop.name}</span>
                                            <button className="cross btn" type="button" onClick={() => setData(prevData => ({ ...prevData, crops: prevData.crops.filter(c => c.name !== crop.name) }))}>
                                                &times;
                                            </button>
                                            </div>
                                        </div>
                                ))}
                        </div>
                        {/* other input fields */}

                        <div className="d-flex my-3 justify-content-center align-items-center">
                            <button className='mx-2 btn btn-brown text-light' type="button" onClick={prevStage}>Back</button>
                            <button className='mx-2 btn btn-brown text-light' type="submit">Next</button>
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
    // outpit the filtered crops

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

    return (

       

        <div className="dropdown">
            <input
            className="form-control "
                type="text"
                value={searchTerm}
                onChange={handleChange}
                placeholder="Type to search crops..."
            />
            {searchTerm.length > 0 &&

                <div className="dropdown-box">
                    {filteredCrops.map(crop => (
                        <div key={crop.name} className="dd-item" onClick={() => handleSelectCrop(crop)}>
                            <img src={crop.image} alt={crop.name} className="crop-image" />
                            <span className="mx-4">{crop.name}</span>
                        </div>
                    ))}
                </div>
                }
        </div>
    );
};




export default CreateFarm;
