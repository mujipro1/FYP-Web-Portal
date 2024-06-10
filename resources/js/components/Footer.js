import { Container, Row, Col } from 'react-bootstrap';


function Footer() {
    return (
      
      <div bgColor='light' className='text-center text-lg-start text-muted'>
        <section className='d-flex justify-content-center justify-content-lg-between p-4 border-bottom'>
          
        </section>
  
        <section className=''>
          <Container className='text-center text-md-start mt-5'>
            <Row className='mt-3'>
              <Col md="3" lg="4" xl="3" className='mx-auto mb-4'>
                <h6 className='text-uppercase fw-bold mb-4'>
                 
                  Company name
                </h6>
                <p>
                  Here you can use rows and columns to organize your footer content. Lorem ipsum dolor sit amet,
                  consectetur adipisicing elit.
                </p>
              </Col>
  
              <Col md="2" lg="2" xl="2" className='mx-auto mb-4'>
                <h6 className='text-uppercase fw-bold mb-4'>Products</h6>
                <p>
                  <a href='#!' className='text-reset'>
                    Angular
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    React
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    Vue
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    Laravel
                  </a>
                </p>
              </Col>
  
              <Col md="3" lg="2" xl="2" className='mx-auto mb-4'>
                <h6 className='text-uppercase fw-bold mb-4'>Useful links</h6>
                <p>
                  <a href='#!' className='text-reset'>
                    Pricing
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    Settings
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    Orders
                  </a>
                </p>
                <p>
                  <a href='#!' className='text-reset'>
                    Help
                  </a>
                </p>
              </Col>
  
              <Col md="4" lg="3" xl="3" className='mx-auto mb-md-0 mb-4'>
                <h6 className='text-uppercase fw-bold mb-4'>Contact</h6>
                <p>
                  <i className='fas fa-home me-3'></i>
                  New York, NY 10012, US
                </p>
                <p>
                  <i className='fas fa-home me-3'></i>
                  info@example.com
                </p>
                <p>
                  <i className='fas fa-home me-3'></i>
                </p>
                <p>
                  <i className='fas fa-home me-3'></i>
                </p>
              </Col>
            </Row>
          </Container>
        </section>
  
        <div className='text-center p-4' style={{ backgroundColor: 'rgba(0, 0, 0, 0.05)' }}>
          © 2021 Copyright:
          <a className='text-reset fw-bold' href='https://mdbootstrap.com/'>
            MDBootstrap.com
          </a>
        </div>
      </div>

    );
  }

export default Footer;