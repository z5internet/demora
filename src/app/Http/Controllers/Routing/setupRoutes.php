<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\SetupController;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use Illuminate\Http\Request;

class setupRoutes extends Controller
{

	public function getIndex(Request $request, SetupController $setupController) {

		$data	=	[

			"id"	=>	$request->input("id"),

			"code"	=>	$request->input("code")

		];

        $data = $request->only('id', 'code');

		return $setupController->verifyLink($data);

	}

	public function postCheckusername(Request $request, SetupController $setupController) {

		return $setupController->checkUsername($request->input("username"));

	}

	public function postIndex(Request $request, SetupController $setupController) {

		$data	=	[

			"first_name" => trim($request->input("first_name")),

			"last_name" => trim($request->input("last_name")),

			"password" => $request->input("password1"),

			"username" => trim($request->input("username")),

			"code" => $request->input("code"),

			"id" => $request->input("id"),

			"gender" => $request->input("gender"),

			"teamName" => trim($request->input("teamName")),

		];

		$st = [
			'data' => [
				'setup' => $setupController->completeSetup($data),
			],
		];

		if (!app('auth')->check()) {

			return $st;

		}

		$user = app('auth')->user();

		$token = app('tymon.jwt.auth')->fromUser($user);

		return UserController::returnLoginHeaders($request, $token, $st);

	}

	public function setupExistingUser(Request $request, SetupController $setupController) {

		$data	=	[

			"code" => $request->input("code"),

			"id" => $request->input("id"),

		];

		$setupController->setupExistingUser($data);

		return [
			'data' => [
				'teams' => (new TeamsController)->getTeamsForUser(app('auth')->id())
			]
		];

	}

}
