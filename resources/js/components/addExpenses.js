// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import { useState } from 'react';

function AddExpenses() {

    const [year, setYear] = useState(2021);
    const [crop, setCrop] = useState('Garlic');
    const crops = ['Wheat', 'Rice', 'Garlic', 'Beetroot']
    const expenses = ['Seed', 'Fertilizer', 'Pesticide', 'Labour', 'Machinery', 'Other']
    const expenseStorer = useState({})

    return (
        <>
            <NavBar />
                <div className="container">
                    <div className="row">
                        <div className="col-md-9">
                            <div className="px-3 my-4">
                                <h4 className='mt-4'>Islamabad Farm</h4>
                                <div className=' light'> Islamabad</div>
                                <div className=' mb-3 light'> Add expenses for your garlic farm here</div>
                            </div>

                            <div className="box-cont p-3">
                                <div className='mx-2 light'>Crops</div>
                                
                            </div>

                            <div className=" mt-3 p-3">
                                <div className='m-2 light'>Add Expenses</div>
                                <div className="container">
                                    <div className="row">
                                    <div className="col-md-6">
                                        <div className="labelcontainer">
                                            <label className=''>Select Year</label>
                                            <select className=' form-select'>
                                                <option value=''>Select Year</option>
                                                    {Array.from({length: 10}, (_, i) => i + 2010).map(year => (
                                                        <option value={year} className=''>{year}</option>
                                                    ))}                                  
                                            </select>
                                        </div>
                                    </div>                                

                                    <div className="col-md-6">
                                        <div className="labelcontainer">
                                            <label className='mx-2'>Select Crop</label>
                                            <select className='mx-2 form-select' onChange={(e) => setCrop(e.target.value)}>
                                                {crops.map(crop => (
                                                    <option value={crop}>{crop}</option>
                                                ))}
                                            </select>    
                                        </div>
                                    </div>

                                    <section className='d-flex justify-content-center justify-content-lg-between my-5 border-bottom'></section>

                                        {
                                            expenses.map(expense => (
                                                    // labels and entries
                                                <div className="col-md-6">
                                                    <div className="labelcontainer">
                                                        <label className=''>{expense}</label>
                                                        <input type='number' className='form-control'/>
                                                    </div>
                                                </div>
                                            ))
                                        }

                                        <div className="text-center my-3">
                                            <button className='btn btn-brown text-light my-3'>Add Expenses</button>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div className="col-md-3"></div>
                    </div>
                </div>

            <Footer/>
        </>
    );
}




export default AddExpenses;

