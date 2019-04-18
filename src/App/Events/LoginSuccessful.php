<?php namespace z5internet\ReactUserFramework\App\Events;

use App\User;

class LoginSuccessful {

    public $request;

    public $user;

    public function __construct(User $user) {

		$this->request = app('request');

		$this->user = $user;

    }

}