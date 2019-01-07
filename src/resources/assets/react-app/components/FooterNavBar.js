import React, { Component } from 'react'
import { Link } from 'react-router-dom';

class FooterNavBar extends Component {

	render() {

        let cols = 'col-4';

		return (<div style={{ flexGrow:1 }}>
			<div className="d-flex">
				<div className="row text-center mx-auto">
					<div className={cols}>
						<Link to="/contact" className="text-white">Contact</Link>
					</div>
					<div className={cols}>
						<Link to="/terms" className="text-white">Terms</Link>
					</div>
					<div className={cols}>
						<Link to="/privacy" className="text-white">Privacy</Link>
					</div>
				</div>
			</div>
		</div>);

	}

}

export default FooterNavBar;
