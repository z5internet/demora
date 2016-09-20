<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use userController;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\ContactController;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\StartController;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Image\ImageController;

use Illuminate\Http\Request;

use Cookie;

class routesController extends Controller
{

	public function __construct(Request $request) {

		$this->request =	$request;

	}

	public function start() {
		
		return (new StartController)->show();
		
	}
	
	public function login() {
		
		return UserController::login($this->request);
		
	}
	
	public function join() {

        $data = $this->request->only('first_name', 'email');

		$data["ref"]	=	Cookie::get('sou');

		return UserController::join($data);
		
	}
	
	public function logout() {

		return UserController::logout();

	}

	public function staticData() {
		
		return UserController::showStaticData();
		
	}
	
	public function sendResetLinkEmail() {
		
		$data = $this->request->only('email');

		return UserController::sendResetLinkEmail($data);
		
	}
	
	public function resetPassword() {
		
		$data = $this->request->only('token','password1','password2');

		return UserController::resetPassword($data);
		
	}

	public function contactUs() {

		$data = $this->request->only('name','email','message');
		
		return (new ContactController)->contactUs($data);

	}

	public function getImage($img) {

		return (new ImageController)->get($img);

	}

	public function createImage($img) {

		return (new ImageController)->create($img);

	}
						
}
