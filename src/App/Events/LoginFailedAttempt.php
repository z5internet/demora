<?php namespace z5internet\ReactUserFramework\App\Events;

use Illuminate\Http\Request;

class LoginFailedAttempt {

    public $request;

    public $attemptsLeft;

    public function __construct(Request $request, $attemptsLeft) {

		$this->request = $request;

		$this->attemptsLeft = $attemptsLeft;

    }

}