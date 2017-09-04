<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Http\Controllers\SetupController;

use z5internet\ReactUserFramework\App\Http\Controllers\SettingsController;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

use Illuminate\Http\Request;

class settingsRoutes extends Controller
{

	public function putIndex(Request $request, SettingsController $settingsController) {

		$settings = $request->input("settings");

		$uid = app('auth')->id();

		return $settingsController->saveSettings($settings, $uid);

	}

	public function postUploadprofileimage(Request $request, SettingsController $settingsController) {

		return $settingsController->postUploadprofileimage($request->input('imageData'), $request->input('crop'));

	}

	public function addTeamMember(Request $request, SettingsController $settingsController, TeamsController $teamsController) {

		$uid = app('auth')->id();

		$tid = $request->input('team');

		if (!$this->isUserAnAdminOfTeam($uid, $tid)) {
			return [];
		}

		$data = [
			'first_name' => $request->input('first_name'),
			'email' => $request->input('email'),
			'teamRole'	=> $request->input('role'),
			'team'	=> $request->input('team'),
		];

		$addTeamMember = $settingsController->addTeamMember($data);

		if (isset($addTeamMember['error'])) {

			return ['errors' => [$addTeamMember['error']]];

		}

		return [
			'data' => [
				'members' => $teamsController->getTeamMembers($tid),
			]
		];

	}

	public function addTeam(Request $request, TeamsController $teamsController) {

		return [
			'data' => [
				'team' => $teamsController->addTeam($request->input('teamName'), app('auth')->id())
			]
		];

	}

	public function editTeamName(Request $request, TeamsController $teamsController) {

		$uid = app('auth')->id();

		$tid = $request->input('team');

		if (!$this->isUserAnAdminOfTeam($uid, $tid)) {
			return [];
		}

		return [
			'data' => [
				'team' => $teamsController->editTeamName($tid, $request->input('teamName'), $uid)
			]
		];

	}

	public function getTeamMembers(Request $request, TeamsController $teamsController) {

		$tid = $request->input('team');

		if (!$this->isUserAnAdminOfTeam(app('auth')->id(), $tid)) {
			return [];
		}

		return [
			'data' => [
				'members' => $teamsController->getTeamMembers($tid)
			]
		];

	}

	public function deleteTeam(Request $request, TeamsController $teamsController) {
		return [
			'data' => [
				'deleted' => $teamsController->deleteTeam($request->input('tid'), app('auth')->id()),
			]
		];
	}

	public function deleteActiveUser(Request $request, TeamsController $teamsController) {
		return [
			'data' => [
				'deleted' => $teamsController->deleteActiveTeamMember($request->input('tid'), $request->input('uid'))
			]
		];
	}

	public function deleteInvitedUser(Request $request, SettingsController $settingsController) {
		return [
			'data' => [
				'deleted' => $settingsController->deleteInvitedTeamMember($request->input('uid'))
			]
		];
	}

	private function isUserAnAdminOfTeam($uid, $tid) {

		return (new TeamsController)->isUserAnAdminOfTeam($uid, $tid);

	}

}
