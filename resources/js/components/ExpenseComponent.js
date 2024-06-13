// Option1.js
import React from 'react';
import { useState } from 'react';

const ExpenseComponent = ({ crops }) => {
  const [selectedCrop, setSelectedCrop] = useState('');

  const [miscExpenses, setMiscExpenses] = useState([]);

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

export default ExpenseComponent;