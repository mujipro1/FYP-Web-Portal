import { useLocation } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';

const ManagerFarmDetails = () => {
  const location = useLocation();
  const { farm } = location.state;
  const crops = farm.crops;
    const workers = [
        {
            id: 1,
            name: 'Ali Hassan',
            type: 'Expense Worker'
        },
        {
            id: 2,
            name: 'Abdullah Khan',
            type: 'Sales Worker'
        }
    ];

    const navigate = useNavigate();

    const handleWorkerClick = (worker) => {
        navigate(`/manager/workers/${worker.id}`, { state: { worker } });
    };

  return (
    <>
    <div className="container">
        <div className="row">
            <div className="text-center my-3">
                <h3>{farm.name}</h3>
            </div>
            <div className="col-md-7 p-3">
                <div className="box-conts px-4">
                    <h4 className='text-start mb-2 light'>Farm Details</h4>

                        <div className="row px-2">
                            <div className="col-md-6 text-start">
                                <label className='light' > Name</label>
                            </div>
                            <div className="col-md-6 text-start">
                                <label>{farm.name}</label>
                            </div>
                        </div>

                        <div className="row px-2">
                            <div className="col-md-6 text-start">
                                <label className='light' >City</label>
                            </div>
                            <div className="col-md-6 text-start">
                                <label>{farm.city}</label>
                            </div>
                        </div>

                        <div className="row px-2">
                            <div className="col-md-6 text-start">
                                <label className='light' >Area</label>
                            </div>
                            <div className="col-md-6 text-start">
                                <label>1200 sq. ft.</label>
                            </div>
                        </div>

                        <div className="row px-2">
                            <div className="col-md-6 text-start">
                                <label className='light' >Crops</label>
                            </div>
                            <div className="col-md-6 text-start">
                                <label>5</label>
                            </div>
                        </div>

                        <div className="row px-2">
                            <div className="col-md-6 text-start">
                                <label className='light' >Workers</label>
                            </div>
                            <div className="col-md-6 text-start">
                                <label>2</label>
                            </div>
                        </div>
                </div>
                <div className="box-cont mt-5">
                    <div className="row">
                        <div className="text-start">
                            <h4 className='light'>Crops</h4>
                        </div>

                        <p className='fsmall light'>Select crops to apply filters on expenses</p>
                        {
                            crops.map(crop => (
                                <div className="col-md-6 my-2">
                            <div className="selected-crop" style={{backgroundColor:"#f4f4f4"}}>
                                <img src={`/images/crops/${crop}.jpg`} className="selected-crop-image" />
                                <div className="d-flex justify-content-between"> 
                                    <span className="m-2">{crop}</span>
                                </div>
                            </div>
                        </div>
                            ))    
                    }
                    
                    </div>
                </div>
            </div>

            <div className="col-md-5 p-3">
                <div className="piccont">
                    <img src={farm.image} />
                </div>
                <div className="box-cont mt-5">
                    <div className="text-start">
                        <h4 className='light px-2'>Workers</h4>
                        
                        {
                            workers.map(worker => (
                                <div className="my-2 popular-crop w-100 d-flex justify-content-start align-items-center px-2 py-2"
                                onClick = {() => handleWorkerClick(worker)}>
                                    <img src={`/images/profile.jpg`} id='profile-image' className='mx-2'/>
                                    <div className='mx-3'>
                                    <div className="">{worker.name}</div>
                                    <div className=" light fsmall">{worker.type}</div>
                                    </div>
                                </div>
                            ))
                        }

                    </div>
                </div>
            </div>
        </div>

        <div className="row">
            <div className="col-md-12 p-3">
                <div className="box-cont">

                    <h4 className='light'>Expenses</h4>


                </div>
            </div>
        </div>

    </div>
    </>
  );
};

export default ManagerFarmDetails;
