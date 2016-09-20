<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use userController;

use Auth;

use darrenmerrett\ReactUserFramework\App\Joined;

class SetupController extends Controller
{

	public function verifyLink($data) {
		
		$check	=	Joined::where("id","=",$data['id'])
						->where('code','=',$data["code"])
						->get(['first_name'])
						->toArray();
		
		
		if (count($check)>0) {

			$fn		=	$check[0]["first_name"];
			
			return [

				"data" => [
			
					"setup"	=>	[
					
						"first_name"	=>	$fn
					
					],

				]
			
			];
			
		}
		
		return ["data"=>["setup"=>[]]];
				
	}
	
	public function checkUsername($username) {

		$check	=	userController::getIdFromUsername($username);
		
		if (count($check)==0) {
					
			return [
				"data" => [
					"setup"	=>	[
						"usernameOK"	=>	true,			
					]
				]
			];
		
		}
		else
		{
			
			return [
				"data" => [
					"setup"	=>	[	
						"usernameError"	=>	"That username has already been taken, please choose another."
					]		
				]
			];		
			
		}
		
	}
	
	public function completeSetup($origData) {
		
		$codeData	=	[
			
			"id"	=>	$origData["id"],
			"code"	=>	$origData["code"]
			
		];
		
		$error	=	"";
		
		if (count($this->verifyLink($codeData)["data"]["setup"])==0) {
			
			$error	=	"This is not a valid verification link. This is probably because you've already validated your email address, or it could be because you haven't typed the link in correctly.";
					
		}
		else
		{
			
			$j = joined::where("id","=",$origData["id"])
					->where("code","=",$origData["code"])
					->get(['email','ref'])
					->toArray();

			$ref			=	userController::decodeReferralCookieAndCheckReferrer($j[0]["ref"]);
	
			$data	=	[
			
				"first_name"	=>	$origData["first_name"],
				"last_name"		=>	$origData["last_name"],
				"password"		=>	$origData["password"],
				"username"		=>	$origData["username"],
				"gender"		=>	$origData["gender"],
				"referrer"		=>	$ref["referrer"],
				"referred_url"	=>	$ref["referred_url"]
			
			];
						
			$data["email"]	=	$j[0]["email"];
						
			if (!$data["first_name"] || !$data["last_name"]) {
			
				$error	=	"You need to enter your name";
			
			}
			
			if (!is_numeric($data["gender"])) {
			
				$error	=	"You need to select your gender";
			
			}
			
			if ($this->passwordNotValid($data["password"])) {
				
				$error	=	$this->passwordNotValid($data["password"]);
				
			}

			if (config('react-user-framework.setup.username_required')) {
			
				if (strlen($data["username"])<6 || preg_match("/[^a-zA-Z0-9]/",$data["username"])) {
				
					$error	=	"Your username should be at least 6 characters long and only contain letters A to Z and number 0 to 9.";
				
				}
			
				if (!array_get($this->checkUsername($data['username']),'data.setup.usernameOK')) {
				
					$error	=	"Your username is already being used.";
				
				}

			}
			else
			{

				$data["username"] = null;

			}

			if (!$error) {

				$id	=	userController::addUser($data);
		
				if ($id) {
						
					Auth::loginUsingId($id);

					joined::where("email","=",$data["email"])
						->delete();
		
					return (new StartController)->show();
		
				}
			
				$error	=	"There was a problem completing setup.";
			
			}
			
		}
		
		if ($error) {
			
			return [
				"data" => [
					"setup"	=>	[
						"error"	=>	$error
					]
				]
			];			
			
		}
		
	}
	
	public function passwordNotValid($password) {
		
		$return		=	null;
		
		if (strlen($password)<6 || preg_match("/[^a-zA-Z0-9]/",$password)) {
			
			$return	=	"Your password should be at least 6 characters long and only contain letters A to Z and number 0 to 9.";
			
		}
		
		return $return;

	}

}