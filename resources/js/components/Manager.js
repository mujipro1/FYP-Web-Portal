// SuperAdmin.js
import React from 'react';
import ReactDOM from 'react-dom';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import NavBar from './NavBar';
import Footer from './Footer';
import { useNavigate } from 'react-router-dom';
import { Container, Col, Row } from 'react-bootstrap'
import { Link } from 'react-router-dom';
import ManagerFarmDetails from './ManagerFarmDetails';
import ManagerWorkers from './ManagerWorkers';


function Manager() {
    return (
        <>
          <NavBar />
          <Container fluid>
          <Row>
          <Router>
            <div className="container">
                <div className="row">
                    <div className="col-md-2 mt-3 sidebarcol">
                        <ManagerSidebar />
                    </div>
                    <div className="col-md-10 ">
                         <Routes>
                            <Route path="/manager/" element={<ManagerHome/>} />
                            <Route path="/manager/farms" element={<ManagerFarms/>} />
                            <Route path="/manager/farms/:farmId" element={<ManagerFarmDetails />} />
                            <Route path="/manager/workers/:workerId" element={<ManagerWorkers />} />
                        </Routes>
                    </div>
                </div>
            </div>
            </Router> 
            </Row>
            </Container>
          <Footer />
        </>
    );
}



function ManagerSidebar() {
  return (
      <div className="sidebar">
          <ul>
              <li><Link to="/manager/home">Home</Link></li>
              <li><Link to="/manager/farms">Farms</Link></li>
              <li><Link to="/manager/workers">Workers</Link></li>
              <li><Link to="/manager/option2">Analytics</Link></li>
          </ul>
      </div>
  );
}

const ManagerHome = () => {
    return (
        <>
        <row>
            <Col>
                <div className="container">
                    <div className="row">
                        <div className="col-md-12 my-3">
                            <div className="text-center">
                                <h2 className='mx-4'>Welcome Hassan!</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </Col>
        </row>
        </>
    );
};


const ManagerFarms = () => {
  const farms = [
    {
      name: 'Farm 1',
      image: '/images/farm1.jpg',
      city: 'Lahore',
      crops: ['Wheat', 'Soybean', 'Cotton']
    },
    {
      name: 'Farm 2',
      image: '/images/farm2.avif',
      city : 'Karachi',
      crops: ['Wheat', 'Rice', 'Corn']
    },
    {
      name: 'Farm 3',
      image: '/images/farm3.jpg',
      city: 'Islamabad',
      crops: ['Wheat', 'Soybean', 'Cotton']
    },
    {
      name: 'Farm 4',
      image: '/images/farm1.jpg',
      city: 'Lahore',
      crops: ['Corn', 'Soybean', 'Cotton']
    },
  ];
      const navigate = useNavigate();
    
      const handleFarmClick = (farm) => {
        navigate(`/manager/farms/${farm.name}`, { state: { farm } });
      };
    
      return (
        <>
          <div className="container">
            <div className="row">
              <div className='text-center my-3'><h3>Farms</h3></div>
              <div className="row">
                {farms.map(farm => (
                  <div className="col-md-4 my-2" key={farm.name} onClick={() => handleFarmClick(farm)}>
                    <div className="selected-farm">
                      <img src={farm.image} className="selected-farm-image" />
                      <h5 className='mt-3 mx-2'>{farm.name}</h5>
                      <div className='mx-2 light fsmall'>{farm.city}</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </>
      );
    };




if (document.getElementById('Manager')) {
    ReactDOM.render(<Manager />, document.getElementById('Manager'));
}
