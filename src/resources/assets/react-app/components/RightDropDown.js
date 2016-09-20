import React, { Component } from 'react'

import { Link } from 'react-router';

import { DropdownMenu, DropdownItem } from 'reactstrap';

class RightDropDown extends Component {

	render() {

		return (<DropdownMenu right>

			{this.props.links.map(ex => {
			   return (<span key={ex.heading}>
			   		<DropdownItem header>{ex.heading}</DropdownItem>
			   		{ex.items.map(ex1 => {
					   	return (<DropdownItem key={ex1.url}>                            
					      <Link to={ex1.url}>
					          {ex1.link}
					      </Link>
					    </DropdownItem>);
					})}
					<DropdownItem divider />
				</span>);			
			})}

		    <DropdownItem header>Settings</DropdownItem>
		    <DropdownItem>                            
		      <Link to="/settings">
		          <i className="fa fa-fw fa-btn fa-cog"></i>Your Settings
		      </Link>
		    </DropdownItem>                         
		    <DropdownItem divider />
		    <DropdownItem header>Support</DropdownItem>
		    <DropdownItem>                            
		      <a style={{cursor: 'pointer'}}>
		          <i className="fa fa-fw fa-btn fa-paper-plane"></i>Email Us
		      </a>
		    </DropdownItem>
		    <DropdownItem>
		      <Link to="/logout">
		          <i className="fa fa-fw fa-btn fa-sign-out"></i>Logout
		      </Link>
		    </DropdownItem>
		  </DropdownMenu>);
	}

}

module.exports = RightDropDown;
