import React, { useState } from 'react';
import NavBar  from './NavBar';
import Footer  from './Footer';

const SignupForm = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    contact: '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Here you would usually handle the form submission, e.g., sending the data to a server
    console.log('Form Data Submitted:', formData);
  };

  return (
    <>
    <NavBar/>

    <div className="container my-5">
      <div className="row">
        <div className="col-md-6 box-cont offset-md-3">

      <div className='my-4 mb-5 text-center'>
        <h3>Signup with us!</h3>
      </div>
       

    <form onSubmit={handleSubmit}>
      <div className=" labelcontainer mt-3 mx-5">
        <label for="name">Name</label>
        <input type="text" className="form-control" id="name" value={formData.name}
          onChange={handleChange} required/>
      </div>

      <div className=" labelcontainer mx-5">
        <label for="exampleInputEmail1">Email</label>
        <input type="email" className="form-control" id="exampleInputEmail1" value={formData.email}
          onChange={handleChange} required />
      </div>

      <div className=" labelcontainer mx-5"> 
        <label for="exampleInputPassword1">Password</label>
        <input type="password" className="form-control" id="exampleInputPassword1" value={formData.password}
          onChange={handleChange} required />
      </div>
      
      <div className=" labelcontainer mx-5">
        <label for="contact">Contact</label>
        <input type="text" className="form-control" id="contact" value={formData.contact}
          onChange={handleChange} required />
      </div>

      <div className="text-center">
        <button className='btn-brown my-3' type="submit">Sign Up</button>
      </div>
    </form>
    </div>
      </div>
    </div>
    <Footer/>
    </>
  );
};

export default SignupForm;
