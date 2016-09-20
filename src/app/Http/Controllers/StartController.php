<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use userController;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\GetStarted\GetStartedController;

use Auth;

use User;

class StartController extends Controller {

	public function show() {

		$user = [];

		$menu = [];

		if (Auth::check()) {

			$uid = Auth::user()->id;

			$user = userController::user($uid); 

			if ((new AdminController)->isUserAnAdmin()) {
				array_push($menu,[
					'heading' => 'Admin',
					'items' => [
						[
							'url' => '/admin',
							'link' => 'Admin'
						]
					]
				]);
			}


			$user['finishedGetStarted'] = (new GetStartedController)->hasUserCompletedStartup();

			$user['email'] = User::find($uid)->email;

		}

		$user['menu'] = $menu;

		return [
			'data' => [
				'user' => $user,
				'website' => [
					'name' => config('react-user-framework.website.website_name'),
					'domain' => config('react-user-framework.website.domain'),
					'setup' => [
						'usernameRequired' => config('react-user-framework.setup.username_required'),
						'uploadProfilePic' => config('react-user-framework.setup.upload_profile_pic'),
					],
				],
			]
		];
	}

}
