<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class OnlineController extends Controller {

	public function setOnline() {

		app('cache')->put($this->key(), time(), 5);

	}

	public function isOnline($uid=null) {

		$lt = app('cache')->get($this->key($uid));

		return !(($lt+60) < time());

	}


	public function key($uid=null) {

		if (!$uid) {

			if (!app('auth')->check()) {

				return;

			}

			$uid = app('auth')->id();

		}

		return 'user_online_'.$uid;

	}

}
