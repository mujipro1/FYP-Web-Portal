// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import AddExpenses from './addExpenses';

function Expensefarmer() {

    return (
        <>
            <NavBar />

            <div className="container my-5">
                <div className="row">
                    <div className="col-md-9">
                        <div className="py-3 px-2">
                            <div className="text-start m-2">
                                <h3>Welcome Hassan!</h3>
                                <p className='light'>Add expenses for your farms here</p>
                            </div>
                        </div>
                        <div className="mt-3 box-cont">
                            <h5 className='mx-2'>Your Farms</h5>
                            <div className="container">
                                <div className="row">
                                    <div className="col-md-4 p-2">
                                        <div className="box-cont your-farm">
                                            <img src='images/crops/garlic.jpg' className="farm-image" />
                                            <h5 className='mt-3'>Sargodha Farm</h5>
                                            <p className='light'>Add expenses for your garlic farm here</p>
                                        </div>
                                    </div>
                                    <div className="col-md-4 p-2">
                                        <div className="box-cont your-farm">
                                        <img src='images/crops/beetroot.jpg' className="farm-image" />
                                            <h5 className='mt-3'>Islamabad Farm</h5>
                                            <p className='light'>Add expenses for your garlic farm here</p>
                                        </div>
                                    </div>
                                    <div className="col-md-4 p-2">
                                        <div className="box-cont your-farm">
                                        <img src='images/crops/goldwheat.jpg' className="farm-image" />
                                            <h5 className='mt-3'>City Farm</h5>
                                            <p className='light'>Add expenses for your garlic farm here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div className="col-md-3">
                        <div className="box-cont mb-3">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quos.
                            
                        </div>
                    </div>
                </div>
            </div>

            <Footer/>
        </>
    );
}




export default expensefarmer;

if (document.getElementById('expensefarmer')) {
    ReactDOM.render(<AddExpenses />, document.getElementById('expensefarmer'));
}
