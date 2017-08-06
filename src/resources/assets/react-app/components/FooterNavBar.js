import React, { Component } from 'react'
import { Link } from 'react-router';

const dark = 'hsl(200, 20%, 20%)'
const light = '#bbb'
const styles = {}

styles.link = {
  fontWeight: 200
}

styles.activeLink = {
  ...styles.link,
  background: light,
  color: dark
}

class FooterNavBar extends Component {

  render() {
    return (
          <ul className="nav navbar-nav">
            <li className="nav-item"><Link to="/terms" className="nav-link" style={styles.link} activeStyle={styles.activeLink}>Terms</Link></li>
            <li className="nav-item"><Link to="/privacy" className="nav-link" style={styles.link} activeStyle={styles.activeLink}>Privacy</Link></li>
            <li className="nav-item"><Link to="/contact" className="nav-link" style={styles.link} activeStyle={styles.activeLink}>Contact us</Link></li>
          </ul>);

  }

}

module.exports = FooterNavBar;
