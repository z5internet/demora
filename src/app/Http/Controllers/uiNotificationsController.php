<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\UiNotifications;

use Carbon\Carbon;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Events\UiNotificationEvent;

class uiNotificationsController extends Controller {

	public function showNotifications($uid, $endCursor) {

		$limit = 20;

		$notif = UiNotifications::where('u', $uid);

		$notif = $notif->orderBy('id', 'desc');

		if ($endCursor <> 0) {

			$notif = $notif->where('id', '<', $endCursor);

		}

		$notif = $notif->take($limit);

		$notif = $notif->get(['id', 'nid', 'u', 'i', 'b', 'r', 'l', app('db')->raw('updated_at as t')]);

		$out = [];

		$users = [];

		foreach ($notif as $not) {

			$not->b = json_decode($not->b);

			$out[$not->nid] = $not;

			$users[$not->u.'-u'] = 1;

			foreach($not->b as $nb) {

				if (isset($nb->u)) {

					$users[$nb->u] = 1;

				}

			}

		}

		$endCursor = -1;

		if ($notif->count() == $limit) {

			$last = $notif->last();

			$endCursor = $last->id;

		}

		$out = ['uiNotifications' => [

			'notifications' => array_values($out),
			'endCursor' => $endCursor,

		]];

		$out['users'] = [];

		foreach (array_keys($users) as $tu) {

			$out['users'][$tu] = UserController::getUser($tu);

		}

		return $out;

	}

	public function addNotification($notification) {

		$notif = UiNotifications::firstOrNew(['nid' => $notification->nid, 'u' => $notification->uid]);

		$notif->u = $notification->uid;

		$notif->nid = $notification->nid;

		$notif->i = $notification->image;

		$notif->b = json_encode($notification->body);

		if (isset($notification)) {

			$notif->l = $notification->link;

		}

		$notif->updated_at = Carbon::now();

		$notif->r = 0;

		$notif->save();

		$notification = [
			'id' => $notification->nid,
			'b' => $notification->body,
			'u' => $notification->uid,
			'i' => $notification->image,
			'l' => $notification->link,
			't' => date('Y-m-d H:i:s'),
		];

		event(new UiNotificationEvent($notification));

		return $notif;

	}

	public function markUiNotificationAsRead($nid, $uid) {

		$notif = UiNotifications::firstOrNew(['nid' => $nid, 'u' => $uid]);

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
