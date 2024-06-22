import React, { useState, useEffect } from 'react';
import RequestModal from './RequestModal'; // Adjust the import path as needed

const predefinedExpenses = ['Labour', 'Machine', 'Fertilizer', 'Seed', 'Fuel', 'Electricity', 'Pesticides', 'Travels', 'Miscellaneous'];

const ExpenseDetails = ({ initialValues, onSubmit, prevStage }) => {
  const [data, setData] = useState({
    ...initialValues,
    cropExpenses: {}
  });
  const [newExpense, setNewExpense] = useState({});
  const [showInput, setShowInput] = useState({});
  const [showModal, setShowModal] = useState(false);
  const [expenseToDelete, setExpenseToDelete] = useState({ crop: '', index: -1, name: '' });

  useEffect(() => {
    const initialCropExpenses = {};
    initialValues.crops.forEach(crop => {
      initialCropExpenses[crop.name] = [...predefinedExpenses];
    });
    setData(prevData => ({
      ...prevData,
      cropExpenses: initialCropExpenses
    }));
  }, [initialValues.crops]);

  const handleNewExpenseChange = (e, crop) => {
    setNewExpense({
      ...newExpense,
      [crop]: e.target.value
    });
  };

  const isDuplicateExpense = (expense, crop) => {
    return data.cropExpenses[crop].some(existingExpense => 
      existingExpense.toLowerCase() === expense.toLowerCase()
    );
  };

  const capitalizeFirstLetter = (string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
  };

  const isEntirelyNumber = (string) => {
    return /^\d+$/.test(string);
  };

  const handleAddExpense = (crop) => {
    let expense = newExpense[crop];
    if (expense && expense.trim() !== '') {
      expense = capitalizeFirstLetter(expense.trim());
      if (isEntirelyNumber(expense)) {
        alert("Expense cannot be entirely a number.");
        return;
      }
      if (isDuplicateExpense(expense, crop)) {
        alert("Expense already exists.");
        return;
      }
      setData(prevData => {
        const updatedExpenses = { ...prevData.cropExpenses };
        updatedExpenses[crop] = [...updatedExpenses[crop], expense];
        return {
          ...prevData,
          cropExpenses: updatedExpenses
        };
      });
      setNewExpense({
        ...newExpense,
        [crop]: ''
      });
      setShowInput({
        ...showInput,
        [crop]: false
      });
    }
  };

  const handleDeleteEntry = () => {
    const { crop, index } = expenseToDelete;
    const updatedExpenses = data.cropExpenses[crop].filter((_, idx) => idx !== index);
    setData(prevData => ({
      ...prevData,
      cropExpenses: {
        ...prevData.cropExpenses,
        [crop]: updatedExpenses
      }
    }));
    setShowModal(false);
  };

  const toggleInput = (crop) => {
    setShowInput(prevState => ({
      ...prevState,
      [crop]: !prevState[crop]
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit(data);
  };

  const confirmDelete = (crop, index) => {
    setExpenseToDelete({ crop, index, name: data.cropExpenses[crop][index] });
    setShowModal(true);
  };

  return (
    <>
      <form onSubmit={handleSubmit}>
        <div className="row px-3 mt-3">
          <div className=" p-3">
            <div className='text-center'><h5>Expenses Details</h5></div>

            <div className="my-4 px-5">
              <div className="progress">
                <div className="progress-bar progress-bar-striped active" role="progressbar"
                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style={{width:"100%"}}>
                  100%
                </div>
              </div>
            </div>

            <div className="row mt-3">
              {initialValues.crops.map((crop, index) => (
                <div key={index} className="col-md-6 p-3 mb-3">
                  <div className="box-cont p-3">
                    <div className='text-center '><h5>Expenses for {crop.name}</h5></div>
                    <div className="display-block">

                    {showInput[crop.name] ? (
                      <div className="my-3 mx-2 d-flex justify-content-center">
                        <input
                          type="text"
                          className="form-control"
                          value={newExpense[crop.name] || ''}
                          onChange={(e) => handleNewExpenseChange(e, crop.name)}
                          placeholder="Add new expense"
                          />
                        <button
                          type="button"
                          className="plusBtn"
                          onClick={() => handleAddExpense(crop.name)}
                        >
                          ✔
                        </button>
                      </div>
                    ) : (
                      <button
                        type="button"
                        className="plusBtn my-3"
                        onClick={() => toggleInput(crop.name)}
                      >
                        +
                      </button>
                    )}
                  </div>

                    {data.cropExpenses[crop.name] && data.cropExpenses[crop.name].map((expense, idx) => (
                      <div key={idx} className='addedcrops px-3 max-width'>
                        <span className='wordbreak'>{expense}</span>
                        <button
                          type='button'
                          className='btn cross'
                          onClick={() => confirmDelete(crop.name, idx)}>&times;
                        </button>
                      </div>
                    ))}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
        <div className="d-flex my-3 justify-content-center align-items-center">
          <button className='mx-2 btn btn-brown text-light' type="button" onClick={prevStage}>Back</button>
          <button className='mx-2 btn btn-brown text-light' type="submit">Submit</button>
        </div>
      </form>

      <RequestModal
        show={showModal}
        onHide={() => setShowModal(false)}
        onConfirm={handleDeleteEntry}
        confirmMessage="Are you sure you want to delete this expense"
        modalType="delete"
        cropName={expenseToDelete.crop}
        expenseName={expenseToDelete.name}
      />
    </>
  );
};

export default ExpenseDetails;