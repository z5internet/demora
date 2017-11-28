import React, { Component } from 'react'
import { Link } from 'react-router-dom';

class FooterNavBar extends Component {

  render() {
    return (
          <ul className="nav navbar-nav">
            <li className="nav-item"><Link to="/terms" className="nav-link">Terms</Link></li>
            <li className="nav-item"><Link to="/privacy" className="nav-link">Privacy</Link></li>
            <li className="nav-item"><Link to="/contact" className="nav-link">Contact us</Link></li>
          </ul>);

  }

}

module.exports = FooterNavBar;
