import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import NavBar  from './NavBar';
import Footer  from './Footer';

function Home() {
  return (
    <>
    <NavBar />
    
    <Container className="d-flex section background-image" fluid>
      <Row className="overlay-text w-100 text-center">
        <h1><b>Welcome!</b></h1>
        <p>Cultivating Profits! A smart Farm Management Platform</p>
        <Col>
          <button className="btn-brown">Get Started</button>
        </Col>
      </Row>
    </Container>

    <div id='filler'></div>

    <Container className="back-grey my-5 px-5 section">
          <h3 className='services'>Our Services</h3>
        <Row className='h-100 p-3'>
          <ServiceCard title='AI Driven Chatbot' description='With our AI chatbot, you can get real-time weather updates for your farm location.' />
          <ServiceCard title='Farm Management Portal' description='Manage your resources, expenses and all farming data in one place.' />
          <ServiceCard title='Recommender System' description='Get personalized recommendations on what to plant, when to plant and how to plant.' />
        </Row>
    </Container>

    <div id='filler'></div>

    <Container fluid className='text-center my-3 gradient d-flex justify-content-center text-light'>
      <h1>Empowering Agriculture and Farmers</h1>
    </Container>


    <Container fluid className="px-5 my-5 grey-gradient">
      <Row className='p-4 text-center'>
        <Stats stats='2k+' author='Farms' />
        <Stats stats='10k+' author='Users' />
        <Stats stats='100+' author='Crops' />
      </Row>
    </Container>

    <div id='filler'></div>
    
    <Signup/>

    <div id='filler'></div>

    <Footer />
    </>    
);
}


function ServiceCard(props) {
  return (
    <Col className='text-center service-card'>
      <svg className='text-center'>
        <path d='M 10 10 L 100 0 L 90 90 L 0 100 Z' />
      </svg>
      <h4 className='px-5 h-20 p-2 text-center gradient-fill'><b>{props.title}</b></h4>
      <p className='px-4'>{props.description}</p>
    </Col>
  );
}

function Stats(props){
  return (
      <Col className='p-5'>
        <h1 className='heading gradient-fill'>{props.stats}</h1>
        <h3 >{props.author}</h3>
      </Col>
  );
}


function Signup(){
  return (
    <Container className='section signup-cont'>
      <Row>
        <Col id='signup-image'>
        <image src='https://via.placeholder.com/150' />
        </Col>

        <Col className='p-4'>

        <div className="text-center">
          <h4 className='my-3 text-center'>Login or Signup</h4>
          <div className='mt-5'>Login with Email</div>
          <div>or Create an account</div>
        </div>

          <form className='p-5'>
            <div className="form-group mx-5">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" className="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" />
            </div>

            <div className="form-group mx-5 mt-3  ">
              <label for="exampleInputPassword1">Password</label>
              <input type="password" className="form-control" id="exampleInputPassword1" />
            </div>

            <div className="text-center">
             <button type="submit" className="btn btn-dark w-75 m-5">Submit</button>
            </div>

            <div className=' text-center'>
              Don't have an account? <a className='text-dark' href='#'>Sign Up</a>
            </div>
          </form>
        </Col>
      </Row>
    </Container>
  )
}


export default Home;