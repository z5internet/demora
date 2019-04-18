<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

use z5internet\ReactUserFramework\App\Events;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

use Carbon\Carbon;

class RecordUserOnline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

    	if (app('auth')->check()) {

    		$uid = app('auth')->user()->id;

    		$user = UserController::user($uid);

    		if (!isset($user->o) || !$user->o) {

    			$date = (new Carbon)->startOfDay();

    			$check = Events::where('uid', $uid)
    				->where('created_at', $date)
    				->first();

    			if (!$check) {

					Events::insert(['uid' => $uid, 'event' => 4, 'created_at' => $date]);

    			}

    			UserController::setOnline($uid);

    		}

    	}

		return $next($request);

	}

}
