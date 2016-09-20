<?php namespace darrenmerrett\ReactUserFramework\App\Http\Controllers\Admin;

use darrenmerrett\ReactUserFramework\App\Http\Controllers\Controller;

use darrenmerrett\ReactUserFramework\App\Admins;

use Auth;

class AdminController extends Controller {

	public function isUserAnAdmin() {

		$uid = Auth::id();

		if ($uid==1) {

			return true;
			
		}

		$isAdmin = Admins::find(Auth::id());

		if ($isAdmin) {
			
			return true;

		}

	}

}
