// Option1.js
import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import { useState } from 'react';
import FarmMap from './Map';

function CreateFarm() {

    const [crops, setCrops] = useState(['Maize', 'Wheat', 'Rice']);
    const [miscExpenses, setMiscExpenses] = useState([]);

    return (
        <div>
        <Container className='p-3 mt-3 section '>
            <Row>
                <Col className='text-center my-3'>
                    <h4>Create Farm</h4>
                </Col>

                <form>
                    <div className="container px-3">
                        <div className="row px-3">
                            <div className="col-md-7">
                                <div className="labelcontainer">
                                    <label className=''>Farm Name</label>
                                    <input type='text' className='form-control ml-3'/>
                                </div>

                                <div className="labelcontainer">
                                    <label className=''>City</label>
                                    <input type='text' className='form-control ml-3'/>
                                </div>
                                
                                <div className="labelcontainer">
                                    <label className=''>Area</label>
                                    <input type='text' className='form-control ml-3'/>
                                </div>

                                <div className="labelcontainer mb-3">
                                    <label className=''>Address</label>
                                    <input type='text' className='form-control ml-3'/>
                                </div>
                            </div>

                        
                            <div className="col-md-5 box-cont mb-3">
                                <div class='text-center'>Request Status</div>

                                <div className='d-flex justify-content-between light pt-3 px-4'>
                                    <label className=''>Request ID</label>
                                    <label className=''>KS3240234</label>
                                </div>
                                <div className='d-flex justify-content-between light px-4'>
                                    <label className=''>Farmer </label>
                                    <label className=''>Ali Hassan</label>
                                </div>
                                <div className='d-flex justify-content-between light px-4'>
                                    <label className=''>Date</label>
                                    <label className=''>20-02-24</label>
                                </div>
                            </div>
                        </div>


                        <EntryComponent 
                            title="Crop" 
                            initialEntries={crops} 
                            onEntriesChange={setCrops} 
                        />
                        <ExpenseComponent crops={crops} />

                        <EntryComponent 
                            title="Misc. Expenses" 
                            initialEntries={miscExpenses} 
                            onEntriesChange={setMiscExpenses} 
                        />

                        <div className="row px-3 mt-3">
                            <div className="box-cont p-3">
                            <FarmMap />
                            </div>
                        </div>

                    </div>
                </form>
            </Row>
        </Container>

        </div>
    );
}



const EntryComponent = ({ title, initialEntries, onEntriesChange }) => {
  const [entryName, setEntryName] = useState('');
  const [entries, setEntries] = useState(initialEntries);

  const handleInputChange = (e) => {
    setEntryName(e.target.value);
  };

  const handleAddEntry = (e) => {
    e.preventDefault();
    if (entryName.trim() !== '') {
      const updatedEntries = [...entries, entryName];
      setEntries(updatedEntries);
      setEntryName('');
      onEntriesChange(updatedEntries); // Save selected crops to shared state
    }
  };

  const handleDeleteEntry = (indexToRemove) => {
    const updatedEntries = entries.filter((_, index) => index !== indexToRemove);
    setEntries(updatedEntries);
    onEntriesChange(updatedEntries); // Update selected crops in shared state
  };

  return (
    <div className="row px-3 mt-3">
      <div className="box-cont p-3">
        <div className='text-center'><h5>Add {title}</h5></div>
        <div className="row">
          <div className="col-md-6">
            <div className="mt-4 labelcontainer">
              <label className='w-50'>{title} Name</label>
              <input 
                type='text' 
                className='form-control ml-3' 
                value={entryName} 
                onChange={handleInputChange} 
              />
            </div>
            <div className="mt-3 text-end mx-1">
              <button 
                type='button' 
                className='btn text-light btn-brown' 
                onClick={handleAddEntry}
              >
                Add 
              </button>
            </div>
          </div>
          <div className="col-md-6 p-3">
            <div className="box-cont">
              <div className='text-center'><h5>Added {title}s</h5></div>
              {entries.map((entry, index) => (
                <div key={index} className='addedcrops  px-3'>
                  <span>{entry}</span>
                  <button 
                    type='button' 
                    className='btn cross' 
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



const ExpenseComponent = ({ crops }) => {
  const [selectedCrop, setSelectedCrop] = useState('');
  const [cropExpenses, setCropExpenses] = useState({});
  const [expense, setExpense] = useState('');

  const handleCropChange = (e) => {
    setSelectedCrop(e.target.value);
  };

  const handleExpenseChange = (e) => {
    setExpense(e.target.value);
  };

  const handleAddExpense = (e) => {
    e.preventDefault();
    if (expense.trim() !== '') {
      if (selectedCrop === 'All crops') {
        crops.forEach(crop => {
          const newExpenses = cropExpenses[crop] ? [...cropExpenses[crop], expense] : [expense];
          setCropExpenses(prevExpenses => ({
            ...prevExpenses,
            [crop]: newExpenses
          }));
        });
      } else if (selectedCrop !== '') {
        const newExpenses = cropExpenses[selectedCrop] ? [...cropExpenses[selectedCrop], expense] : [expense];
        setCropExpenses({
          ...cropExpenses,
          [selectedCrop]: newExpenses
        });
      }
      setExpense('');
    }
  };

  const handleDeleteEntry = (indexToRemove, crop) => {
    const updatedExpenses = cropExpenses[crop].filter((_, index) => index !== indexToRemove);
    setCropExpenses({
      ...cropExpenses,
      [crop]: updatedExpenses
    });
  }

  return (
    <div className="row px-3 mt-3">
      <div className="box-cont p-3">
        <div className='text-center'><h5>Add Expenses</h5></div>
        <div className="row">
          <div className="col-md-6">
            <div className="mt-4 labelcontainer">
              <label className='w-50'>Select Crop</label>
              <select className='form-control ml-3' value={selectedCrop} onChange={handleCropChange}>
                <option value=''>Select a crop</option>
                <option value='All crops'>All crops</option>
                {crops.map((crop, index) => (
                  <option key={index} value={crop}>{crop}</option>
                ))}
              </select>
            </div>
            <div className="mt-4 labelcontainer">
              <label className='w-50'>Add Expense</label>
              <input 
                type='text' 
                className='form-control ml-3' 
                value={expense} 
                onChange={handleExpenseChange} 
              />
            </div>
            <div className="mt-3 text-end mx-1">
              <button 
                type='button' 
                className='btn text-light btn-brown' 
                onClick={handleAddExpense}
              >
                Add Expense
              </button>
            </div>
          </div>
        </div>
        <div className="row mt-3">
          {crops.map((crop, index) => (
            <div key={index} className="col-md-3 m-3 box-cont p-3 mb-3">
              <div className='text-center '><h5>Expenses for {crop}</h5></div>
              {cropExpenses[crop] && cropExpenses[crop].map((expense, idx) => (
                <div key={idx} className='addedcrops  px-3'>
                  <span>{expense}</span>
                  <button 
                    type='button' 
                    className='btn cross' 
                    onClick={() => handleDeleteEntry(idx, crop)}
                  >
                    &times;
                  </button>
                </div>
              ))}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};


export default CreateFarm;