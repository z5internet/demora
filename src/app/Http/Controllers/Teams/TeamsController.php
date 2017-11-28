<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Teams;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\Teams;

use z5internet\ReactUserFramework\App\TeamUsers;

use z5internet\ReactUserFramework\App\Joined;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Http\Controllers\Pay\PayController;

use stdClass;

class TeamsController extends Controller {

	public function getCurrentTeamForThisUser() {

		$team = array_get(getallheaders(),'X-Current-Team',0);

		if (!$team) {

			$team = array_get(getallheaders(),'x-current-team',0);

		}

		$uid = app('auth')->id();

		if ($team >0 && $this->isUserAMemberOfTeam($uid, $team)) {

			return (int) $team;

		}

		if ($team > 0) {

			throw(new \Exception("User $uid not a member of team $team"));

		}

		return 0;

	}

	public function getDefaultTeamForUser($uid=null) {

		if (!$uid) {

			$uid = app('auth')->id();

		}

		$team = TeamUsers::where('uid',$uid)->first();

		if ($team) {

			return $team->tid;

		}

	}

	public function isUserAMemberOfTeam($uid = null, $tid = null) {

		return $this->getRoleForUserInTeam($uid, $tid)?true:false;

	}

	public function isUserAnAdminOfTeam($uid = null, $tid = null) {

		return $this->getRoleForUserInTeam($uid, $tid)==255?true:false;

	}

	public function isUserTheOwnerOfTeam($uid = null, $tid = null) {

		return $this->getOwnerOfTeam($tid)==$uid?true:false;

	}

	public function getOwnerOfTeam($tid) {

		return Teams::where('id', $tid)->get(['owner_id'])->first()['owner_id'];

	}

	public function getRolesForATeam($tid) {

		return [
			[
				'id' => 1,
				'name' => 'User'
			],
			[
				'id' => 255,
				'name' => 'Administrator'
			]
		];
	}

	public function getTeamsForUser($uid = null) {

		if (!$uid) {

			$uid = app('auth')->id();

		}

		$teams = TeamUsers::where('uid', $uid)->get(['tid']);

		$out = [];

		foreach($teams as $team) {

			$tid = $team['tid'];

			$t = $this->getTeam($uid, $tid);

			$products = (new PayController)->teamSubscribedTo($tid);

			$t['subscribed'] = ['products' => new stdClass, 'groups' => $products['groups']];

			foreach($products['products'] as $product) {

				$a = [
					'id' => $product->id,
					'product_group' => $product->product_group,
					'product_id' => $product->product_id,
					'willChargeForUsers' => (new PayController)->willAppAutoChargeForUsers($product),
				];

				$t['subscribed']['products']->{$a['id']} = $a;

			}

			$out[$tid] = $t;

		}

		return $out;

	}

	public function getTeamName($tid) {

		$team = Teams::where('id', $tid)->first(['name']);

		if ($team) {
			return $team->name;
		}

		return false;

	}

	public function getRoleForUserInTeam($uid=null, $tid=null) {

		if (!$uid) {

			$uid = app('auth')->id();

		}

		if (!$tid) {

			$tid = $this->getCurrentTeamForThisUser();

		}

		$role = TeamUsers::where('uid',$uid)->where('tid', $tid)->first(['role']);

		if ($role) {

			return $role->role;

		}

	}

	public function addTeamMember($tid, $uid, $role) {

		$db = new TeamUsers;

		$db->tid = $tid;
		$db->uid = $uid;
		$db->role = $role;

		$db->save();

		(new PayController)->addTeamMember($tid, $uid, $role);

		return $db;

	}

	public function addTeam($teamName, $owner) {

		$db = new Teams;

		$db->owner_id = $owner;
		$db->name = $teamName;

		$db->save();

		$tid = $db->id;

		$this->addTeamMember($tid, $owner, 255);

		return $this->getTeam(app('auth')->id(), $tid);

	}

	public function editTeamName($tid, $teamName, $uid) {

		$db = Teams::where('id', $tid)->update(['name' => $teamName]);

		return $this->getTeam($uid, $tid);
	}

	public function getTeamMembers($tid) {

		$db1 = TeamUsers::where('tid', $tid)->get(['role', 'tid', 'uid']);

		foreach ($db1 as $key => $value) {

			$user = UserController::user($value['uid']);

			$db1[$key]['first_name'] = $user->first_name;
			$db1[$key]['last_name'] = $user->last_name;
			$db1[$key]['image'] = $user->image;

		}

		$db2 = Joined::where('team', $tid)->get([app('db')->raw('id as uid'), app('db')->raw($tid.' as tid'), 'first_name', 'email', app('db')->raw('teamRole as role')]);

		return [
			'active' => $db1,
			'invited' => $db2,
		];

	}

	private function getTeam($uid, $tid) {

		if (!$this->isUserAMemberOfTeam($uid, $tid)) {
			return false;
		}

		return [
			'id' => $tid,
			'name' => $this->getTeamName($tid),
            'role' => $this->getRoleForUserInTeam($uid, $tid),
            'roles' => $this->getRolesForATeam($tid),
            'owner' => $this->getOwnerOfTeam($tid),
		];

	}

	private function getTeamsOwnedByUser($uid) {

		return Teams::where('owner_id', $uid)->get();

	}

	public function deleteTeam($id, $uid) {

		if (!$this->isUserTheOwnerOfTeam($uid, $id)) {
			return false;
		}

		if (count($this->getTeamsOwnedByUser($uid)) < 2) {

			return false;

		}

		TeamUsers::where('tid', $id)->delete();

		return Teams::where("id", "=", $id)->delete();

	}

	public function deleteActiveTeamMember($tid, $uid) {

		if ($this->isUserTheOwnerOfTeam($uid, $tid)) {

			return false;

		}

		return TeamUsers::where('tid', $tid)
					->where('uid', $uid)
					->delete();

	}

}
