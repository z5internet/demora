<?php namespace z5internet\ReactUserFramework\App\Http\Controllers;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use z5internet\ReactUserFramework\App\ErrorLog;

use z5internet\ReactUserFramework\App\Events\ErrorLogged;

class ErrorLogController extends Controller {

	public function LogError($data) {

		$db = new ErrorLog;

		$db->url = $data['url'];
		$db->uid = $data['uid']?$data['uid']:0;
		$db->stacktrace = json_encode($data['stacktrace']);
		$db->created_at = app('db')->raw('now()');
		$db->type = $data['type'];

		$db->save();

		event(new ErrorLogged($db));

		return $db;

	}

}
