<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

class CorsMiddleware
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

        $origin = $request->headers->get('Origin');

		if ($origin <> $this->getURLFromConfig()) {

			abort(405);

		}

		if ($request->isMethod('OPTIONS')) {

			$response = response('', 200);

			// Adds headers to the response
			$response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
			$response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
			$response->header('Access-Control-Allow-Origin', $this->getURLFromConfig());
			$response->header('Access-Control-Allow-Credentials', 'true');

		} else {

			$response = $next($request);

	        $response->headers->set('Access-Control-Allow-Origin', $origin);
	        $response->headers->set('Access-Control-Allow-Credentials', 'true');

		}

		return $response;

	}

	public function getURLFromConfig() {

        return config('app.url');

	}

}
