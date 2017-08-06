<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Routing;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use z5internet\ReactUserFramework\App\Http\Controllers\Teams\TeamsController;

class teamRoutes extends Controller
{

	public function __construct() {

		$this->teamsController = new TeamsController;

	}

}
