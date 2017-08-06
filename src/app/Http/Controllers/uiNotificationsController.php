<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\UiNotifications;

use Carbon\Carbon;

use z5internet\ReactUserFramework\App\Http\Controllers\PushController;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

class uiNotificationsController extends Controller {

	public function showNotifications($uid) {

		$notif = UiNotifications::where('u', $uid);

		$notif = $notif->orderBy('updated_at', 'desc');

		$notif = $notif->take(20);

		$notif = $notif->get(['id', 'u', 'i', 'b', 'r', 'l', app('db')->raw('updated_at as t')]);

		$out = [];

		$users = [];

		foreach ($notif as $not) {

			$not->b = json_decode($not->b);

			$out[$not->id] = $not;

			$users[$not->u.'-u'] = 1;

			foreach($not->b as $nb) {

				if (isset($nb->u)) {

					$users[$nb->u] = 1;

				}

			}

		}

		$out = ['uiNotifications' => $out];

		$out['users'] = [];

		foreach (array_keys($users) as $tu) {

			$out['users'][$tu] = UserController::getUser($tu);

		}

		return $out;

	}

	public function addNotification($notification) {

		$notif = UiNotifications::firstOrNew(['id' => $notification->id, 'u' => $notification->uid]);

		$notif->u = $notification->uid;

		$notif->i = $notification->image;

		$notif->b = json_encode($notification->body);

		if (isset($notification)) {

			$notif->l = $notification->link;

		}

		$notif->updated_at = Carbon::now();

		$notif->r = 0;

		$notif->save();

		(new PushController)->pushToUserChannel($notification->uid, 'uiNotifications', $notif->updated_at, $notif->updated_at);

		return $notif;

	}

	public function markUiNotificationAsRead($nid, $uid) {

		$notif = UiNotifications::firstOrNew(['id' => $nid, 'u' => $uid]);

		$notif->r = 1;

		$notif->updated_at = app('db')->raw((new Carbon($notif->updated_at))->subSecond());

		$notif->save();

		return [
			$notif->id => [
				'r' => $notif->r,
			],
		];

	}

}
