<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Admin;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\AppManagers;

class AdminController extends Controller {

	public function isUserAnAdmin() {

		$uid = app('auth')->id();

		if ($uid==1) {

			return true;

		}

		$isAdmin = AppManagers::find(app('auth')->id());

		if ($isAdmin) {

			return true;

		}

	}

}
