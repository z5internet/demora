<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

use z5internet\ReactUserFramework\App\Http\Controllers\Auth\AuthenticationController;

class RefreshToken
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

		$response = $next($request);

        $tokenAlreadySet = false;

        if ($response->headers->get('Authorization')) {

            $tokenAlreadySet = true;

        }

        if (!$tokenAlreadySet) {

            foreach ($response->headers->getCookies() as $cookie) {

                if ($cookie->getName() == 'rufT') {

                    $tokenAlreadySet = true;

                }

            }

        }

        if ($tokenAlreadySet) {

            return $response;

        }

		$AuthenticationController = new AuthenticationController($request);

		if ($AuthenticationController->token) {

            $token = $AuthenticationController->refreshToken();

            switch ($AuthenticationController->cookieOrHeader) {

                case 'cookie':

                    $response->withCookie($AuthenticationController->cookie($token));

                break;

                case 'header':

                    $response->headers->set('Authorization', 'Bearer '.$token);

                break;

            }

        }

        return $response;

	}

	public function getURLFromConfig() {

        return config('app.url');

	}

}
