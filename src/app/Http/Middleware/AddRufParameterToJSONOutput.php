<?php namespace z5internet\ReactUserFramework\App\Http\Middleware;

use Closure;

class AddRufParameterToJSONOutput
{

	public function handle($request, Closure $next) {

		$response = $next($request);

		$content = $response->content();

		if (is_a($content = json_decode($content), 'stdCLass')) {

			if (!isset($content->data)) {

				$content->data = [];

			}

			if (is_array($content->data)) {

				$content->data['rufP'] = 1;

			}
			else
			{

				$content->data->rufP = 1;

			}

			$response->setContent(collect($content));

		}

		return $response;

	}

}
