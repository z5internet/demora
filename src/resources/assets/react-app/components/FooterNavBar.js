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
            <li><Link to="/terms" style={styles.link} activeStyle={styles.activeLink}>Terms</Link></li>
            <li><Link to="/privacy" style={styles.link} activeStyle={styles.activeLink}>Privacy</Link></li>
            <li><Link to="/contact" style={styles.link} activeStyle={styles.activeLink}>Contact us</Link></li>
          </ul>);

  }

}

module.exports = FooterNavBar;
