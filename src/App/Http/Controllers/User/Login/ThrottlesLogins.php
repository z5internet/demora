<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\User\Login;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiter;
use z5internet\ReactUserFramework\App\Events\LoginLockOut;

class ThrottlesLogins {

    /**
     *
     * Throttle by email used
     *
     * Throttle by IP
     *
     */

    private $max_attempts = [

        'email' => 5,
        'ip' => 5,

    ];

    public function hasTooManyLoginAttempts(Request $request) {

        if ($this->limiter()->tooManyAttempts($this->key($request, 'email'), $this->max_attempts['email'])) {

            return true;

        }

        if ($this->limiter()->tooManyAttempts($this->key($request, 'ip'), $this->max_attempts['ip'])) {

            return true;

        }

        return false;

    }

    public function incrementLoginAttempts(Request $request) {

        $this->limiter()->hit($this->key($request, 'email'), 30);

        $this->limiter()->hit($this->key($request, 'ip'), 30);

    }

    public function clearLoginAttempts(Request $request) {

        $this->limiter()->clear($this->key($request, 'email'));

        $this->limiter()->clear($this->key($request, 'ip'));

    }

    public function fireLockoutEvent(Request $request) {

        event(new LoginLockOut($request));

    }

    public function checkAttemptsLeft(Request $request) {

        $a = $this->limiter()->retriesLeft($this->key($request, 'email'), $this->max_attempts['email']);

        $b = $this->limiter()->retriesLeft($this->key($request, 'ip'), $this->max_attempts['ip']);

        return min($a, $b);

    }

    public function key(Request $request, $type) {

        $key = '';

        switch ($type) {

            case 'ip':

                $key = $request->ip();

                break;

            case 'email':

                $key = strtolower($request->input('email'));

                break;

            default:

                abort(500);

                break;

        }

        return 'throttle|login|'.$key;

    }

    public function limiter() {

        return app(RateLimiter::class);

    }

}
