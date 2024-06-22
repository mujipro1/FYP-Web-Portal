import { Button } from 'react-bootstrap';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';


function NavBar() {
  return (
    <Navbar expand="lg" className=" navbar pt-3">
      <Container>
        <Navbar.Brand href="/"><h4><b>Cultivating Profits</b></h4></Navbar.Brand>
        <Navbar.Toggle aria-controls="basic-navbar-nav" />
        <Navbar.Collapse id="basic-navbar-nav">
          <Nav className="ms-auto">
              <Nav.Link className="mx-2 navlink" href="/">Home</Nav.Link>
              <Nav.Link className="mx-2 navlink" href="#home">About</Nav.Link>
              <Nav.Link className="mx-2 navlink" href="#link">Services</Nav.Link>
              <Nav.Link className="mx-2 navlink" href="#link">Contact</Nav.Link>
          </Nav> 
        </Navbar.Collapse>
      </Container>
    </Navbar>
  );
}

export default NavBar;