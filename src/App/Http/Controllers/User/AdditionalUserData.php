<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\User;

class AdditionalUserData {

	private static $functions = [];

	public static function register($f) {

		self::$functions[$f] = $f;

	}

	public static function getData($uid) {

		$d = [];

		foreach (self::$functions as $f) {

			$d = $d + call_user_func([new $f, 'getData'], $uid);

		}

		return $d;

	}

}
