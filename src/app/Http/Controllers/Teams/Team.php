<?php namespace z5internet\ReactUserFramework\App\Http\Controllers\Teams;

use z5internet\ReactUserFramework\App\Http\Controllers\Controller;

class Team extends Controller {

	public function __construct() {

		$this->teamsController = new TeamsController;

	}

	public function currentTeam() {

		return $this->teamsController->getCurrentTeamForThisUser();

	}

}
