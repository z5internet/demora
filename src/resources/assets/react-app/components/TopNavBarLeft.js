import React, { Component } from 'react'
import { Link } from 'react-router';

const dark = 'hsl(200, 20%, 20%)'
const light = '#fff'
const styles = {}

styles.link = {
  color: light,
  fontWeight: 200
}

styles.activeLink = {
  ...styles.link,
  background: light,
  color: dark
}

class TopNavBarLeft extends Component {

	render() {
		return (
              <ul className="nav navbar-nav navbar-left">
                <li><Link to="/" style={styles.link}>Home</Link></li>
                <li><Link to="/login" style={styles.link} activeStyle={styles.activeLink}>Login</Link></li>

                <li><Link to="/calendar" style={styles.link} activeStyle={styles.activeLink}>Calendar</Link></li>
                <li><Link to="/grades" style={styles.link} activeStyle={styles.activeLink}>Grades</Link></li>
                <li><Link to="/messages" style={styles.link} activeStyle={styles.activeLink}>Messages</Link></li>

              </ul>);

	}

}

module.exports = TopNavBarLeft;



