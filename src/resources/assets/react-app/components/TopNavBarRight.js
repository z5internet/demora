import React, { Component } from 'react'
import { Link } from 'react-router-dom';

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

class TopNavBarRight extends Component {

	render() {
		return (
              <ul className="nav navbar-nav navbar-right">

              </ul>
        );

	}

}

module.exports = TopNavBarRight;



