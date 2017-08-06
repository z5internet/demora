import React, { Component } from 'react'
import { Link } from 'react-router';

class TopNavBarLeft extends Component {

  render() {
    return (

        <ul className="nav navbar-nav navbar-left">       
          <li className="nav-item">
            <Link
              to="/"
              className="nav-link">
                Home
            </Link>
          </li>
        </ul>);

  }

}

module.exports = TopNavBarLeft;
