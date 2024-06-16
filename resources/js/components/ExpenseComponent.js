import React, { useState, useEffect } from 'react';

const predefinedExpenses = ['Labour','Machine','Fertilizer','Seed','Fuel',
  'Electricity','Pesticides','Travels','Miscellaneous'];

const ExpenseDetails = ({ initialValues, onSubmit, prevStage }) => {
    const [data, setData] = useState({
        ...initialValues,
        cropExpenses: {}
    });
    const [selectedCrop, setSelectedCrop] = useState('');
    const [selectedExpense, setSelectedExpense] = useState('');

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

    const handleCropChange = (e) => {
        setSelectedCrop(e.target.value);
    };

    const handleSelectExpense = (e) => {
        const expense = e.target.value;
        if (expense.trim() !== '') {
            const updatedExpenses = { ...data.cropExpenses };

            if (selectedCrop === 'All crops') {
                initialValues.crops.forEach(crop => {
                    if (!updatedExpenses[crop.name].includes(expense)) {
                        updatedExpenses[crop.name] = [...updatedExpenses[crop.name], expense];
                    }
                });
            } else if (selectedCrop !== '') {
                if (!updatedExpenses[selectedCrop].includes(expense)) {
                    updatedExpenses[selectedCrop] = [...updatedExpenses[selectedCrop], expense];
                }
            }

            setData(prevData => ({
                ...prevData,
                cropExpenses: updatedExpenses
            }));
        }
    };

    const handleDeleteEntry = (indexToRemove, crop) => {
        const updatedExpenses = data.cropExpenses[crop].filter((_, index) => index !== indexToRemove);
        setData(prevData => ({
            ...prevData,
            cropExpenses: {
                ...prevData.cropExpenses,
                [crop]: updatedExpenses
            }
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        onSubmit(data);
    };

    return (
        <form onSubmit={handleSubmit}>
            <div className="row px-3 mt-3">
                <div className=" p-3">
                    <div className='text-center'><h5>Add Expenses</h5></div>
                    <div className="row">
                        <div className="col-md-6">
                            <div className="mt-4 labelcontainer">
                                <label className='w-50'>Select Crop</label>
                                <select className='form-control ml-3' value={selectedCrop} onChange={handleCropChange}>
                                    <option value=''>Select a crop</option>
                                    <option value='All crops'>All crops</option>
                                    {initialValues.crops.map((crop, index) => (
                                        <option key={index} value={crop.name}>{crop.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div className="mt-4 labelcontainer">
                                <label className='w-50 ml-2'>Expenses</label>
                                <select className='form-control' value={selectedExpense} onChange={handleSelectExpense}>
                                    <option value=''>Select an expense</option>
                                    {predefinedExpenses.map((expense, index) => (
                                        <option key={index} value={expense}>{expense}</option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div className="row mt-3">
                        {initialValues.crops.map((crop, index) => (
                            <div key={index} className="col-md-6 p-3 mb-3">
                              <div className="box-cont p-3">

                                <div className='text-center '><h5>Expenses for {crop.name}</h5></div>
                                {data.cropExpenses[crop.name] && data.cropExpenses[crop.name].map((expense, idx) => (
                                    <div key={idx} className='addedcrops px-3'>
                                        <span>{expense}</span>
                                        <button
                                            type='button'
                                            className='btn cross'
                                            onClick={() => handleDeleteEntry(idx, crop.name)}>&times;
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
    );
};

export default ExpenseDetails;
