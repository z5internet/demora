<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

use z5internet\ReactUserFramework\App\Http\Controllers\Admin\AdminController;

class IsUserAWebsiteAdminMiddleware
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

		if (($request->is('data/admin/*') || $request->is('admin/*')) && !(new AdminController)->isUserAnAdmin()) {

			abort(404);

		}

		return $next($request);

	}

}
