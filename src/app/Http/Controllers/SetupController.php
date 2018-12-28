<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Joined;

use App\User;

class SetupController extends Controller
{

	public function verifyLink($data) {

		$checkJ	= Joined::where('id', '=', $data['id'])
						->where('code','=', $data['code'])
						->get(['first_name', 'team', 'email'])
						->first();

		if (!$checkJ) {

			return ['data' => ['setup' => [] ] ];

		}

		if (app('auth')->check() && app('auth')->user()->email <> $checkJ['email']) {

			return ['data' => [], 'errors' => [['message' => 'Your are not logged in as the correct user, logout then login as the correct user.']]];

		}

		$checkJ = $checkJ->toArray();

		$existingUser = false;

		if (config('react-user-framework.website.multiAccounts') && UserController::getUserByEmail($checkJ['email'])) {

			$existingUser = true;

		}

		$invited = $checkJ['team']?true:false;

		$teamToJoin = null;

		if ($existingUser) {

			if (app('auth')->check()) {

				$teamToJoin = (new TeamsController)->getTeamName($checkJ['team']);

			}

		}

		if (count($checkJ)>0) {

			$fn		=	$checkJ["first_name"];

			return [

				'data' => [

					'setup'	=>	[
						'existingUser' => $existingUser,
						'invited' => $invited,
						'first_name' => $checkJ['first_name'],
						'teamToJoin' => $teamToJoin,
						'uploadProfilePic' => config('react-user-framework.setup.upload_profile_pic'),
						'usernameRequired' => config('react-user-framework.setup.username_required'),
					],

				]

			];

		}

		return ['data' => ['setup' => [] ] ];

	}

	public function checkUsername($username) {

		$username = trim($username);

		$check	=	UserController::getIdFromUsername($username);

		if (!$check) {

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

		if (count($this->verifyLink($codeData)["data"]["setup"])==0) {

			return ['error' => "This is not a valid verification link. This is probably because you've already validated your email address, or it could be because you haven't typed the link in correctly."
			];

		}

		$j = joined::where('id', '=', $origData['id'])
				->where('code', '=', $origData['code'])
				->first(['email', 'ref', 'team', 'teamRole'])
				->toArray();

		$error = $this->validateData($origData, $j);

		if ($error) {

			return ['error' => $error];

		}

		return $this->completeSetup2($origData, $j);

	}

	private function completeSetup2($origData, $j) {

		$ref = UserController::decodeReferralCookieAndCheckReferrer($j["ref"]);

		$data = [

			"first_name" => $origData["first_name"],
			"last_name" => $origData["last_name"],
			"username" => null,
			"password" => $origData["password"],
			"gender" =>	$origData["gender"],
			"referrer" => $ref['referrer'],
			"referred_url" => $ref["referred_url"]

		];

		if (config('react-user-framework.setup.username_required')) {

			$data['username'] = trim($origData['username']);

		}

		$data['email'] = $j['email'];

		$uid	= UserController::addUser($data);

		$role = $j['teamRole'];

		if ($uid) {

			UserController::loginUsingId($uid);

			$team = 0;

			if ($j['team']) {

				(new TeamsController)->addTeamMember($j['team'], $uid, $role);

				$team = $j['team'];

			}
			else
			{

				if (!$origData['teamName']) {

					$origData['teamName'] = '';

				}

				$team = (new TeamsController)->addTeam($origData['teamName'], $uid)['id'];

			}

			$deleteJ = joined::where('email', '=', $data['email']);

			$deleteJ->where('team', $j['team']);

			$deleteJ->delete();

			return ['team' => $team];

		}

		return ['error'	=>	'There was a problem completing setup.'];

	}

	public function passwordNotValid($password) {

		$return		=	null;

		if (strlen($password) < 6) {

			$return	=	"Your password should be at least 6 characters long.";

		}

		return $return;

	}

	public function setupExistingUser($data) {

		$j = joined::where('id', '=', $data['id'])
				->where('code', '=', $data['code'])
				->get(['email', 'team', 'teamRole'])
				->first();

		if (!$j) {

			return [];

		}

		$j = $j->toArray();

		if ($j['email'] <> app('auth')->user()->email || !$j['team']) {

			return [];

		}

		$uid = app('auth')->id();

		(new TeamsController)->addTeamMember($j['team'], $uid, $j['teamRole']);

		$deleteJ = joined::where('email', '=', $j['email']);

		$deleteJ->where('team', $j['team']);

		return $deleteJ->delete();

	}

	private function validateData($origData, $j) {

		$error = '';

		if (!$origData["first_name"] || !$origData["last_name"]) {

			$error = "You need to enter your name";

		}

		if (!is_numeric($origData["gender"]) || $origData["gender"] > 1) {

			$error = "You need to select your gender";

		}

		if ($this->passwordNotValid($origData["password"])) {

			$error = $this->passwordNotValid($origData["password"]);

		}

		if (config('react-user-framework.setup.username_required')) {

			if (strlen($origData["username"])<6 || preg_match("/[^a-zA-Z0-9]/",$origData["username"])) {

				$error = "Your username should be at least 6 characters long and only contain letters A to Z and number 0 to 9.";

			}

			if (!array_get($this->checkUsername($origData['username']),'data.setup.usernameOK')) {

				$error = "Your username is already being used.";

			}

		}

		$teamLabel = config('react-user-framework.website.multiAccounts.label');

		if ($teamLabel && !$j['team'] && !$origData['teamName']) {

			$error = "You need to select your $teamLabel name.";

		}

		return $error;

	}

}
