<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Routing;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\setupController;

use userController;

use Request;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\settingsController;

class settingsRoutes extends Controller
{
	
	public function putIndex(settingsController $settingsController) {
		
		$settings	=	Request::input("settings");
		
		return $settingsController->saveSettings($settings);
		
	}

	public function postUploadprofileimage(settingsController $settingsController) {
		
		return $settingsController->postUploadprofileimage(Request::input('imageData'),Request::input('crop'));
		
	}
	
}
