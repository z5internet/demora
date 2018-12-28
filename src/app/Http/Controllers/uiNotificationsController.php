<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\UiNotifications;

use Carbon\Carbon;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use z5internet\ReactUserFramework\App\Events\UiNotificationEvent;

use stdClass;

class uiNotificationsController extends Controller {

	public function showNotifications($uid, $endCursor) {

		$limit = 20;

		$notif = UiNotifications::where('u', $uid);

		$notif = $notif->orderBy('id', 'desc');

		if ($endCursor <> 0) {

			$notif = $notif->where('id', '<', $endCursor);

		}

		$notif = $notif->take($limit);

		$notif = $notif->get(['id', 'nid', 'u', 'i', 'b', 'r', 'l', app('db')->raw('created_at as t')]);

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

		if (count($out) == 0) {

			$out = new stdClass;

		}

		$out = ['uiNotifications' => [

			'notifications' => $out,
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

		$notif->created_at = Carbon::now();

		$notif->r = 0;

		$notif->save();

		$notification = [
			'nid' => $notification->nid,
			'b' => $notification->body,
			'u' => $notification->uid,
			'i' => $notification->image,
			'l' => $notification->link,
			't' => date('Y-m-d H:i:s'),
			'r' => $notif->r,
		];

		event(new UiNotificationEvent($notification));

		return $notif;

	}

	public function markUiNotificationAsRead($nid, $uid) {

		$notif = UiNotifications::where('nid', $nid)->where('u', $uid)->first();

		if (!$notif) {

			return [];

		}

		$notif->r = 1;

		$notif->save();

		$notification = [
			'nid' => $notif->nid,
			'b' => json_decode($notif->b, true),
			'u' => $notif->u,
			'i' => $notif->i,
			'l' => $notif->l,
			't' => $notif->created_at,
			'r' => $notif->r,
		];

		event(new UiNotificationEvent($notification));

		return [
			$notif->id => [
				'r' => $notif->r,
			],
		];

	}

}
