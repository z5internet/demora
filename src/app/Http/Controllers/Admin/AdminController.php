<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Admin;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\AppManagers;

use z5internet\ReactUserFramework\App\AdminServices;

class AdminController extends Controller {

	public function isUserAnAdmin() {

		$uid = app('auth')->id();

		if ($uid == 1) {

			return true;

		}

		$isAdmin = AppManagers::where('uid', app('auth')->id())->first();

		if ($isAdmin) {

			return true;

		}

	}

	public function getAdminServicesForUser($uid) {

		$AdminServicesForManager = AppManagers::where('uid', $uid)->get(['service']);

		if ($AdminServicesForManager->count() > 0) {

			$admin = AdminServices::whereIn('id', array_pluck($AdminServicesForManager, 'service'))
				->get(['service']);

			$admin = array_pluck($admin, 'service');

			$admin = array_combine($admin, array_fill(0, count($admin), 1));

			return $admin;

		}

	}

	public function doesUserHaveAccessToAdminService($uid, $service) {

		$access = $this->checkDoesUserHaveAccessToAdminService($uid, $service);

		if (!$access) {

			abort(401);

		}

		return true;

	}

	private function checkDoesUserHaveAccessToAdminService($uid, $service) {

		$service = AdminServices::where('service', $service)->first(['id']);

		if (!$service){

			return false;

		}

		$isAdmin = AppManagers::where('uid', app('auth')->id())
			->where('service', $service->id)
			->first();

		return !!$isAdmin;


	}

}
