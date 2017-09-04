<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

use z5internet\ReactUserFramework\App\Http\Controllers\Routing\routesController;

class CheckForReferralURL
{

	public function handle($request, Closure $next) {

		$response = $next($request);

		if ($response->getStatusCode() == '404' && preg_match('#^/([^/\.]*)$#', $request->getRequestUri(), $r)) {

			$response = (new routesController($request))->referer($r[1]);

		}

		return $response;

	}

}
