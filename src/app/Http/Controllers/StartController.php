<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Http\Controllers\Admin\AdminController;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

use App\Http\Controllers\GetStarted\GetStartedController;

use stdClass;

class StartController extends Controller {

	public function __construct() {

		$this->teamsController = new TeamsController;

	}

	public function show($uid = null) {

		$user = new stdClass;

		$menu = [];

		if (!$uid && app('auth')->check()) {

			$uid = app('auth')->id();

		}

		if ($uid) {

			$currentTeam = $this->teamsController->getCurrentTeamForThisUser();

			if (!$currentTeam) {

				$currentTeam = $this->teamsController->getDefaultTeamForUser();

			}

			$user = UserController::user($uid);

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

			$user->finishedGetStarted = (new GetStartedController)->hasUserCompletedStartup();

			$user->email = UserController::getUser($uid)->email;

			$user->currentTeam = [

				'id' => $currentTeam,
				'role' => $this->teamsController->getRoleForUserInTeam($uid, $currentTeam),

			];

			$user->multiAccounts = $this->teamsController->getTeamsForUser();

			$user->menu = $menu;

			if ($adminServices = (new AdminController)->getAdminServicesForUser($uid)) {

				$user->admin = $adminServices;

			}

		}

		$out = [
			'user' => $user,
			'website' => [
				'name' => config('app.name'),
				'signups' => !config('react-user-framework.website.disallow_public_signups'),
				'multiAccounts' => config('react-user-framework.website.multiAccounts'),
                'stripe_key' => config('react-user-framework.pay.stripe.publishable_key'),
                'livePusher' => [
                	'app_id' => config('broadcasting.connections.livePusher.app_id'),
                ],
			],
		];

		return $out;

	}

}
