<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\setupController;

use userController;

use Request;

class setupRoutes extends Controller
{

	public function getIndex(setupController $setupController) {
		
		$data	=	[
			
			"id"	=>	Request::input("id"),
			
			"code"	=>	Request::input("code")
				
		];
		
        $data = Request::only('id', 'code');
		
		return $setupController->verifyLink($data);
		
	}

	public function postCheckusername(setupController $setupController) {
		
		return $setupController->checkUsername(Request::input("username"));
		
	}
	
	public function postIndex(setupController $setupController) {
		
		$data	=	[
			
			"first_name"=>	Request::input("first_name"),
			
			"last_name"	=>	Request::input("last_name"),
			
			"password"	=>	Request::input("password1"),

			"username"	=>	Request::input("username"),
	
			"code"		=>	Request::input("code"),
			
			"id"		=>	Request::input("id"),

			"gender"	=>	Request::input("gender")
				
		];	

		return $setupController->completeSetup($data);		
		
	}
				
}
