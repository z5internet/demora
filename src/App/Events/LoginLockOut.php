<?php namespace z5internet\ReactUserFramework\App\Events;

use Illuminate\Http\Request;

class LoginLockOut {

    public $request;

    public function __construct(Request $request) {

		$this->request = $request;

    }

}